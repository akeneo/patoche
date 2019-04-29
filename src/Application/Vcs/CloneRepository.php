<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

use Akeneo\Domain\Tagging\WorkingDirectory;
use Akeneo\Domain\Vcs\Repository;

class CloneRepository
{
    private $repository;
    private $workingDirectory;

    public function __construct(Repository $repository, WorkingDirectory $workingDirectory)
    {
        $this->repository = $repository;
        $this->workingDirectory = $workingDirectory;
    }

    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function getWorkingDirectory(): WorkingDirectory
    {
        return $this->workingDirectory;
    }
}
