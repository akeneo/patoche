<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Tagging;

class TaggingProcess
{
    private $branch;
    private $tag;
    private $organization;
    private $workingDirectory;
    private $places;

    public function __construct(string $branch, string $tag, string $organization)
    {
        $this->branch = $branch;
        $this->tag = $tag;
        $this->organization = $organization;

        $this->workingDirectory = uniqid(sprintf('release-%s-', $tag));

        $this->places = [];
    }

    public function getBranch(): string
    {
        return $this->branch;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getOrganization(): string
    {
        return $this->organization;
    }

    public function getWorkingDirectory(): string
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
