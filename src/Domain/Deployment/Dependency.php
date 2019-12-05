<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Domain\Deployment;

use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Commit;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;

final class Dependency
{
    private $dependencyName;
    private $reference;

    private function __construct(string $dependencyName, string $reference)
    {
        $this->dependencyName = $dependencyName;
        $this->reference = $reference;
    }

    public static function fromBranchNameAndCommitReference(
        Organization $organization,
        Project $project,
        Branch $branch,
        Commit $commit
    ): self {
        $dependencyName = sprintf('%s/%s', $organization, $project);
        $reference = 'master' === (string) $branch
            ? sprintf('dev-%s#%s@dev', $branch, $commit)
            : sprintf('%s.x-dev#%s@dev', $branch, $commit);

        return new self($dependencyName, $reference);
    }

    public function __toString(): string
    {
        return sprintf('%s:%s', $this->dependencyName, $this->reference);
    }
}
