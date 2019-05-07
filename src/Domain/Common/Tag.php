<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Domain\Common;

use Webmozart\Assert\Assert;

final class Tag
{
    private $tag;

    public function __construct(string $tag)
    {
        Assert::notEmpty($tag, 'You must specify a tag for the release.');
        Assert::regex($tag, '/^\d+.\d+.\d+$/', sprintf(
            'The tag must correspond to a patch version (i.e. "4.2.1", "10.0.0"), "%s" provided.',
            $tag
        ));

        $this->tag = $tag;
    }

    public function getVcsTag(): string
    {
        return sprintf('v%s', $this->tag);
    }

    public function getDockerTag(): string
    {
        return $this->tag;
    }
}
