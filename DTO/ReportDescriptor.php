<?php

declare(strict_types=1);

namespace Eldahar\SonarQubeReportBundle\DTO;

use Eldahar\SonarQubeAPI\DTO\ProjectComponent;
use Eldahar\SonarQubeAPI\Enum\IssueSeverityEnum;

final class ReportDescriptor
{
    private ProjectComponent $project;
    /**
     * @var IssueReport[][][]
     */
    private array $issues;

    private array $issueCounts;

    public function getIssues(): array
    {
        return $this->issues;
    }

    public function setIssues(array $issues): void
    {
        $this->issues = $issues;
    }

    public function hasIssues(): bool
    {
        return isset($this->issues);
    }

    public function getProject(): ProjectComponent
    {
        return $this->project;
    }

    public function setProject(ProjectComponent $project): void
    {
        $this->project = $project;
    }

    public function hasProject(): bool
    {
        return isset($this->project);
    }

    public function getIssueCounts(): array
    {
        return $this->issueCounts;
    }

    public function setIssueCounts(array $issueCounts): void
    {
        $this->issueCounts = $issueCounts;
    }

    public function hasIssueCounts(): bool
    {
        return isset($this->issueCounts);
    }

    public function getBlockerIssues(): array
    {
        return $this->getIssuesBySeverity(IssueSeverityEnum::BLOCKER);
    }

    public function getIssuesBySeverity(IssueSeverityEnum $severity): array
    {
        return $this->issues[$severity->value] ?? [];
    }

    public function getCriticalIssues(): array
    {
        return $this->getIssuesBySeverity(IssueSeverityEnum::CRITICAL);
    }

    public function countBlockerIssues(): int
    {
        return $this->countIssuesBySeverity(IssueSeverityEnum::BLOCKER);
    }

    public function getMajorIssues(): array
    {
        return $this->getIssuesBySeverity(IssueSeverityEnum::MAJOR);
    }

    public function getMinorIssues(): array
    {
        return $this->getIssuesBySeverity(IssueSeverityEnum::MINOR);
    }

    public function getInfoIssues(): array
    {
        return $this->getIssuesBySeverity(IssueSeverityEnum::INFO);
    }

    public function countCriticalIssues(): int
    {
        return $this->countIssuesBySeverity(IssueSeverityEnum::CRITICAL);
    }

    public function countMajorIssues(): int
    {
        return $this->countIssuesBySeverity(IssueSeverityEnum::MAJOR);
    }

    public function countMinorIssues(): int
    {
        return $this->countIssuesBySeverity(IssueSeverityEnum::MINOR);
    }

    public function countInfoIssues(): int
    {
        return $this->countIssuesBySeverity(IssueSeverityEnum::INFO);
    }

    public function countIssuesBySeverity(IssueSeverityEnum $severity): int
    {
        return $this->issueCounts[$severity->value] ?? 0;
    }
}
