<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Akeneo\Application\Vcs\DownloadArchive;
use Akeneo\Application\Vcs\DownloadArchiveHandler;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Behat\Behat\Context\Context;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

final class DownloadArchiveContext implements Context
{
    private const EXPECTED_DATA = [
        'akeneo' => [
            'onboarder' => [
                '2.2' => 'Cloning akeneo/onboarder 2.2',
            ],
        ],
    ];

    private $downloadArchiveHandler;
    private $filesystem;

    public function __construct(DownloadArchiveHandler $downloadArchiveHandler, FilesystemInterface $filesystem)
    {
        $this->downloadArchiveHandler = $downloadArchiveHandler;
        $this->filesystem = $filesystem;
    }

    /**
     * @When I download the :projectName archive
     */
    public function downloadArchive(string $projectName): void
    {
        $repository = new Repository(
            CommonContext::$onboarderRelease->getOrganization(),
            new Project($projectName),
            CommonContext::$onboarderRelease->getBranch()
        );

        ($this->downloadArchiveHandler)(new DownloadArchive(
            $repository,
            CommonContext::$onboarderRelease->getWorkingDirectory()
        ));
    }

    /**
     * @Then the :projectName project is available locally
     */
    public function projectAvailableLocally(string $projectName): void
    {
        $readContent = $this->filesystem->read(sprintf(
            '%s/%s/README.md',
            CommonContext::$onboarderRelease->getWorkingDirectory(),
            $projectName
        ));

        $organization = (string) CommonContext::$onboarderRelease->getOrganization();
        $branch = (string) CommonContext::$onboarderRelease->getBranch();
        $expectedContent = static::EXPECTED_DATA[$organization][$projectName][$branch];

        Assert::same($readContent, $expectedContent);
    }
}
