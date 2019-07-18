<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class PrepareForDeploymentContext implements Context
{
    /**
     * @When I want to test the PIM Onboarder bundle to be released
     */
    public function testPimOnboarderBundle(): void
    {
        throw new PendingException();
    }

    /**
     * @Then the PIM Enterprise Cloud dependencies are updated accordingly
     */
    public function pecDependenciesUpdated(): void
    {
        throw new PendingException();
    }
}
