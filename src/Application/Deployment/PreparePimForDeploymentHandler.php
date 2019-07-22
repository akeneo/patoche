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
    private $dependencyManager;

    public function __construct(VcsApiClient $vcsApiClient, DependencyManager $dependencyManager)
    {
        $this->vcsApiClient = $vcsApiClient;
        $this->dependencyManager = $dependencyManager;
    }

    public function __invoke(PreparePimForDeployment $preparePimForDeployment): void
    {
        $this->dependencyManager->require($this->dependencyToRequire($preparePimForDeployment));
        $this->dependencyManager->update();
    }

    private function dependencyToRequire(PreparePimForDeployment $preparePimForDeployment): Dependency
    {
        $organization = $preparePimForDeployment->getRepository()->getOrganization();
        $project = $preparePimForDeployment->getRepository()->getProject();
        $branch = $preparePimForDeployment->getRepository()->getBranch();

        $commit = $this->vcsApiClient->getLastCommitForBranch($organization, $project, $branch);

        return Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);
    }
}
