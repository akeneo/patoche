<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Infrastructure\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Infrastructure\Vcs\Api\FakeClient;
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
            'release-4.2.0-5cc30e180c6fb/onboarder/README.md',
            'Cloning akeneo/onboarder 4.2'
        )->shouldBeCalled();

        $this->clone('akeneo', 'onboarder', '4.2', 'release-4.2.0-5cc30e180c6fb');
    }
}