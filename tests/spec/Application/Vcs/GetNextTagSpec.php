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
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class GetNextTagSpec extends ObjectBehavior
{
    private $repository;

    function let()
    {
        $this->repository = new Repository(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('4.2')
        );

        $this->beConstructedWith($this->repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GetNextTag::class);
    }

    function it_returns_the_repository()
    {
        $this->getRepository()->shouldReturn($this->repository);
    }
}
