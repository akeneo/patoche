<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Deployment\Subscriber;

use Akeneo\Application\Deployment\PreparePimForDeployment;
use Akeneo\Application\Deployment\PreparePimForDeploymentHandler;
use Akeneo\Application\Onboarder\OnboarderRelease;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Webmozart\Assert\Assert;

final class PreparePimForDeploymentSubscriber implements EventSubscriberInterface
{
    private $preparePimForDeploymentHandler;

    public function __construct(PreparePimForDeploymentHandler $preparePimForDeploymentHandler)
    {
        $this->preparePimForDeploymentHandler = $preparePimForDeploymentHandler;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.onboarder_release.transition.prepare_pim_enterprise_cloud_for_dev' => 'preparePecForDev',
        ];
    }

    public function preparePecForDev(Event $event): void
    {
        $onboarderRelease = $event->getSubject();
        Assert::isInstanceOf($onboarderRelease, OnboarderRelease::class, sprintf(
            'Event subject should be an instance of "OnboarderRelease", "%s" provided.',
            get_class($onboarderRelease)
        ));

        $preparePimForDeployment = new PreparePimForDeployment(
            new Repository(
                $onboarderRelease->getOrganization(),
                new Project(Project::PIM_ONBOARDER_BUNDLE),
                $onboarderRelease->getBranch()
            ),
            $onboarderRelease->getBranchForProject(new Project(Project::PIM_ENTERPRISE_CLOUD)),
            $onboarderRelease->getWorkingDirectory()
        );

        ($this->preparePimForDeploymentHandler)($preparePimForDeployment);
    }
}
