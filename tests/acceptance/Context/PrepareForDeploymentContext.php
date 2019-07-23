<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Akeneo\Application\Deployment\PreparePimForDeployment;
use Akeneo\Application\Deployment\PreparePimForDeploymentHandler;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Behat\Behat\Context\Context;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

final class PrepareForDeploymentContext implements Context
{
    public const PEC_COMPOSER_JSON = <<<JSON
{
    "name": "akeneo/pim-enterprise-cloud",
    "description": "The \"Akeneo Enterprise Cloud Edition\" distribution",
    "license": "Proprietary",
    "type": "project",
    "authors": [
        {
            "name": "Akeneo",
            "homepage": "http://www.akeneo.com"
        }
    ],
    "require": {
        "php": "7.2.*",
        "akeneo/pim-onboarder": "2.2.0",
        "akeneo/pim-community-dev": "3.0.31",
        "akeneo/pim-enterprise-dev": "3.0.31",
        "doctrine/collections": "1.5.0",
        "google/cloud-error-reporting": "0.14.2",
        "grpc/grpc": "1.19.0"
    },
    "require-dev": {
        "doctrine/migrations": "1.5.0",
        "doctrine/doctrine-migrations-bundle": "1.2.1"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/akeneo/pim-enterprise-dev.git",
            "branch": "master"
        },
        {
            "type": "vcs",
            "url":  "https://github.com/akeneo/pim-onboarder.git",
            "branch": "master"
        }
    ],
    "minimum-stability": "stable",
    "extra": {
        "symfony-app-dir": "app",
        "symfony-bin-dir": "bin",
        "symfony-var-dir": "var",
        "symfony-web-dir": "web",
        "symfony-assets-install": "relative"
    }
}
JSON;

    private $preparePimForDeploymentHandler;
    private $filesystem;

    public function __construct(
        PreparePimForDeploymentHandler $preparePimForDeploymentHandler,
        FilesystemInterface $filesystem
    ) {
        $this->preparePimForDeploymentHandler = $preparePimForDeploymentHandler;
        $this->filesystem = $filesystem;

        $this->filesystem->write('composer.json', static::PEC_COMPOSER_JSON);
    }

    /**
     * @When I want to test the PIM Onboarder bundle to be released
     */
    public function testPimOnboarderBundle(): void
    {
        $organization = new Organization('akeneo');
        $project = new Project('pim-onboarder');
        $branch = new Branch('4.2');
        $repository = new Repository($organization, $project, $branch);

        ($this->preparePimForDeploymentHandler)(new PreparePimForDeployment(
            $repository,
            CommonContext::$onboarderRelease->getWorkingDirectory()
        ));
    }

    /**
     * @Then the PIM Enterprise Cloud dependencies are updated accordingly
     */
    public function pecDependenciesUpdated(): void
    {
        $expectedDependencies = [
            'php' => '7.2.*',
            'akeneo/pim-onboarder' => '4.2.x-dev#eb39d8227797b960796fc1662b24da234c5cda13@dev',
            'akeneo/pim-community-dev' => '3.0.31',
            'akeneo/pim-enterprise-dev' => '3.0.31',
            'doctrine/collections' => '1.5.0',
            'google/cloud-error-reporting' => '0.14.2',
            'grpc/grpc' => '1.19.0',
        ];

        $composerJsonAsArray = json_decode($this->filesystem->read('composer.json'), true);
        Assert::same(
            $composerJsonAsArray['require'],
            $expectedDependencies
        );

        $composerLockAsArray = json_decode($this->filesystem->read('composer.lock'), true);
        Assert::same(
            $composerLockAsArray,
            $expectedDependencies
        );
    }
}
