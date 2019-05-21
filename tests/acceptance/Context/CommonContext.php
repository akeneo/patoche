<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Akeneo\Application\Onboarder\MappedBranches;
use Akeneo\Application\Onboarder\OnboarderRelease;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Behat\Behat\Context\Context;

final class CommonContext implements Context
{
    private $mappedBranches;

    /** @var OnboarderRelease */
    public static $onboarderRelease;

    public function __construct(array $mappedBranches)
    {
        $this->mappedBranches = $mappedBranches;
    }

    /**
     * @Given a new version of the Onboarder is going to be released
     */
    public function newOnboarderRelease(): void
    {
        static::$onboarderRelease = new OnboarderRelease(
            new Branch('4.2'),
            Tag::fromGenericTag('4.2.0'),
            new Organization('akeneo'),
            MappedBranches::fromRawMapping($this->mappedBranches)
        );
    }
}
