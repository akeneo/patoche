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

    public function __construct(string $organization, string $project, string $branch)
    {
        $this->organization = $organization;
        $this->project = $project;
        $this->branch = $branch;
    }

    public function getName(): string
    {
        return sprintf('%s/%s', $this->organization, $this->project);
    }

    public function getBranch(): string
    {
        return $this->branch;
    }
}
