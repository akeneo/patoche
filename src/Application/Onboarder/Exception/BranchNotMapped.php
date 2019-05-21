<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Onboarder\Exception;

use Akeneo\Domain\Vcs\Branch;

final class BranchNotMapped extends \RuntimeException
{
    public function __construct(Branch $branch)
    {
        parent::__construct(sprintf(
            'Onboarder branch "%s" is not mapped to any PIM Enterprise Cloud branch.',
            $branch
        ));
    }
}
