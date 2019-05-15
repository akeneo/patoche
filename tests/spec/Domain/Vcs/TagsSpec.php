<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs;

use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Tags;
use PhpSpec\ObjectBehavior;

class TagsSpec extends ObjectBehavior
{
    /**
     * This is the real repsonse of the GitHub API for the "akeneo/onboarder" repository, as of May 13, 2019.
     */
    private const JSON_LIST_TAGS_API_RESPONSE = <<<JSON
[
  {
    "name": "v1.2.1",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.2.1",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.2.1",
    "commit": {
      "sha": "5f997b2e9c5735d4e24a3fd18c3ffc56e7914172",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/5f997b2e9c5735d4e24a3fd18c3ffc56e7914172"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjIuMQ=="
  },
  {
    "name": "v1.2.0",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.2.0",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.2.0",
    "commit": {
      "sha": "03a306db8df9de440aa3be4d91e57b9447cd0345",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/03a306db8df9de440aa3be4d91e57b9447cd0345"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjIuMA=="
  },
  {
    "name": "v1.1.2",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.1.2",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.1.2",
    "commit": {
      "sha": "e985b72d7ee16bbea4edd9d693e836bab8caefb4",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/e985b72d7ee16bbea4edd9d693e836bab8caefb4"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjEuMg=="
  },
  {
    "name": "v1.1.1",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.1.1",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.1.1",
    "commit": {
      "sha": "6e89167246124c62d5446ea866a56fab2a7e71c9",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/6e89167246124c62d5446ea866a56fab2a7e71c9"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjEuMQ=="
  },
  {
    "name": "v1.1.0",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.1.0",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.1.0",
    "commit": {
      "sha": "219fbdaebecc81d9e780e05c92483b5c5e305e62",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/219fbdaebecc81d9e780e05c92483b5c5e305e62"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjEuMA=="
  },
  {
    "name": "v1.0.0",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.0.0",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.0.0",
    "commit": {
      "sha": "ae13bfcaf1a0d5f226484f032a2bd0579a215d17",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/ae13bfcaf1a0d5f226484f032a2bd0579a215d17"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjAuMA=="
  },
  {
    "name": "v1.0.0-BETA4",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.0.0-BETA4",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.0.0-BETA4",
    "commit": {
      "sha": "7d7913ec2f732a6330d7e322595208cf03850c58",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/7d7913ec2f732a6330d7e322595208cf03850c58"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBNA=="
  },
  {
    "name": "v1.0.0-BETA3",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.0.0-BETA3",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.0.0-BETA3",
    "commit": {
      "sha": "05d8580ac607ab76f3639a23c0456a57377e834a",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/05d8580ac607ab76f3639a23c0456a57377e834a"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBMw=="
  },
  {
    "name": "v1.0.0-BETA2",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.0.0-BETA2",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.0.0-BETA2",
    "commit": {
      "sha": "a0bf3cfedbf4bc38545f615e05a5844ad89d3977",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/a0bf3cfedbf4bc38545f615e05a5844ad89d3977"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBMg=="
  },
  {
    "name": "v1.0.0-BETA1",
    "zipball_url": "https://api.github.com/repos/akeneo/onboarder/zipball/v1.0.0-BETA1",
    "tarball_url": "https://api.github.com/repos/akeneo/onboarder/tarball/v1.0.0-BETA1",
    "commit": {
      "sha": "a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29",
      "url": "https://api.github.com/repos/akeneo/onboarder/commits/a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29"
    },
    "node_id": "MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBMQ=="
  }
]
JSON;

    function it_is_initializable()
    {
        $this->beConstructedThrough('fromListTagsApiResponse', [
            json_decode(static::JSON_LIST_TAGS_API_RESPONSE, true),
        ]);

        $this->shouldHaveType(Tags::class);
    }

    function it_is_initializable_through_an_empty_api_response()
    {
        $this->beConstructedThrough('fromListTagsApiResponse', [[]]);

        $this->shouldHaveType(Tags::class);
    }

    function it_returns_the_next_tag_for_a_given_branch()
    {
        $this->beConstructedThrough('fromListTagsApiResponse', [
            json_decode(static::JSON_LIST_TAGS_API_RESPONSE, true),
        ]);

        $tag = $this->nextTagForBranch(new Branch('1.2'));

        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getVcsTag()->shouldReturn('v1.2.2');
    }

    function it_returns_the_next_tag_for_multi_digit_versions()
    {
        $this->beConstructedThrough('fromListTagsApiResponse', [[
            ['name' => 'v10.10.42'],
        ]]);

        $tag = $this->nextTagForBranch(new Branch('10.10'));

        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getVcsTag()->shouldReturn('v10.10.43');
    }

    function it_uses_natural_sorting_to_find_the_next_tag()
    {
        $this->beConstructedThrough('fromListTagsApiResponse', [[
            ['name' => 'v1.1.0'],
            ['name' => 'v1.1.1'],
            ['name' => 'v1.1.10'],
            ['name' => 'v1.1.2'],
        ]]);

        $tag = $this->nextTagForBranch(new Branch('1.1'));

        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getVcsTag()->shouldReturn('v1.1.11');
    }

    function it_returns_the_first_tag_of_a_new_branch()
    {
        $this->beConstructedThrough('fromListTagsApiResponse', [
            json_decode(static::JSON_LIST_TAGS_API_RESPONSE, true),
        ]);

        $tag = $this->nextTagForBranch(new Branch('1.3'));

        $tag->shouldBeAnInstanceOf(Tag::class);
        $tag->getVcsTag()->shouldReturn('v1.3.0');
    }
}
