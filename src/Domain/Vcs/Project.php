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

final class Project
{
    private $projectName;

    public function __construct(string $projectName)
    {
        Assert::notEmpty($projectName, 'You must specify a project name.');

        $this->projectName = $projectName;
    }

    public function __toString(): string
    {
        return $this->projectName;
    }
}
