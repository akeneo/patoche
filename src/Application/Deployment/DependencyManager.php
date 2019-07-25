<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Deployment;

use Akeneo\Domain\Deployment\Dependency;

interface DependencyManager
{
    public function require(Dependency $dependency): void;

    public function update(): void;
}