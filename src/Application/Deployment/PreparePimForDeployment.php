<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Deployment;

use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Repository;

final class PreparePimForDeployment
{
    private $repository;
    private $pecBranch;
    private $workingDirectory;

    public function __construct(
        Repository $dependencyRepository,
        Branch $pecBranch,
        WorkingDirectory $workingDirectory
    ) {
        $this->repository = $dependencyRepository;
        $this->pecBranch = $pecBranch;
        $this->workingDirectory = $workingDirectory;
    }

    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function getPecBranch(): Branch
    {
        return $this->pecBranch;
    }

    public function getWorkingDirectory(): WorkingDirectory
    {
        return $this->workingDirectory;
    }
}
