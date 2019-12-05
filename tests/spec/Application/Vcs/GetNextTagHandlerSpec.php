<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Vcs;

use Akeneo\Patoche\Application\Vcs\GetNextTag;
use Akeneo\Patoche\Application\Vcs\GetNextTagHandler;
use Akeneo\Patoche\Application\Vcs\VcsApiClient;
use Akeneo\Patoche\Domain\Common\Tag;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Repository;
use Akeneo\Patoche\Domain\Vcs\Tags;
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
