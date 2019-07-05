<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Vcs;

use Webmozart\Assert\Assert;

final class Commit
{
    private $sha;

    public function __construct(string $sha)
    {
        Assert::notEmpty($sha, 'A commit SHA cannot be empty.');

        $this->sha = $sha;
    }

    public function __toString(): string
    {
        return $this->sha;
    }
}
