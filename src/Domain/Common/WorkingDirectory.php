<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Domain\Common;

use Webmozart\Assert\Assert;

final class WorkingDirectory
{
    private $directoryName;

    public function __construct(string $directoryName)
    {
        Assert::notEmpty($directoryName, 'You must specify a working directory.');
        Assert::regex($directoryName, '/^release-v\d+.\d+.\d+$/', sprintf(
            'The working directory must be named using the tag to release, "%s" provided.',
            $directoryName
        ));

        $this->directoryName = $directoryName;
    }

    public function __toString(): string
    {
        return $this->directoryName;
    }
}
