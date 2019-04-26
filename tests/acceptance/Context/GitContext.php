<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Akeneo\Domain\Tagging\TaggingProcess;
use Behat\Behat\Context\Context;

class GitContext implements Context
{
    /** @var TaggingProcess */
    private $taggingProcess;

    /**
     * @Given a new version of the Onboarder is going to be released
     */
    public function newOnboarderRelease(): void
    {
        $this->taggingProcess = new TaggingProcess('1.0', '1.1.1', 'akeneo');
    }

    /**
     * @When I clone the :projectName git repository
     */
    public function cloneProject(string $projectName): void
    {
        throw new \LogicException('Not implemented step!');
    }

    /**
     * @Then the :projectName project is available locally
     */
    public function projectAvailableLocally(string $projectName): void
    {
        throw new \LogicException('Not implemented step!');
    }
}
