<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Exception;

use Akeneo\Application\Exception\BranchNotMapped;
use Akeneo\Domain\Vcs\Branch;
use PhpSpec\ObjectBehavior;

class BranchNotMappedSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Branch('1.0'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BranchNotMapped::class);
    }

    function it_returns_a_message()
    {
        $this->getMessage()->shouldReturn(
            'Onboarder branch "1.0" is not mapped to any PIM Enterprise Cloud branch.'
        );
    }
}
