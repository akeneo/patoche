<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs\Subscriber;

use Akeneo\Application\ReleaseProcess;
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
            'workflow.onboarder_release.transition.download_middleware_archive' => 'downloadMiddleware',
            'workflow.onboarder_release.transition.download_overseer_archive' => 'downloadOverseer',
            'workflow.onboarder_release.transition.download_supplier_onboarder_archive' => 'downloadSupplierOnboarder',
            'workflow.onboarder_release.transition.download_pim_onboarder_bundle_archive' => 'downloadPimOnboarder',
        ];
    }

    public function downloadPec(Event $event): void
    {
        $this->downloadArchive(Project::PIM_ENTERPRISE_CLOUD, $event->getSubject());
    }

    public function downloadMiddleware(Event $event): void
    {
        $this->downloadArchive(Project::MIDDLEWARE, $event->getSubject());
    }

    public function downloadOverseer(Event $event): void
    {
        $this->downloadArchive(Project::OVERSEER, $event->getSubject());
    }

    public function downloadSupplierOnboarder(Event $event): void
    {
        $this->downloadArchive(Project::SUPPLIER_ONBOARDER, $event->getSubject());
    }

    public function downloadPimOnboarder(Event $event): void
    {
        $this->downloadArchive(Project::PIM_ONBOARDER_BUNDLE, $event->getSubject());
    }

    private function downloadArchive(string $projectName, ReleaseProcess $releaseProcess): void
    {
        $organization = $releaseProcess->getOrganization();
        $project = new Project($projectName);
        $branch = $releaseProcess->getBranchForProject($project);

        $downloadArchive = new DownloadArchive(
            new Repository($organization, $project, $branch),
            $releaseProcess->getWorkingDirectory()
        );

        ($this->downloadArchiveHandler)($downloadArchive);
    }
}
