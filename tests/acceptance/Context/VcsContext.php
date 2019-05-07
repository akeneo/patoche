<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Akeneo\Application\Vcs\CloneRepository;
use Akeneo\Application\Vcs\CloneRepositoryHandler;
use Akeneo\Application\TaggingProcess;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Akeneo\Tests\Acceptance\Vcs\Api\FakeClient;
use Behat\Behat\Context\Context;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

class VcsContext implements Context
{
    private const EXPECTED_DATA = [
        'akeneo' => [
            'onboarder' => [
                '4.2' => 'Cloning akeneo/onboarder 4.2',
            ],
        ],
    ];

    private $cloneRepositoryHandler;
    private $filesystem;

    /** @var TaggingProcess */
    private $taggingProcess;

    public function __construct(CloneRepositoryHandler $cloneRepositoryHandler, FilesystemInterface $filesystem)
    {
        $this->cloneRepositoryHandler = $cloneRepositoryHandler;
        $this->filesystem = $filesystem;
    }

    /**
     * @Given a new version of the Onboarder is going to be released
     */
    public function newOnboarderRelease(): void
    {
        $this->taggingProcess = new TaggingProcess(
            new Branch('4.2'),
            new Tag('4.2.0'),
            new Organization('akeneo')
        );
    }

    /**
     * @When I clone the :projectName vcs repository
     */
    public function cloneRepository(string $projectName): void
    {
        $repository = new Repository(
            $this->taggingProcess->getOrganization(),
            new Project($projectName),
            $this->taggingProcess->getBranch()
        );

        ($this->cloneRepositoryHandler)(new CloneRepository(
            $repository,
            $this->taggingProcess->getWorkingDirectory()
        ));
    }

    /**
     * @Then the :projectName project is available locally
     */
    public function projectAvailableLocally(string $projectName): void
    {
        $readContent = $this->filesystem->read(sprintf(
            '%s/%s/README.md',
            $this->taggingProcess->getWorkingDirectory(),
            $projectName
        ));

        $organization = (string) $this->taggingProcess->getOrganization();
        $branch = (string) $this->taggingProcess->getBranch();
        $expectedContent = static::EXPECTED_DATA[$organization][$projectName][$branch];

        Assert::same($readContent, $expectedContent);
    }
}
