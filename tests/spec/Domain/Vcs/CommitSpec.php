<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Domain\Vcs;

use Akeneo\Patoche\Domain\Vcs\Commit;
use PhpSpec\ObjectBehavior;

class CommitSpec extends ObjectBehavior
{
    /**
     * This is the response of the GitHub API for branch "0.0" of the "akeneo/patoche" repository.
     */
    private const JSON_BRANCHES_API_RESPONSE = <<<JSON
{
    "name": "0.0",
    "commit": {
        "sha": "eb39d8227797b960796fc1662b24da234c5cda13",
        "node_id": "MDY6Q29tbWl0MTgyMDU5NTIzOmViMzlkODIyNzc5N2I5NjA3OTZmYzE2NjJiMjRkYTIzNGM1Y2RhMTM=",
        "commit": {
            "author": {
                "name": "Damien Carcel",
                "email": "damien.carcel@akeneo.com",
                "date": "2019-05-09T08:00:59Z"
            },
            "committer": {
                "name": "Damien Carcel",
                "email": "damien.carcel@akeneo.com",
                "date": "2019-05-09T08:06:55Z"
            },
            "message": "AOB-331: Branch dedicated to integration tests",
            "tree": {
                "sha": "70ff82f090b9b58df5f709e55f39f7c614d05761",
                "url": "https://api.github.com/repos/akeneo/patoche/git/trees/70ff82f090b9b58df5f709e55f39f7c614d05761"
            },
            "url": "https://api.github.com/repos/akeneo/patoche/git/commits/eb39d8227797b960796fc1662b24da234c5cda13",
            "comment_count": 0,
            "verification": {
                "verified": true,
                "reason": "valid"
            }
        },
        "url": "https://api.github.com/repos/akeneo/patoche/commits/eb39d8227797b960796fc1662b24da234c5cda13",
        "html_url": "https://github.com/akeneo/patoche/commit/eb39d8227797b960796fc1662b24da234c5cda13",
        "comments_url": "https://api.github.com/repos/akeneo/patoche/commits/eb39d8227797b960796fc1662b24da234c5cda13/comments",
        "author": {
            "login": "damien-carcel",
            "id": 5039018,
            "node_id": "MDQ6VXNlcjUwMzkwMTg=",
            "avatar_url": "https://avatars3.githubusercontent.com/u/5039018?v=4",
            "gravatar_id": "",
            "url": "https://api.github.com/users/damien-carcel",
            "html_url": "https://github.com/damien-carcel",
            "followers_url": "https://api.github.com/users/damien-carcel/followers",
            "following_url": "https://api.github.com/users/damien-carcel/following{/other_user}",
            "gists_url": "https://api.github.com/users/damien-carcel/gists{/gist_id}",
            "starred_url": "https://api.github.com/users/damien-carcel/starred{/owner}{/repo}",
            "subscriptions_url": "https://api.github.com/users/damien-carcel/subscriptions",
            "organizations_url": "https://api.github.com/users/damien-carcel/orgs",
            "repos_url": "https://api.github.com/users/damien-carcel/repos",
            "events_url": "https://api.github.com/users/damien-carcel/events{/privacy}",
            "received_events_url": "https://api.github.com/users/damien-carcel/received_events",
            "type": "User",
            "site_admin": false
        },
        "committer": {
            "login": "damien-carcel",
            "id": 5039018,
            "node_id": "MDQ6VXNlcjUwMzkwMTg=",
            "avatar_url": "https://avatars3.githubusercontent.com/u/5039018?v=4",
            "gravatar_id": "",
            "url": "https://api.github.com/users/damien-carcel",
            "html_url": "https://github.com/damien-carcel",
            "followers_url": "https://api.github.com/users/damien-carcel/followers",
            "following_url": "https://api.github.com/users/damien-carcel/following{/other_user}",
            "gists_url": "https://api.github.com/users/damien-carcel/gists{/gist_id}",
            "starred_url": "https://api.github.com/users/damien-carcel/starred{/owner}{/repo}",
            "subscriptions_url": "https://api.github.com/users/damien-carcel/subscriptions",
            "organizations_url": "https://api.github.com/users/damien-carcel/orgs",
            "repos_url": "https://api.github.com/users/damien-carcel/repos",
            "events_url": "https://api.github.com/users/damien-carcel/events{/privacy}",
            "received_events_url": "https://api.github.com/users/damien-carcel/received_events",
            "type": "User",
            "site_admin": false
        },
        "parents": []
    },
    "_links": {
        "self": "https://api.github.com/repos/akeneo/patoche/branches/0.0",
        "html": "https://github.com/akeneo/patoche/tree/0.0"
    },
    "protected": true,
    "protection": {
        "enabled": true,
        "required_status_checks": {
            "enforcement_level": "everyone",
            "contexts": []
        }
    },
    "protection_url": "https://api.github.com/repos/akeneo/patoche/branches/0.0/protection"
}
JSON;

    function let()
    {
        $this->beConstructedThrough('fromBranchesApiResponse', [
            json_decode(static::JSON_BRANCHES_API_RESPONSE, true),
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Commit::class);
    }

    function it_returns_the_commit_sha()
    {
        $this->__toString()->shouldReturn('eb39d8227797b960796fc1662b24da234c5cda13');
    }

    function it_throws_an_exception_if_the_commit_sha_is_empty()
    {
        $decodedApiResponse = json_decode(static::JSON_BRANCHES_API_RESPONSE, true);
        $decodedApiResponse['commit']['sha'] = '';

        $this->beConstructedThrough('fromBranchesApiResponse', [$decodedApiResponse]);

        $this
            ->shouldThrow(new \InvalidArgumentException('A commit SHA cannot be empty.'))
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_there_is_no_commit_sha()
    {
        $decodedApiResponse = json_decode(static::JSON_BRANCHES_API_RESPONSE, true);
        unset($decodedApiResponse['commit']['sha']);

        $this->beConstructedThrough('fromBranchesApiResponse', [$decodedApiResponse]);

        $this
            ->shouldThrow(new \InvalidArgumentException('A branch commit must have a SHA.'))
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_there_is_no_commit_in_the_branches_api_response()
    {
        $decodedApiResponse = json_decode(static::JSON_BRANCHES_API_RESPONSE, true);
        unset($decodedApiResponse['commit']);

        $this->beConstructedThrough('fromBranchesApiResponse', [$decodedApiResponse]);

        $this
            ->shouldThrow(new \InvalidArgumentException('The branch doesn\'t have a last commit.'))
            ->duringInstantiation();
    }
}
