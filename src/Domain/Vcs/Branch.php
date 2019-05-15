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

final class Branch
{
    private $branchName;

    public function __construct(string $branchName)
    {
        Assert::notEmpty($branchName, 'You must specify a branch to work on.');
        Assert::regex($branchName, '/^\d+.\d+$/', sprintf(
            'The branch name must correspond to a minor version (i.e. "4.2", "10.0"), "%s" provided.',
            $branchName
        ));

        $this->branchName = $branchName;
    }

    public function __toString(): string
    {
        return $this->branchName;
    }
}
