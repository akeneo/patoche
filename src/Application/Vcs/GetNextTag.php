<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Application\Vcs;

use Akeneo\Patoche\Domain\Vcs\Repository;

final class GetNextTag
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository(): Repository
    {
        return $this->repository;
    }
}
