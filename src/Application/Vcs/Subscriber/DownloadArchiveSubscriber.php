<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs\Subscriber;

use Akeneo\Application\Onboarder\OnboarderRelease;
use Akeneo\Application\Vcs\DownloadArchive;
use Akeneo\Application\Vcs\DownloadArchiveHandler;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

final class DownloadArchiveSubscriber implements EventSubscriberInterface
{
    private $downloadArchiveHandler;

    public function __construct(DownloadArchiveHandler $downloadArchiveHandler)
    {
        $this->downloadArchiveHandler = $downloadArchiveHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.onboarder_release.transition.download_pim_enterprise_cloud_archive' => 'downloadPec',
        ];
    }

    public function downloadPec(Event $event): void
    {
        $this->downloadArchive(Project::PIM_ENTERPRISE_CLOUD, $event->getSubject());
    }

    private function downloadArchive(string $projectName, OnboarderRelease $onboarderRelease): void
    {
        $organization = $onboarderRelease->getOrganization();
        $project = new Project($projectName);
        $branch = $onboarderRelease->getBranchForProject($project);

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $onboarderRelease->getWorkingDirectory()
        );

        ($this->downloadArchiveHandler)($downloadArchive);
    }
}
