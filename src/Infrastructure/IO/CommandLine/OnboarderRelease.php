<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Infrastructure\IO\CommandLine;

use Akeneo\Application\ReleaseProcess;
use Akeneo\Application\Vcs\GetNextTag;
use Akeneo\Application\Vcs\GetNextTagHandler;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Workflow\WorkflowInterface;

final class OnboarderRelease extends Command
{
    private $getNextTagHandler;
    private $workflow;

    protected static $defaultName = 'akeneo:patoche:onboarder-release';

    public function __construct(GetNextTagHandler $getNextTagHandler, WorkflowInterface $workflow)
    {
        parent::__construct();

        $this->getNextTagHandler = $getNextTagHandler;
        $this->workflow = $workflow;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Release the entire Onboarder stack.')
            ->addArgument(
                'branch',
                InputArgument::REQUIRED,
                'The branch Patoche will tag from. It must be a stable minor branch (i.e. "4.2" or "1.0")'
            )
            ->addArgument(
                'organization',
                InputArgument::OPTIONAL,
                'The GitHub organization Patoche will tag from.',
                'akeneo'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $releaseProcess = $this->startReleaseProcess($input, $output);
        if (null === $releaseProcess) {
            return 1;
        }

        $output->writeln(sprintf(
            '<info>Starting release process for version "%s"</info>',
            $releaseProcess->getTag()->getDockerTag()
        ));

        while ([] !== $enabledTransitions = $this->workflow->getEnabledTransitions($releaseProcess)) {
            foreach ($enabledTransitions as $transition) {
                $this->workflow->apply($releaseProcess, $transition->getName());
            }
        }

        $output->writeln(sprintf(
            '<info>Process finished. Onboarder %s is released.</info>',
            $releaseProcess->getTag()->getVcsTag()
        ));

        return 0;
    }

    /**
     * We get the last tag from one of the Onboarder repositories.
     * Using the supplier-onboarder repository is an arbitrary choice.
     *
     * We need to validate that the inputs are strings and initialize them to satisfy PHPStan.
     * This wouldn't be needed if Symfony inputs could be typed :/.
     */
    private function startReleaseProcess(InputInterface $input, OutputInterface $output): ?ReleaseProcess
    {
        $organizationInput = $input->getArgument('organization');
        if (!is_string($organizationInput)) {
            $organizationInput = '';
        }

        $branchInput = $input->getArgument('branch');
        if (!is_string($branchInput)) {
            $branchInput = '';
        }

        $organization = new Organization($organizationInput);
        $project = new Project(Project::SUPPLIER_ONBOARDER);
        $branch = new Branch($branchInput);
        $repository = new Repository($organization, $project, $branch);

        $getNextTag = new GetNextTag($repository);
        $nextTag = ($this->getNextTagHandler)($getNextTag);

        $output->writeln(sprintf('Proposed tag for this release is "%s".', $nextTag->getDockerTag()));

        $questionHelper = $this->getHelper('question');
        $confirmation = new ConfirmationQuestion('Is it OK? [Y/n] ', true);
        if (!$questionHelper->ask($input, $output, $confirmation)) {
            $question = new Question(
                'Please enter the tag you want to use for this release (only numbers, no "v" at the beginning): '
            );
            $userDefinedTag = $questionHelper->ask($input, $output, $question);

            try {
                $nextTag = Tag::fromGenericTag($userDefinedTag);
            } catch (\InvalidArgumentException $exception) {
                $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
                $output->writeln('<error>Aborting release process.</error>');

                return null;
            }
        }

        return new ReleaseProcess($branch, $nextTag, $organization);
    }
}
