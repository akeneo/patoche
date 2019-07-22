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
use PhpSpec\ObjectBehavior;

class FakeComposerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            <<<JSON
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
JSON
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FakeComposer::class);
    }

    function it_requires_a_new_dependency()
    {
        $organization = new Organization('fake');
        $project = new Project('another-lib');
        $branch = new Branch('0.0');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
            ],
        ]);
        $dependency = Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);

        $this->require($dependency);

        $this->shouldHaveDependenciesRequiredCount(3);
        $this->shouldHaveRequiredDependency(
            'fake/another-lib',
            '0.0.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev'
        );
        $this->shouldHaveLockedNothing();
    }

    function it_updates_a_already_present_dependency()
    {
        $organization = new Organization('fake');
        $project = new Project('lib');
        $branch = new Branch('1.0');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
            ],
        ]);
        $dependency = Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);

        $this->require($dependency);

        $this->shouldHaveDependenciesRequiredCount(2);
        $this->shouldHaveRequiredDependency(
            'fake/lib',
            '1.0.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev'
        );
        $this->shouldHaveLockedNothing();
    }

    function it_locks_dependencies()
    {
        $this->update();

        $this->shouldHaveLocked([
            'php' => '7.3.*',
            'fake/lib' => '^1.0.0',
        ]);
    }

    function it_updates_locked_dependencies()
    {
        $this->update();

        $organization = new Organization('fake');
        $project = new Project('lib');
        $branch = new Branch('1.0');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
            ],
        ]);
        $dependency = Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);

        $this->require($dependency);
        $this->update();

        $this->shouldHaveLocked([
            'php' => '7.3.*',
            'fake/lib' => '1.0.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev',
        ]);
    }

    function it_adds_locked_dependencies()
    {
        $organization = new Organization('fake');
        $project = new Project('another-lib');
        $branch = new Branch('1.0');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
            ],
        ]);
        $dependency = Dependency::fromBranchNameAndCommitReference($organization, $project, $branch, $commit);

        $this->require($dependency);
        $this->update();

        $this->shouldHaveLocked([
            'php' => '7.3.*',
            'fake/lib' => '^1.0.0',
            'fake/another-lib' => '1.0.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev',
        ]);
    }

    public function getMatchers(): array
    {
        return [
            'haveDependenciesRequiredCount' => function (FakeComposer $fakeComposer, int $count) {
                $decodedContent = json_decode($fakeComposer->getComposerJson(), true);

                return count($decodedContent['require']) === $count;
            },
            'haveRequiredDependency' => function (
                FakeComposer $fakeComposer,
                string $dependencyName,
                string $dependencyVersion
            ) {
                $decodedContent = json_decode($fakeComposer->getComposerJson(), true);

                return isset($decodedContent['require'][$dependencyName])
                    && $decodedContent['require'][$dependencyName] === $dependencyVersion;
            },
            'haveLockedNothing' => function (FakeComposer $fakeComposer) {
                return $fakeComposer->getComposerLock() === [];
            },
            'haveLocked' => function (FakeComposer $fakeComposer, array $lockedDependencies) {
                return $fakeComposer->getComposerLock() === $lockedDependencies;
            },
        ];
    }
}
