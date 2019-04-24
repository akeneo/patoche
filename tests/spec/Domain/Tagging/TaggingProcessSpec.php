<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Tagging;

use Akeneo\Domain\Tagging\TaggingProcess;
use PhpSpec\ObjectBehavior;

class TaggingProcessSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['original_state']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TaggingProcess::class);
    }

    function it_has_a_state()
    {
        $this->getStates()->shouldReturn(['original_state']);
    }

    function it_can_change_state()
    {
        $this->setStates(['next_state_1', 'next_state_2']);

        $this->getStates()->shouldReturn(['next_state_1', 'next_state_2']);
    }
}
