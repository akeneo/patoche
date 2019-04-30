<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

interface VcsApiClient
{
    public function clone(string $organization, string $project, string $branch, string $destination): void;
}
