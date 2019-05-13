<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestCase extends KernelTestCase
{
    public function setUp(): void
    {
        static::$kernel = static::bootKernel(['debug' => false, 'environment' => 'integration']);

        $filesystem = $this->container()->get('League\Flysystem\Filesystem');

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
}
