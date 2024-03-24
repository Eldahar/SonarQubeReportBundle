<?php

declare(strict_types=1);

namespace Eldahar\SonarQubeReportBundle\Builder;

use Eldahar\SonarQubeAPI\Enum\IssueSeverityEnum;
use Eldahar\SonarQubeAPIBundle\Handler\IssueHandler;
use Eldahar\SonarQubeAPIBundle\Handler\ProjectHandler;
use Eldahar\SonarQubeReportBundle\DTO\IssueReport;
use Eldahar\SonarQubeReportBundle\DTO\ReportDescriptor;

final class ReportDescriptorBuilder
{
    private string $projectKey;

    public function __construct(
        private ProjectHandler $projectHandler,
        private IssueHandler $issueHandler,
    ) {
    }


    public function build(): ReportDescriptor
    {
        $descriptor = new ReportDescriptor();
        $descriptor->setProject(
            $this->projectHandler->search($this->projectKey)
        );
        $issuesWithComponents = $this->issueHandler->search($this->projectKey);
        $indexedComponents = [];
        foreach ($issuesWithComponents->getComponents() as $component) {
            if (!$component->enabled) {
                continue;
            }
            $indexedComponents[$component->key] = $component;
        }
        $issueReports = [];
        $issueCounts = [
            IssueSeverityEnum::BLOCKER->value => 0,
            IssueSeverityEnum::CRITICAL->value => 0,
            IssueSeverityEnum::MAJOR->value => 0,
            IssueSeverityEnum::MINOR->value => 0,
            IssueSeverityEnum::INFO->value => 0,
        ];
        foreach ($issuesWithComponents->getIssues() as $issue) {
            if (!array_key_exists($issue->component, $indexedComponents)) {
                continue;
            }
            $issueReports[$issue->severity->value][$issue->component][] = new IssueReport(
                $issue,
                $indexedComponents[$issue->component]
            );
            $issueCounts[$issue->severity->value]++;
        }
        $indexedIssueReports = [];
        foreach ($issueReports as $severity => $issuesByComponent) {
            ksort($issuesByComponent);
            $indexedIssueReports[$severity] = $issuesByComponent;
        }
        $descriptor->setIssues($indexedIssueReports);
        $descriptor->setIssueCounts($issueCounts);

        return $descriptor;
    }

    public function reset(): self
    {
        unset($this->projectKey);

        return $this;
    }

    public function setProjectKey(string $projectKey): self
    {
        $this->projectKey = $projectKey;

        return $this;
    }
}
