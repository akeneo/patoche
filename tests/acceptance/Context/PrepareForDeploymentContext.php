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
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Akeneo\Tests\Acceptance\Vcs\Api\FakeClient;
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
    private $pathToProject;

    public function __construct(
        PreparePimForDeploymentHandler $preparePimForDeploymentHandler,
        FilesystemInterface $filesystem
    ) {
        $this->preparePimForDeploymentHandler = $preparePimForDeploymentHandler;
        $this->filesystem = $filesystem;

        $this->pathToProject = 'release-v2.2.0' . DIRECTORY_SEPARATOR . 'akeneo-pim-enterprise-cloud-'
            . FakeClient::FAKE_BRANCHES['akeneo']['pim-enterprise-cloud']['3.0']['commit']['sha'];

        $this->filesystem->write(
            $this->pathToProject . DIRECTORY_SEPARATOR . 'composer.json',
            static::PEC_COMPOSER_JSON
        );
    }

    /**
     * @When I want to test the PIM Onboarder bundle to be released
     */
    public function testPimOnboarderBundle(): void
    {
        $dependencyRepository = new Repository(
            CommonContext::$onboarderRelease->getOrganization(),
            new Project(Project::PIM_ONBOARDER_BUNDLE),
            CommonContext::$onboarderRelease->getBranch()
        );

        ($this->preparePimForDeploymentHandler)(new PreparePimForDeployment(
            $dependencyRepository,
            CommonContext::$onboarderRelease->getBranchForProject(new Project(Project::PIM_ENTERPRISE_CLOUD)),
            CommonContext::$onboarderRelease->getWorkingDirectory()
        ));
    }

    /**
     * @Then the PIM Enterprise Cloud dependencies are updated accordingly
     */
    public function pecDependenciesAreUpdated(): void
    {
        $expectedDependencies = [
            'php' => '7.2.*',
            'akeneo/pim-onboarder' => '2.2.x-dev#7757b6a0ee80313fbbc42c2b7013fa523929c8c3@dev',
            'akeneo/pim-community-dev' => '3.0.31',
            'akeneo/pim-enterprise-dev' => '3.0.31',
            'doctrine/collections' => '1.5.0',
            'google/cloud-error-reporting' => '0.14.2',
            'grpc/grpc' => '1.19.0',
        ];

        $composerJsonAsArray = json_decode(
            $this->filesystem->read($this->pathToProject . DIRECTORY_SEPARATOR . 'composer.json'),
            true
        );
        Assert::same(
            $composerJsonAsArray['require'],
            $expectedDependencies
        );

        $composerLockAsArray = json_decode(
            $this->filesystem->read($this->pathToProject . DIRECTORY_SEPARATOR . 'composer.lock'),
            true
        );
        Assert::same(
            $composerLockAsArray,
            $expectedDependencies
        );
    }
}
