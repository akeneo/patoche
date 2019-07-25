<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Tests\Acceptance\Deployment\DependencyManager;

use Akeneo\Domain\Deployment\Dependency;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Tests\Acceptance\Deployment\DependencyManager\FakeComposer;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;

class FakeComposerSpec extends ObjectBehavior
{
    private const COMPOSER_JSON = <<<JSON
{
    "name": "fake/project",
    "authors": [
        {
            "name": "Patoche",
            "email": "patoche@akeneo.com"
        }
    ],
    "require": {
        "php": "7.3.*",
        "fake/lib": "^1.0.0"
    }
}
JSON;

    function let(FilesystemInterface $filesystem)
    {
        $this->beConstructedWith(
            $filesystem,
            'release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FakeComposer::class);
    }

    function it_requires_a_new_dependency($filesystem)
    {
        $filesystem
            ->read('release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3/composer.json')
            ->willReturn(static::COMPOSER_JSON);

        $organization = new Organization('fake');
        $project = new Project('another-lib');
        $branch = new Branch('0.0');
        $commit = Commit::fromBranchesApiResponse(
            [
                'commit' => [
                    'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
                ],
            ]
        );
        $dependency = Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);

        $filesystem->update(
            'release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3/composer.json',
            '{"name":"fake\/project","authors":[{"name":"Patoche","email":"patoche@akeneo.com"}],"require":{"php":"7.3.*","fake\/lib":"^1.0.0","fake\/another-lib":"0.0.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev"}}'
        )->shouldBeCalled();

        $this->require($dependency);
    }

    function it_updates_a_already_present_dependency($filesystem)
    {
        $filesystem
            ->read('release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3/composer.json')
            ->willReturn(static::COMPOSER_JSON);

        $organization = new Organization('fake');
        $project = new Project('lib');
        $branch = new Branch('1.0');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
            ],
        ]);
        $dependency = Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);

        $filesystem->update(
            'release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3/composer.json',
            '{"name":"fake\/project","authors":[{"name":"Patoche","email":"patoche@akeneo.com"}],"require":{"php":"7.3.*","fake\/lib":"1.0.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev"}}'
        )->shouldBeCalled();

        $this->require($dependency);
    }

    function it_locks_dependencies($filesystem)
    {
        $filesystem
            ->read('release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3/composer.json')
            ->willReturn(static::COMPOSER_JSON);

        $filesystem
            ->put('release-v0.0.0/fake-project-7757b6a0ee80313fbbc42c2b7013fa523929c8c3/composer.lock', '{"php":"7.3.*","fake\/lib":"^1.0.0"}')
            ->shouldBeCalled();

        $this->update();
    }
}
