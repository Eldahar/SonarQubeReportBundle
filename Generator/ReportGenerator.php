<?php

declare(strict_types=1);

namespace Eldahar\SonarQubeReportBundle\Generator;

use Eldahar\SonarQubeReportBundle\DTO\ReportDescriptor;
use Twig\Environment;

final class ReportGenerator
{

    public const BLOCKER = 'blocker';
    public const CRITICAL = 'critical';
    public const MAJOR = 'major';
    public const MINOR = 'minor';
    public const INFO = 'info';

    public function __construct(
        private Environment $twig,
    ) {
    }

    public function generate(ReportDescriptor $descriptor): string
    {
        $context = [
            'counts' => [
                self::BLOCKER => $descriptor->countBlockerIssues(),
                self::CRITICAL => $descriptor->countCriticalIssues(),
                self::MAJOR => $descriptor->countMajorIssues(),
                self::MINOR => $descriptor->countMinorIssues(),
                self::INFO => $descriptor->countInfoIssues()
            ],
            'blocker_issues' => $descriptor->getBlockerIssues(),
            'critical_issues' => $descriptor->getCriticalIssues(),
            'major_issues' => $descriptor->getMajorIssues(),
            'minor_issues' => $descriptor->getMinorIssues(),
            'info_issues' => $descriptor->getInfoIssues(),
            'project' => $descriptor->getProject(),
        ];

        return $this->twig->render('report.html.twig', $context);
    }
}
