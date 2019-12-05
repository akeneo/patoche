<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Application\Onboarder;

use Akeneo\Patoche\Application\Onboarder\Exception\BranchNotMapped;
use Akeneo\Patoche\Domain\Vcs\Branch;

final class MappedBranches
{
    private $mappedBranches;

    private function __construct(array $mappedBranches)
    {
        $this->mappedBranches = $mappedBranches;
    }

    public static function fromRawMapping(array $rawMappedBranches)
    {
        $mappedBranches = [];
        foreach ($rawMappedBranches as $onboarderBranchName => $pecBranchName) {
            $mappedBranches[$onboarderBranchName] = new Branch($pecBranchName);
        }

        return new self($mappedBranches);
    }

    public function getPecMappedBranched(Branch $branch): Branch
    {
        if (!isset($this->mappedBranches[(string) $branch])) {
            throw new BranchNotMapped($branch);
        }

        return $this->mappedBranches[(string) $branch];
    }
}
