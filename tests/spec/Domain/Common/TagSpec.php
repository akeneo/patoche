<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Domain\Common;

use Akeneo\Patoche\Domain\Common\Tag;
use PhpSpec\ObjectBehavior;

class TagSpec extends ObjectBehavior
{
    function it_is_initializable_from_a_generic_tag_name()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.1']);

        $this->shouldHaveType(Tag::class);
    }

    function it_is_initializable_from_a_vcs_tag_name()
    {
        $this->beConstructedThrough('fromVcsTag', ['v4.2.1']);

        $this->shouldHaveType(Tag::class);
    }

    function it_returns_the_vcs_tag()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.1']);

        $this->getVcsTag()->shouldReturn('v4.2.1');
    }

    function it_returns_the_docker_tag()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.1']);

        $this->getDockerTag()->shouldReturn('4.2.1');
    }

    function it_proposes_the_next_tag()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.1']);

        $nextTag = $this->nextTag();

        $nextTag->shouldBeAnInstanceOf(Tag::class);
        $nextTag->getVcsTag()->shouldReturn('v4.2.2');
    }

    function it_proposes_the_next_tag_with_metadata()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.1-02']);

        $nextTag = $this->nextTag();

        $nextTag->shouldBeAnInstanceOf(Tag::class);
        $nextTag->getVcsTag()->shouldReturn('v4.2.1-03');
    }

    function it_returns_the_corresponding_vcs_branch()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.1']);

        $this->getVcsBranchName()->shouldReturn('4.2');
    }

    function it_proposes_the_next_multi_digit_tag()
    {
        $this->beConstructedThrough('fromGenericTag', ['42.666.0']);

        $nextTag = $this->nextTag();

        $nextTag->shouldBeAnInstanceOf(Tag::class);
        $nextTag->getVcsTag()->shouldReturn('v42.666.1');
    }

    function it_returns_the_corresponding_vcs_branch_for_multi_digit_version()
    {
        $this->beConstructedThrough('fromGenericTag', ['42.666.0']);

        $this->getVcsBranchName()->shouldReturn('42.666');
    }

    function it_throws_an_exception_if_the_tag_is_empty()
    {
        $this->beConstructedThrough('fromGenericTag', ['']);

        $this
            ->shouldThrow(new \InvalidArgumentException('You must specify a tag for the release.'))
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_the_tag_is_not_a_version_number()
    {
        $this->beConstructedThrough('fromGenericTag', ['foobar']);

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must respect Semantic Versioning with, optionally, two digits metadata (6.6.6-01), "foobar" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_tag_does_not_respect_semantic_versioning()
    {
        $this->beConstructedThrough('fromGenericTag', ['10']);

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must respect Semantic Versioning with, optionally, two digits metadata (6.6.6-01), "10" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_tag_metadata_are_less_than_two_digits()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.0-1']);

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must respect Semantic Versioning with, optionally, two digits metadata (6.6.6-01), "4.2.0-1" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_tag_metadata_are_more_than_two_digits()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.0-666']);

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must respect Semantic Versioning with, optionally, two digits metadata (6.6.6-01), "4.2.0-666" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_tag_metadata_are_not_digits()
    {
        $this->beConstructedThrough('fromGenericTag', ['4.2.0-beta']);

        $this->shouldThrow(new \InvalidArgumentException(
            'The tag must respect Semantic Versioning with, optionally, two digits metadata (6.6.6-01), "4.2.0-beta" provided.'
        ))->duringInstantiation();
    }
}
