<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Vcs;

class Repository
{
    private $organization;
    private $project;
    private $branch;

    public function __construct(Organization $organization, Project $project, Branch $branch)
    {
        $this->organization = $organization;
        $this->project = $project;
        $this->branch = $branch;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getBranch(): Branch
    {
        return $this->branch;
    }
}
