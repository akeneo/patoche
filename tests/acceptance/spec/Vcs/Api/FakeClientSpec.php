<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Tests\Acceptance\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Tagging\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Tests\Acceptance\Vcs\Api\FakeClient;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;

class FakeClientSpec extends ObjectBehavior
{
    function let(FilesystemInterface $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FakeClient::class);
    }

    function it_is_a_vcs_api_client()
    {
        $this->shouldImplement(VcsApiClient::class);
    }

    function it_fakes_cloning_a_repository($filesystem)
    {
        $filesystem->write(
            'release-4.2.0/onboarder/README.md',
            'Cloning akeneo/onboarder 4.2'
        )->shouldBeCalled();

        $this->clone(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('4.2'),
            new WorkingDirectory('release-4.2.0')
        );
    }
}