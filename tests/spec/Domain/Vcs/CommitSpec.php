<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs;

use Akeneo\Domain\Vcs\Commit;
use PhpSpec\ObjectBehavior;

class CommitSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('eb39d8227797b960796fc1662b24da234c5cda13');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Commit::class);
    }

    function it_returns_the_commit_sha()
    {
        $this->__toString()->shouldReturn('eb39d8227797b960796fc1662b24da234c5cda13');
    }

    function it_throws_an_exception_if_the_commit_sha_is_empty()
    {
        $this->beConstructedWith('');

        $this
            ->shouldThrow(new \InvalidArgumentException('A commit SHA cannot be empty.'))
            ->duringInstantiation();
    }
}
