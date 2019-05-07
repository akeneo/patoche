<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Tagging;

use Webmozart\Assert\Assert;

final class WorkingDirectory
{
    private $directoryName;

    public function __construct(string $directoryName)
    {
        Assert::notEmpty($directoryName, 'You must specify a working directory.');

        $this->directoryName = $directoryName;
    }

    public function __toString(): string
    {
        return $this->directoryName;
    }
}
