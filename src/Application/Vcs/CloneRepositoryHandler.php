<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

final class CloneRepositoryHandler
{
    private $vcsApiClient;

    public function __construct(VcsApiClient $vcsApiClient)
    {
        $this->vcsApiClient = $vcsApiClient;
    }

    public function __invoke(CloneRepository $cloneRepository): void
    {
        $organization = (string) $cloneRepository->getRepository()->getOrganization();
        $project = (string) $cloneRepository->getRepository()->getProject();
        $branch = (string) $cloneRepository->getRepository()->getBranch();
        $destination = (string) $cloneRepository->getWorkingDirectory();

        $this->vcsApiClient->clone($organization, $project, $branch, $destination);
    }
}
