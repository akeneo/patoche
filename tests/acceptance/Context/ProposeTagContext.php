<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Context;

use Akeneo\Application\Vcs\GetNextTag;
use Akeneo\Application\Vcs\GetNextTagHandler;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Behat\Behat\Context\Context;
use Webmozart\Assert\Assert;

final class ProposeTagContext implements Context
{
    /** @var Tag */
    private $nextTag;

    private $getNextTagHandler;

    public function __construct(GetNextTagHandler $getNextTagHandler)
    {
        $this->getNextTagHandler = $getNextTagHandler;
    }

    /**
     * @When I want to tag an already tagged branch
     */
    public function iWantToTagAnAlreadyTaggedBranch(): void
    {
        $repository = new Repository(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('2.2')
        );
        $this->nextTag = ($this->getNextTagHandler)(new GetNextTag($repository));
    }

    /**
     * @When I want to tag a new minor branch
     */
    public function iWantToTagANewMinorBranch(): void
    {
        $repository = new Repository(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('4.3')
        );
        $this->nextTag = ($this->getNextTagHandler)(new GetNextTag($repository));
    }

    /**
     * @When I want to tag an new major branch
     */
    public function iWantToTagAnNewMajorBranch(): void
    {
        $repository = new Repository(
            new Organization('akeneo'),
            new Project('onboarder'),
            new Branch('5.0')
        );
        $this->nextTag = ($this->getNextTagHandler)(new GetNextTag($repository));
    }

    /**
     * @Then then a new patch tag is proposed
     */
    public function thenANewPatchTagIsProposed(): void
    {
        Assert::same($this->nextTag->getVcsTag(), 'v2.2.1');
    }

    /**
     * @Then then a new minor tag is proposed
     */
    public function thenANewMinorTagIsProposed(): void
    {
        Assert::same($this->nextTag->getVcsTag(), 'v4.3.0');
    }

    /**
     * @Then then a new major tag is proposed
     */
    public function thenANewMajorTagIsProposed(): void
    {
        Assert::same($this->nextTag->getVcsTag(), 'v5.0.0');
    }
}
