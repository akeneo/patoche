<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Vcs\Subscriber;

use Akeneo\Application\ReleaseProcess;
use Akeneo\Application\Vcs\DownloadArchive;
use Akeneo\Application\Vcs\DownloadArchiveHandler;
use Akeneo\Application\Vcs\Subscriber\DownloadArchiveSubscriber;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
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
            'workflow.onboarder_release.transition.download_middleware_archive' => 'downloadMiddleware',
            'workflow.onboarder_release.transition.download_overseer_archive' => 'downloadOverseer',
            'workflow.onboarder_release.transition.download_supplier_onboarder_archive' => 'downloadSupplierOnboarder',
            'workflow.onboarder_release.transition.download_pim_onboarder_bundle_archive' => 'downloadPimOnboarder',
        ]);
    }

    function it_download_the_pim_enterprise_cloud_2x_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('pim-enterprise-cloud');
        $branch = new Branch('1.2');
        $pecBranch = new Branch('2.3');
        $workingDirectory = new WorkingDirectory('release-v1.1.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $pecBranch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new ReleaseProcess($branch, Tag::fromGenericTag('1.1.0'), $organization),
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
            new ReleaseProcess($branch, Tag::fromGenericTag('2.0.1'), $organization),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadPec($event);
    }

    function it_download_the_middleware_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder-middleware');
        $branch = new Branch('1.1');
        $workingDirectory = new WorkingDirectory('release-v1.1.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new ReleaseProcess($branch, Tag::fromGenericTag('1.1.0'), $organization),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadMiddleware($event);
    }

    function it_download_the_overseer_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder-supplier-service');
        $branch = new Branch('1.1');
        $workingDirectory = new WorkingDirectory('release-v1.1.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new ReleaseProcess($branch, Tag::fromGenericTag('1.1.0'), $organization),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadOverseer($event);
    }

    function it_download_the_supplier_onboarder_bundle_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder');
        $branch = new Branch('1.1');
        $workingDirectory = new WorkingDirectory('release-v1.1.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new ReleaseProcess($branch, Tag::fromGenericTag('1.1.0'), $organization),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadSupplierOnboarder($event);
    }

    function it_download_the_pim_onboarder_bundle_archive($downloadArchiveHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('pim-onboarder');
        $branch = new Branch('1.1');
        $workingDirectory = new WorkingDirectory('release-v1.1.0');

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $workingDirectory
        );

        $downloadArchiveHandler->__invoke(Argument::exact($downloadArchive))->shouldBeCalled();

        $event = new Event(
            new ReleaseProcess($branch, Tag::fromGenericTag('1.1.0'), $organization),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->downloadPimOnboarder($event);
    }
}
