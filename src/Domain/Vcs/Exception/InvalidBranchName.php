<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Vcs\Exception;

final class InvalidBranchName extends \InvalidArgumentException
{
    public function __construct(string $branchName)
    {
        $message = sprintf(
            'The branch name must correspond to a minor version (i.e. "4.2", "10.0"), "%s" provided.',
            $branchName
        );

        parent::__construct($message);
    }
}
