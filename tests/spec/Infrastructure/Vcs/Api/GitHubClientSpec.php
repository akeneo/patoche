<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Infrastructure\Vcs\Api;

use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Infrastructure\Vcs\Api\GitHubClient;
use Github\Api\Repo;
use Github\Api\Repository\Contents;
use Github\Client;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;

class GitHubClientSpec extends ObjectBehavior
{
    function let(Client $client, FilesystemInterface $filesystem)
    {
        $this->beConstructedWith($client, $filesystem, 'data');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GitHubClient::class);
    }

    function it_download_a_vcs_repository_archive($client, $filesystem, Repo $repo, Contents $contents)
    {
        $client->api('repo')->willReturn($repo);
        $repo->contents()->willReturn($contents);
        $contents->archive('akeneo', 'onboarder', 'zipball', '4.2')->willReturn('ZIP archive as a string');

        $filesystem->write('release-v4.2.0/onboarder.zip', 'ZIP archive as a string')->shouldBeCalled();

        $this->download(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('4.2'),
            new WorkingDirectory('release-v4.2.0')
        );
    }
}
