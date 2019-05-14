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
    private $genericTag;

    private function __construct(string $genericTag)
    {
        Assert::notEmpty($genericTag, 'You must specify a tag for the release.');
        Assert::regex($genericTag, '/^\d+.\d+.\d+$/', sprintf(
            'The tag must correspond to a patch version (i.e. "4.2.1", "10.0.0"), "%s" provided.',
            $genericTag
        ));

        $this->genericTag = $genericTag;
    }

    public static function fromGenericTag(string $genericTag): self
    {
        return new self($genericTag);
    }

    public static function fromVcsTag(string $vcsTag): self
    {
        $genericTagName = substr($vcsTag, 1);

        return new self($genericTagName);
    }

    public function getVcsTag(): string
    {
        return sprintf('v%s', $this->genericTag);
    }

    public function getDockerTag(): string
    {
        return $this->genericTag;
    }

    public function nextTag(): self
    {
        list($major, $minor, $patch) = explode('.', $this->genericTag);
        $nextTag = implode('.', [$major, $minor, ((int) $patch) + 1]);

        return new self($nextTag);
    }

    public function getVcsBranchName(): string
    {
        preg_match('/\d+.\d+/', $this->getVcsTag(), $matches);

        return $matches[0];
    }
}
