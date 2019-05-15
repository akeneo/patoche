<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Vcs;

use Akeneo\Application\Vcs\GetNextTag;
use Akeneo\Application\Vcs\GetNextTagHandler;
use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Akeneo\Domain\Vcs\Tags;
use PhpSpec\ObjectBehavior;

class GetNextTagHandlerSpec extends ObjectBehavior
{
    function let(VcsApiClient $vcsApiClient)
    {
        $this->beConstructedWith($vcsApiClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GetNextTagHandler::class);
    }

    function it_gets_the_next_tag($vcsApiClient)
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder');
        $branch = new Branch('4.2');
        $repository = new Repository($organization, $project, $branch);

        $tags = Tags::fromListTagsApiResponse([['name' => 'v4.2.1']]);
        $vcsApiClient->listTags($organization, $project)->willReturn($tags);

        $tag = $this->__invoke(new GetNextTag($repository));
        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getVcsTag()->shouldReturn('v4.2.2');
    }
}
