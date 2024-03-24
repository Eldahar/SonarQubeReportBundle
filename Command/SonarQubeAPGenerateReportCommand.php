<?php

declare(strict_types=1);

namespace Eldahar\SonarQubeReportBundle\Command;

use Eldahar\SonarQubeReportBundle\Builder\ReportDescriptorBuilder;
use Eldahar\SonarQubeReportBundle\Generator\ReportGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(SonarQubeAPGenerateReportCommand::NAME)]
final class SonarQubeAPGenerateReportCommand extends Command
{
    public const NAME = 'sonarqube:report:generate';
    public const PROJECT_KEY = 'projectKey';

    public function __construct(
        private ReportDescriptorBuilder $reportDescriptorBuilder,
        private ReportGenerator $reportGenerator,
    ) {
        parent::__construct();
    }


    protected function configure()
    {
        $this->setDescription('Generate SonarQube report')
            ->addArgument(self::PROJECT_KEY,  InputArgument::REQUIRED, 'Project key');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectKey = $input->getArgument(self::PROJECT_KEY);
        $descriptor = $this->reportDescriptorBuilder->reset()
            ->setProjectKey($projectKey)
            ->build();
        $report = $this->reportGenerator->generate($descriptor);
        $filesystem = new Filesystem();
        $filesystem->dumpFile('var/report.html', $report);

        return Command::SUCCESS;
    }
}
