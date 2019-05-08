<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Common;

use Akeneo\Domain\Common\Tag;
use PhpSpec\ObjectBehavior;

class TagSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('4.2.1');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Tag::class);
    }

    function it_returns_the_vcs_tag()
    {
        $this->getVcsTag()->shouldReturn('v4.2.1');
    }

    function it_returns_the_docker_tag()
    {
        $this->getDockerTag()->shouldReturn('4.2.1');
    }

    function it_throws_an_exception_if_the_tag_is_empty()
    {
        $this->beConstructedWith('');

        $this
            ->shouldThrow(new \InvalidArgumentException('You must specify a tag for the release.'))
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_the_tag_is_not_a_version_number()
    {
        $this->beConstructedWith('foobar');

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must correspond to a patch version (i.e. "4.2.1", "10.0.0"), "foobar" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_branch_name_is_not_a_patch_version()
    {
        $this->beConstructedWith('10');

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must correspond to a patch version (i.e. "4.2.1", "10.0.0"), "10" provided.'
        ))->duringInstantiation();
    }
}
