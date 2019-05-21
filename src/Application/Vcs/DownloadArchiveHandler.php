<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

class DownloadArchiveHandler
{
    private $vcsApiClient;

    public function __construct(VcsApiClient $vcsApiClient)
    {
        $this->vcsApiClient = $vcsApiClient;
    }

    public function __invoke(DownloadArchive $downloadArchive): void
    {
        $organization = $downloadArchive->getRepository()->getOrganization();
        $project = $downloadArchive->getRepository()->getProject();
        $branch = $downloadArchive->getRepository()->getBranch();
        $destination = $downloadArchive->getWorkingDirectory();

        $this->vcsApiClient->download($organization, $project, $branch, $destination);
    }
}
