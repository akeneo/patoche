<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application;

use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;

final class ReleaseProcess
{
    private const PIM_ENTERPRISE_CLOUD_2X_BRANCH = '2.3';
    private const PIM_ENTERPRISE_CLOUD_3X_BRANCH = '3.1';

    private $branch;
    private $tag;
    private $organization;
    private $workingDirectory;
    private $places;

    public function __construct(Branch $branch, Tag $tag, Organization $organization)
    {
        $this->branch = $branch;
        $this->tag = $tag;
        $this->organization = $organization;

        $this->workingDirectory = new WorkingDirectory(sprintf(
            'release-%s',
            $tag->getVcsTag()
        ));

        $this->places = [];
    }

    public function getBranch(): Branch
    {
        return $this->branch;
    }

    public function getPecBranch(): Branch
    {
        list($major) = explode('.', (string) $this->branch);
        if ('1' === $major) {
            return new Branch(static::PIM_ENTERPRISE_CLOUD_2X_BRANCH);
        }

        return new Branch(static::PIM_ENTERPRISE_CLOUD_3X_BRANCH);
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getWorkingDirectory(): WorkingDirectory
    {
        return $this->workingDirectory;
    }

    public function getPlaces(): array
    {
        return $this->places;
    }

    public function setPlaces(array $places): void
    {
        $this->places = $places;
    }
}
