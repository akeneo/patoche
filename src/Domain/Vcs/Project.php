<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Vcs;

class Project
{
    private $projectName;

    public function __construct(string $projectName)
    {
        $this->projectName = $projectName;
    }

    public function __toString(): string
    {
        return $this->projectName;
    }
}