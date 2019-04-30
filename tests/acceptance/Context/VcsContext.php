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
use Akeneo\Domain\Tagging\TaggingProcess;
use Akeneo\Domain\Tagging\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Behat\Behat\Context\Context;

class VcsContext implements Context
{
    /** @var TaggingProcess */
    private $taggingProcess;

    /** @var CloneRepositoryHandler */
    private $cloneRepositoryHandler;

    public function __construct(CloneRepositoryHandler $cloneRepositoryHandler)
    {
        $this->cloneRepositoryHandler = $cloneRepositoryHandler;
    }

    /**
     * @Given a new version of the Onboarder is going to be released
     */
    public function newOnboarderRelease(): void
    {
        $this->taggingProcess = new TaggingProcess('1.0', '1.1.1', 'akeneo');
    }

    /**
     * @When I clone the :projectName vcs repository
     */
    public function cloneRepository(string $projectName): void
    {
        $repository = new Repository(
            new Organization($this->taggingProcess->getOrganization()),
            new Project($projectName),
            new Branch($this->taggingProcess->getBranch())
        );

        $workingDirectory = new WorkingDirectory($this->taggingProcess->getWorkingDirectory());

        ($this->cloneRepositoryHandler)(new CloneRepository($repository, $workingDirectory));
    }

    /**
     * @Then the :projectName project is available locally
     */
    public function projectAvailableLocally(string $projectName): void
    {
        throw new \LogicException('Not implemented step!');
    }
}
