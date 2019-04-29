<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Tagging;

use Akeneo\Domain\Tagging\WorkingDirectory;
use PhpSpec\ObjectBehavior;

class WorkingDirectorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('release-4.2.0-5cc30e180c6fb');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WorkingDirectory::class);
    }

    function it_returns_the_working_directory_name()
    {
        $this->__toString()->shouldReturn('release-4.2.0-5cc30e180c6fb');
    }
}
