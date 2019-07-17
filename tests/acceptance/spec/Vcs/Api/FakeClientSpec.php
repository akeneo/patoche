<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Tests\Acceptance\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Tags;
use Akeneo\Tests\Acceptance\Vcs\Api\FakeClient;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;

class FakeClientSpec extends ObjectBehavior
{
    function let(FilesystemInterface $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FakeClient::class);
    }

    function it_is_a_vcs_api_client()
    {
        $this->shouldImplement(VcsApiClient::class);
    }

    function it_fakes_downloading_the_archive_of_a_repository($filesystem)
    {
        $filesystem->write(
            'release-v4.2.1/onboarder/README.md',
            'Cloning akeneo/onboarder 4.2'
        )->shouldBeCalled();

        $this->download(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('4.2'),
            new WorkingDirectory('release-v4.2.1')
        );
    }

    function it_get_the_list_of_the_repository_tags()
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder');

        $tags = $this->listTags($organization, $project);
        $tags->shouldBeAnInstanceOf(Tags::class);
        $tag = $tags->nextTagForBranch(new Branch('4.2'));
        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getVcsTag()->shouldReturn('v4.2.1');
    }

    function it_gets_the_last_commit_of_a_provided_branch()
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder');
        $branch = new Branch('4.2');

        $commit = $this->getLastCommitForBranch($organization, $project, $branch);

        $commit->shouldBeAnInstanceOf(Commit::class);
        $commit->__toString()->shouldReturn('eb39d8227797b960796fc1662b24da234c5cda13');
    }
}
