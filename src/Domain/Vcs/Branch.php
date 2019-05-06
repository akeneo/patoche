<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Vcs;

use Akeneo\Domain\Vcs\Exception\InvalidBranchName;

final class Branch
{
    private $branchName;

    public function __construct(string $branchName)
    {
        if (0 === preg_match('/^\d+.\d+$/', $branchName)) {
            throw new InvalidBranchName($branchName);
        }

        $this->branchName = $branchName;
    }

    public function __toString(): string
    {
        return $this->branchName;
    }
}
