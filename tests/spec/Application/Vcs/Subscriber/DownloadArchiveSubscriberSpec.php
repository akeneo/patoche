<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Vcs\Subscriber;

use Akeneo\Patoche\Application\Onboarder\MappedBranches;
use Akeneo\Patoche\Application\Onboarder\OnboarderRelease;
use Akeneo\Patoche\Application\Vcs\DownloadArchive;
use Akeneo\Patoche\Application\Vcs\DownloadArchiveHandler;
use Akeneo\Patoche\Application\Vcs\Subscriber\DownloadArchiveSubscriber;
use Akeneo\Patoche\Domain\Common\Tag;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

class DownloadArchiveSubscriberSpec extends ObjectBehavior
{
    function let(DownloadArchiveHandler $downloadArchiveHandler)
    {
        $this->beConstructedWith($downloadArchiveHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DownloadArchiveSubscriber::class);
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_download_archive_transitions()
    {
        $this->getSubscribedEvents()->shouldReturn([
            'workflow.onboarder_release.transition.download_pim_enterprise_cloud_archive' => 'downloadPec',
        ]);
    }

    function it_download_the_pim_enterprise_cloud_2x_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('pim-enterprise-cloud');
        $branch = new Branch('1.2');
        $pecBranch = new Branch('2.3');
        $workingDirectory = new WorkingDirectory('release-v1.2.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $pecBranch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new OnboarderRelease(
                $branch,
                Tag::fromGenericTag('1.2.0'),
                $organization,
                MappedBranches::fromRawMapping([
                    '1.2' => '2.3',
                    '2.0' => '3.0',
                ])
            ),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadPec($event);
    }

    function it_download_the_pim_enterprise_cloud_3x_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('pim-enterprise-cloud');
        $branch = new Branch('2.0');
        $pecBranch = new Branch('3.0');
        $workingDirectory = new WorkingDirectory('release-v2.0.1');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $pecBranch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new OnboarderRelease(
                $branch,
                Tag::fromGenericTag('2.0.1'),
                $organization,
                MappedBranches::fromRawMapping([
                    '1.2' => '2.3',
                    '2.0' => '3.0',
                ])
            ),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadPec($event);
    }

    function it_throws_an_exception_if_the_event_subject_is_not_an_onboarder_release()
    {
        $event = new Event(
            new \stdClass(),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );

        $exception = new \InvalidArgumentException(
            'Event subject should be an instance of "OnboarderRelease", "stdClass" provided.'
        );
        $this->shouldThrow($exception)->during('downloadPec', [$event]);
    }
}
