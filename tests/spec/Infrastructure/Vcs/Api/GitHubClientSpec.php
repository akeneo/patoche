<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Infrastructure\Vcs\Api;

use Akeneo\Infrastructure\Vcs\Api\GitHubClient;
use PhpSpec\ObjectBehavior;

class GitHubClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GitHubClient::class);
    }
}
