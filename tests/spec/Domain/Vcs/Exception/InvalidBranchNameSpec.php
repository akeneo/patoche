<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs\Exception;

use Akeneo\Domain\Vcs\Exception\InvalidBranchName;
use PhpSpec\ObjectBehavior;

class InvalidBranchNameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InvalidBranchName::class);
    }

    function it_returns_a_dedicated_message()
    {
        $this->beConstructedThrough('build', ['foobar']);

        $this->getMessage()->shouldReturn(
            'The branch name must correspond to a minor version (i.e. "4.2", "10.0"), "foobar" provided.'
        );
    }
}
