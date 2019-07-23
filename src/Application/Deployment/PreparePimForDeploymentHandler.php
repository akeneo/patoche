<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Deployment;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Deployment\Dependency;

final class PreparePimForDeploymentHandler
{
    private $vcsApiClient;
    private $dependencyManagerFactory;

    public function __construct(VcsApiClient $vcsApiClient, DependencyManagerFactory $dependencyManagerFactory)
    {
        $this->vcsApiClient = $vcsApiClient;
        $this->dependencyManagerFactory = $dependencyManagerFactory;
    }

    public function __invoke(PreparePimForDeployment $preparePimForDeployment): void
    {
        $workingDirectory = $preparePimForDeployment->getWorkingDirectory();
        $organization = $preparePimForDeployment->getRepository()->getOrganization();
        $project = $preparePimForDeployment->getRepository()->getProject();
        $branch = $preparePimForDeployment->getRepository()->getBranch();
        $commit = $this->vcsApiClient->getLastCommitForBranch($organization, $project, $branch);

        $dependencyManager = $this->dependencyManagerFactory->create(
            $workingDirectory,
            $organization,
            $project,
            $commit
        );

        $dependencyManager->require(Dependency::fromBranchNameAndCommitReference(
            $organization,
            $project,
            $branch,
            $commit
        ));
        $dependencyManager->update();
    }
}
