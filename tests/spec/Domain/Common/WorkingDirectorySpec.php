<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Common;

use Akeneo\Domain\Common\WorkingDirectory;
use PhpSpec\ObjectBehavior;

class WorkingDirectorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('release-v4.2.0');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WorkingDirectory::class);
    }

    function it_returns_the_working_directory_name()
    {
        $this->__toString()->shouldReturn('release-v4.2.0');
    }

    function it_throws_an_exception_if_the_directory_name_is_empty()
    {
        $this->beConstructedWith('');

        $this
            ->shouldThrow(new \InvalidArgumentException('You must specify a working directory.'))
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_the_directory_name_is_not_based_on_the_tag_to_release()
    {
        $this->beConstructedWith('foobar');

        $this
            ->shouldThrow(new \InvalidArgumentException(
                'The working directory must be named using the tag to release, "foobar" provided.',
            ))
            ->duringInstantiation();
    }
}
