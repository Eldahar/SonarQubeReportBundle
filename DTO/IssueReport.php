<?php

declare(strict_types=1);

namespace Eldahar\SonarQubeReportBundle\DTO;

use Eldahar\SonarQubeAPI\DTO\Issue;
use Eldahar\SonarQubeAPI\DTO\IssueComponent;

final class IssueReport
{
    private Issue $issue;
    private IssueComponent $component;

    public function __construct(Issue $issue, IssueComponent $component)
    {
        $this->issue = $issue;
        $this->component = $component;
    }

    public function getIssue(): Issue
    {
        return $this->issue;
    }

    public function hasIssue(): bool
    {
        return isset($this->issue);
    }

    public function getComponent(): IssueComponent
    {
        return $this->component;
    }

    public function hasComponent(): bool
    {
        return isset($this->component);
    }
}
