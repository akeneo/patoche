<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Deployment\Subscriber;

use Akeneo\Patoche\Application\Deployment\PreparePimForDeployment;
use Akeneo\Patoche\Application\Deployment\PreparePimForDeploymentHandler;
use Akeneo\Patoche\Application\Deployment\Subscriber\PreparePimForDeploymentSubscriber;
use Akeneo\Patoche\Application\Onboarder\MappedBranches;
use Akeneo\Patoche\Application\Onboarder\OnboarderRelease;
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

class PreparePimForDeploymentSubscriberSpec extends ObjectBehavior
{
    function let(PreparePimForDeploymentHandler $preparePimForDeploymentHandler)
    {
        $this->beConstructedWith($preparePimForDeploymentHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PreparePimForDeploymentSubscriber::class);
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_prepare_pim_enterprise_cloud_for_dev_transitions()
    {
        $this->getSubscribedEvents()->shouldReturn([
            'workflow.onboarder_release.transition.prepare_pim_enterprise_cloud_for_dev' => 'preparePecForDev',
        ]);
    }

    function it_prepares_pim_enterprise_cloud_for_dev($preparePimForDeploymentHandler)
    {
        $organization = new Organization('akeneo');
        $project = new Project('pim-onboarder');
        $branch = new Branch('2.2');
        $workingDirectory = new WorkingDirectory('release-v2.2.1');

        $preparePimForDeployment = new PreparePimForDeployment(
            new Repository($organization, $project, $branch),
            new Branch('3.0'),
            $workingDirectory
        );

        $preparePimForDeploymentHandler->__invoke(Argument::exact($preparePimForDeployment))->shouldBeCalled();

        $event = new Event(
            new OnboarderRelease(
                $branch,
                Tag::fromGenericTag('2.2.1'),
                $organization,
                MappedBranches::fromRawMapping([
                    '1.2' => '2.3',
                    '2.0' => '3.0',
                    '2.1' => '3.0',
                    '2.2' => '3.0',
                ])
            ),
            new Marking(['original_place' => 1]),
            new Transition('transition_name', 'original_place', 'destination_place'),
            'onboarder_release'
        );
        $this->preparePecForDev($event);
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
        $this->shouldThrow($exception)->during('preparePecForDev', [$event]);
    }
}
