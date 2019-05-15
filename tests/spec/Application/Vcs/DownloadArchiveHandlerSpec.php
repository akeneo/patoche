<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Vcs;

use Akeneo\Application\Vcs\DownloadArchive;
use Akeneo\Application\Vcs\DownloadArchiveHandler;
use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class DownloadArchiveHandlerSpec extends ObjectBehavior
{
    function let(VcsApiClient $vcsApiClient)
    {
        $this->beConstructedWith($vcsApiClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DownloadArchiveHandler::class);
    }

    function it_downloads_a_vcs_repository_archive($vcsApiClient)
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder');
        $branch = new Branch('4.2');
        $workingDirectory = new WorkingDirectory('release-v4.2.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $workingDirectory
        );

        $vcsApiClient->download($organization, $project, $branch, $workingDirectory)->shouldBeCalled();

        $this->__invoke($downloadArchive);
    }
}
