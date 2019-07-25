<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Integration\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManagerFactory;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Deployment\Dependency;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Infrastructure\Deployment\DependencyManager\Composer;
use Akeneo\Tests\Integration\TestCase;
use League\Flysystem\Filesystem;

final class ComposerIntegration extends TestCase
{
    private const TESTED_WORKING_DIRECTORY = 'release-v0.0.0';
    private const TESTED_ORGANIZATION = 'akeneo';
    private const TESTED_PROJECT = 'patoche';
    private const TESTED_COMMIT = '7757b6a0ee80313fbbc42c2b7013fa523929c8c3';

    private const COMPOSER_JSON = <<<JSON
{
    "name": "akeneo/patoche",
    "authors": [
        {
            "name": "Akeneo",
            "homepage": "http://www.akeneo.com"
        }
    ],
    "type": "project",
    "license": "proprietary",
    "require": {
        "php": "7.3.*"
    },
    "config": {
        "preferred-install": {
            "*": "dist"
        },
        "sort-packages": true
    },
    "autoload": {
        "psr-4": {
            "Akeneo\\\\": "src/"
        }
    }
}

JSON;

    public function setUp(): void
    {
        parent::setUp();

        $this->container()->get(Filesystem::class)->createDir($this->pathToPatoche());
        $this->container()->get(Filesystem::class)->write(
            $this->pathToPatoche() . DIRECTORY_SEPARATOR . 'composer.json',
            static::COMPOSER_JSON
        );
    }

    /** @test */
    public function itRequiresANewDependency(): void
    {
        $dependency = $this->dependency(
            'symfony',
            'process',
            '4.3',
            '856d35814cf287480465bb7a6c413bb7f5f5e69c'
        );
        $composer = $this->instantiateComposer();

        $composer->require($dependency);

        $this->assertDependencyIsRequired(
            'symfony/process',
            '4.3.x-dev#856d35814cf287480465bb7a6c413bb7f5f5e69c@dev'
        );
    }

    /** @test */
    public function itUpdatesAnAlreadyPresentDependency(): void
    {
        $this->setDependency('symfony/process', '4.3.0');

        $dependency = $this->dependency(
            'symfony',
            'process',
            '4.3',
            '856d35814cf287480465bb7a6c413bb7f5f5e69c'
        );
        $composer = $this->instantiateComposer();

        $composer->require($dependency);

        $this->assertDependencyIsRequired(
            'symfony/process',
            '4.3.x-dev#856d35814cf287480465bb7a6c413bb7f5f5e69c@dev'
        );
    }

    /** @test */
    public function itLocksDependencies(): void
    {
        $composer = $this->instantiateComposer();

        $composer->update();

        $this->assertDependenciesAreLockedWithCommitHash();
    }

    /** @test */
    public function itUpdatesLockedDependencies(): void
    {
        $composer = $this->instantiateComposer();

        $composer->update();

        $composer->require($this->dependency(
            'symfony',
            'process',
            '4.3',
            '856d35814cf287480465bb7a6c413bb7f5f5e69c'
        ));

        $composer->update();

        $this->assertLockedDependenciesAreUpdated();
    }

    /** @test */
    public function itAddsLockedDependencies(): void
    {
        $this->setDependency('symfony/process', '4.3.0');

        $composer = $this->instantiateComposer();

        $composer->update();

        $this->assertDependenciesAreLockedWithSemanticVersion();

        $composer->require($this->dependency(
            'symfony',
            'process',
            '4.3',
            '856d35814cf287480465bb7a6c413bb7f5f5e69c'
        ));

        $composer->update();

        $this->assertLockedDependenciesAreUpdated();
    }

    /** @test */
    public function itThrowsAnExceptionIfComposerExecutableIsNotAvailable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find composer executable "/there/is/no/composer".');

        $_ENV['PATH_TO_COMPOSER_EXECUTABLE'] = '/there/is/no/composer';
        static::$kernel = static::bootKernel(['debug' => false, 'environment' => 'integration']);

