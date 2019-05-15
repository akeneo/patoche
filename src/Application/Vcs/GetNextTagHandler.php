<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

use Akeneo\Domain\Common\Tag;

final class GetNextTagHandler
{
    private $vcsApiClient;

    public function __construct(VcsApiClient $vcsApiClient)
    {
        $this->vcsApiClient = $vcsApiClient;
    }

    public function __invoke(GetNextTag $getNextTag): Tag
    {
        $tagList = $this->vcsApiClient->listTags(
            $getNextTag->getRepository()->getOrganization(),
            $getNextTag->getRepository()->getProject()
        );

        return $tagList->nextTagForBranch($getNextTag->getRepository()->getBranch());
    }
}
