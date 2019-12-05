<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Domain\Vcs;

use Akeneo\Patoche\Domain\Common\Tag;

final class Tags
{
    private $tags;

    private function __construct(array $tags)
    {
        $this->tags = $tags;
    }

    public static function fromListTagsApiResponse(array $listTagsApiResponse): self
    {
        $tags = [];

        foreach ($listTagsApiResponse as $tagResource) {
            $vcsTag = $tagResource['name'];
            if (false === strpos($vcsTag, 'ALPHA') && false === strpos($vcsTag, 'BETA')) {
                $tags[$vcsTag] = Tag::fromVcsTag($vcsTag);
            }
        }

        return new self($tags);
    }

    public function nextTagForBranch(Branch $branch): Tag
    {
        $branchTags = array_filter(
            $this->tags,
            function (Tag $tag) use ($branch) {
                return ((string) $branch) === $tag->getVcsBranchName();
            }
        );

        if (empty($branchTags)) {
            return Tag::fromGenericTag(sprintf('%s.0', $branch));
        }

        ksort($branchTags, SORT_NATURAL);

        return (end($branchTags))->nextTag();
    }
}
