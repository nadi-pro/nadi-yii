<?php

namespace Nadi\Yii\Command;

use Nadi\Yii\Nadi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

class TestCommand extends Command
{
    protected static $defaultName = 'nadi:test';

    protected static $defaultDescription = 'Test the Nadi monitoring connection';

    public function __construct(
        private Nadi $nadi,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Testing Nadi Connection...</info>');

        $transporter = $this->nadi->getTransporter();

        if (! $transporter) {
            $output->writeln('<error>Nadi transporter is not configured.</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        }

        try {
            $result = $transporter->test();

            if ($result) {
                $output->writeln('<info>Successfully connected to Nadi!</info>');

                return ExitCode::OK;
            }

            $output->writeln('<error>Connection test failed.</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        } catch (\Exception $e) {
            $output->writeln('<error>Connection test failed: '.$e->getMessage().'</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
