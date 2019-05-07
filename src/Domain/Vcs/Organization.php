<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Vcs;

use Webmozart\Assert\Assert;

final class Organization
{
    private $organizationName;

    public function __construct(string $organizationName)
    {
        Assert::notEmpty($organizationName, 'You must specify a GitHub organization.');

        $this->organizationName = $organizationName;
    }

    public function __toString(): string
    {
        return $this->organizationName;
    }
}
