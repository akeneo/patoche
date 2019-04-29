<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

class CloneRepositoryHandler
{
    private $vcsApiClient;

    public function __construct(VcsApiClient $vcsApiClient)
    {
        $this->vcsApiClient = $vcsApiClient;
    }

    public function __invoke(CloneRepository $cloneRepository): void
    {
        $repositoryName = $cloneRepository->getRepository()->getName();
        $repositoryBranch = $cloneRepository->getRepository()->getBranch();
        $cloneDestination = (string) $cloneRepository->getWorkingDirectory();

        $this->vcsApiClient->clone($repositoryName, $repositoryBranch, $cloneDestination);
    }
}
