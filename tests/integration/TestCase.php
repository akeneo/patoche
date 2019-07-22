<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Integration;

use League\Flysystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestCase extends KernelTestCase
{
    public const TESTED_WORKING_DIRECTORY = 'release-v0.0.2';

    public function setUp(): void
    {
        static::$kernel = static::bootKernel(['debug' => false, 'environment' => 'integration']);

        $filesystem = $this->container()->get(Filesystem::class);

        $rootDirectoryContents = $filesystem->listContents();
        foreach ($rootDirectoryContents as $content) {
            if ('.gitkeep' !== $content['path']) {
                'dir' === $content['type']
                    ? $filesystem->deleteDir($content['path'])
                    : $filesystem->delete($content['path']);
            }
        }
    }

    /**
     * Using this special test container allows to get private services,
     * but only if they are already injected somewhere in the application.
     */
    protected function container(): ContainerInterface
    {
        return static::$container;
    }

    protected function pathToDownloadedPatoche(): string
    {
        $filesystem = $this->container()->get(Filesystem::class);
        $downloadedContents = $filesystem->listContents(static::TESTED_WORKING_DIRECTORY);

        $downloadedRepositoryPath = '';
        foreach ($downloadedContents as $content) {
            if ('dir' === $content['type'] && 1 === preg_match('/^akeneo-patoche-.*/', $content['basename'])) {
                $downloadedRepositoryPath = $content['path'];
            }
        }

        return $downloadedRepositoryPath;
    }
}
