<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Domain\Common;

use Webmozart\Assert\Assert;

final class Tag
{
    private $genericTag;

    private function __construct(string $genericTag)
    {
        Assert::notEmpty($genericTag, 'You must specify a tag for the release.');
        Assert::regex($genericTag, '/^\d+.\d+.\d+(-\d{2}|$)$/', sprintf(
            'The tag must respect Semantic Versioning with, optionally, two digits metadata (6.6.6-01), "%s" provided.',
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
        list($major, $minor, $patchAndMetadata) = explode('.', $this->genericTag);

        $nextTag = sprintf(
            '%s.%s.%s',
            $major,
            $minor,
            $this->incrementPatchAndMetadata($patchAndMetadata)
        );

        return new self($nextTag);
    }

    public function getVcsBranchName(): string
    {
        preg_match('/\d+.\d+/', $this->getVcsTag(), $matches);

        return $matches[0];
    }

    private function incrementPatchAndMetadata(string $patchAndMetadata): string
    {
        if (false === strpos($patchAndMetadata, '-')) {
            $incrementedPatch = (int) $patchAndMetadata + 1;

            return (string) $incrementedPatch;
        }

        list($patch, $metadata) = explode('-', $patchAndMetadata);
        $incrementedMetadata = (int) $metadata + 1;

        return sprintf(
            '%s-%s',
            $patch,
            str_pad((string) $incrementedMetadata, 2, '0', STR_PAD_LEFT)
        );
    }
}
