<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManager;
use Akeneo\Domain\Deployment\Dependency;

final class FakeComposer implements DependencyManager
{
    private $composerJson;
    private $composerLock = [];

    public function __construct(string $composerJson)
    {
        $this->composerJson = $composerJson;
    }

    public function require(Dependency $dependency): void
    {
        list($dependencyName, $dependencyVersion) = explode(':', (string) $dependency);
        $composerJsonAsArray = json_decode($this->composerJson, true);

        $composerJsonAsArray['require'][$dependencyName] = $dependencyVersion;

        $this->composerJson = json_encode($composerJsonAsArray);
    }

    public function update(): void
    {
        $composerJsonAsArray = json_decode($this->composerJson, true);
        $this->composerLock = $composerJsonAsArray['require'];
    }

    public function getComposerJson(): string
    {
        return $this->composerJson;
    }

    public function getComposerLock(): array
    {
        return $this->composerLock;
    }
}