        $this->instantiateComposer();
    }

    private function dependency(string $organization, string $project, string $branch, string $commit): Dependency
    {
        return Dependency::fromBranchNameAndCommitReference(
            new Organization($organization),
            new Project($project),
            new Branch($branch),
            Commit::fromBranchesApiResponse([
                'commit' => [
                    'sha' => $commit,
                ],
            ])
        );
    }

    private function instantiateComposer(): Composer
    {
        $composerFactory = $this->container()->get(DependencyManagerFactory::class);

        return $composerFactory->create(
            new WorkingDirectory(static::TESTED_WORKING_DIRECTORY),
            new Organization(static::TESTED_ORGANIZATION),
            new Project(static::TESTED_PROJECT),
            Commit::fromBranchesApiResponse([
                'commit' => [
                    'sha' => static::TESTED_COMMIT,
                ],
            ])
        );
    }

    private function assertDependencyIsRequired(string $dependency, string $version): void
    {
        $composerJsonAsArray = json_decode($this->getComposerJson(), true);

        $this->assertArrayHasKey($dependency, $composerJsonAsArray['require']);
        $this->assertSame($version, $composerJsonAsArray['require']['symfony/process']);
    }

    private function setDependency(string $dependency, string $version): void
    {
        $composerJsonAsArray = json_decode($this->getComposerJson(), true);

        $composerJsonAsArray['require'][$dependency] = $version;

        $this->container()->get(Filesystem::class)->update(
            $this->pathToPatoche() . DIRECTORY_SEPARATOR . 'composer.json',
            json_encode($composerJsonAsArray)
        );

        $this->assertDependencyIsRequired($dependency, $version);
    }

    private function pathToPatoche(): string
    {
        $patocheDirectory = sprintf(
            '%s-%s-%s',
            static::TESTED_ORGANIZATION,
            static::TESTED_PROJECT,
            static::TESTED_COMMIT
        );

        return static::TESTED_WORKING_DIRECTORY . DIRECTORY_SEPARATOR . $patocheDirectory;
    }

    private function assertDependenciesAreLockedWithCommitHash(): void
    {
        $composerLock = <<<JSON
{
    "_readme": [
        "This file locks the dependencies of your project to a known state",
        "Read more about it at https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies",
        "This file is @generated automatically"
    ],
    "content-hash": "47f6899d68bc54b621862d591ae2086e",
    "packages": [],
    "packages-dev": [],
    "aliases": [],
    "minimum-stability": "stable",
    "stability-flags": [],
    "prefer-stable": false,
    "prefer-lowest": false,
    "platform": {
        "php": "7.3.*"
    },
    "platform-dev": []
}

JSON;

        $this->assertSame($composerLock, $this->getComposerLock());
    }

    private function assertDependenciesAreLockedWithSemanticVersion(): void
    {
        $composerLock = <<<JSON
{
    "_readme": [
        "This file locks the dependencies of your project to a known state",
        "Read more about it at https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies",
        "This file is @generated automatically"
    ],
    "content-hash": "1f5ff111972f4ba0090d53c3d0f4329b",
    "packages": [
        {
            "name": "symfony/process",
            "version": "v4.3.0",
            "source": {
                "type": "git",
                "url": "https://github.com/symfony/process.git",
                "reference": "a5e3dd4e93a364668034a3cb6efa963d0b33ab45"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/symfony/process/zipball/a5e3dd4e93a364668034a3cb6efa963d0b33ab45",
                "reference": "a5e3dd4e93a364668034a3cb6efa963d0b33ab45",
                "shasum": ""
            },
            "require": {
                "php": "^7.1.3"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "4.3-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Symfony\\\\Component\\\\Process\\\\": ""
                },
                "exclude-from-classmap": [
                    "/Tests/"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Fabien Potencier",
                    "email": "fabien@symfony.com"
                },
                {
                    "name": "Symfony Community",
                    "homepage": "https://symfony.com/contributors"
                }
            ],
            "description": "Symfony Process Component",
            "homepage": "https://symfony.com",
            "time": "2019-05-26T20:47:49+00:00"
        }
    ],
    "packages-dev": [],
    "aliases": [],
    "minimum-stability": "stable",
    "stability-flags": [],
    "prefer-stable": false,
    "prefer-lowest": false,
    "platform": {
        "php": "7.3.*"
    },
    "platform-dev": []
}

JSON;

        $this->assertSame($composerLock, $this->getComposerLock());
    }

    private function assertLockedDependenciesAreUpdated(): void
    {
        $composerLock = <<<JSON
{
    "_readme": [
        "This file locks the dependencies of your project to a known state",
        "Read more about it at https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies",
        "This file is @generated automatically"
    ],
    "content-hash": "16b2ac27e82fb41e2925b9f9009fd98c",
    "packages": [
        {
            "name": "symfony/process",
            "version": "4.3.x-dev",
            "source": {
                "type": "git",
                "url": "https://github.com/symfony/process.git",
                "reference": "856d35814cf287480465bb7a6c413bb7f5f5e69c"
            },
            "dist": {
                "type": "zip",
                "url": "https://api.github.com/repos/symfony/process/zipball/856d35814cf287480465bb7a6c413bb7f5f5e69c",
                "reference": "856d35814cf287480465bb7a6c413bb7f5f5e69c",
                "shasum": ""
            },
            "require": {
                "php": "^7.1.3"
            },
            "type": "library",
            "extra": {
                "branch-alias": {
                    "dev-master": "4.3-dev"
                }
            },
            "autoload": {
                "psr-4": {
                    "Symfony\\\\Component\\\\Process\\\\": ""
                },
                "exclude-from-classmap": [
                    "/Tests/"
                ]
            },
            "notification-url": "https://packagist.org/downloads/",
            "license": [
                "MIT"
            ],
            "authors": [
                {
                    "name": "Fabien Potencier",
                    "email": "fabien@symfony.com"
                },
                {
                    "name": "Symfony Community",
                    "homepage": "https://symfony.com/contributors"
                }
            ],
            "description": "Symfony Process Component",
            "homepage": "https://symfony.com",
            "time": "2019-05-30T16:10:05+00:00"
        }
    ],
    "packages-dev": [],
    "aliases": [],
    "minimum-stability": "stable",
    "stability-flags": {
        "symfony/process": 20
    },
    "prefer-stable": false,
    "prefer-lowest": false,
    "platform": {
        "php": "7.3.*"
    },
    "platform-dev": []
}

JSON;

        $this->assertSame($composerLock, $this->getComposerLock());
    }

    private function getComposerJson(): string
    {
        return $this->container()->get(Filesystem::class)->read(
            $this->pathToPatoche() . DIRECTORY_SEPARATOR . 'composer.json'
        );
    }

    private function getComposerLock(): string
    {
        return $this->container()->get(Filesystem::class)->read(
            $this->pathToPatoche() . DIRECTORY_SEPARATOR . 'composer.lock'
        );
    }
}