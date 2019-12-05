<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Vcs;

use Akeneo\Patoche\Application\Vcs\DownloadArchive;
use Akeneo\Patoche\Application\Vcs\DownloadArchiveHandler;
use Akeneo\Patoche\Application\Vcs\VcsApiClient;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Repository;
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
