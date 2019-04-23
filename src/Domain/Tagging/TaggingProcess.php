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
    private $states;

    public function __construct(array $states)
    {
        $this->states = $states;
    }

    public function setStates(array $states): void
    {
        $this->states = $states;
    }

    public function getStates(): array
    {
        return $this->states;
    }
}
