<?php

namespace Nadi\Yii\Command;

use Nadi\Yii\Nadi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

class VerifyCommand extends Command
{
    protected static $defaultName = 'nadi:verify';

    protected static $defaultDescription = 'Verify the Nadi monitoring configuration';

    public function __construct(
        private Nadi $nadi,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Verifying Nadi Configuration...</info>');

        $config = $this->nadi->getConfig();

        // Check enabled
        $enabled = $config['enabled'] ?? false;
        $output->writeln('Enabled: ' . ($enabled ? '<info>Yes</info>' : '<comment>No</comment>'));

        // Check driver
        $driver = $config['driver'] ?? 'log';
        $output->writeln("Driver: <info>{$driver}</info>");

        // Check driver configuration
        $connections = $config['connections'] ?? [];
        $driverConfig = $connections[$driver] ?? [];

        if ($driver === 'http') {
            if (empty($driverConfig['api_key'])) {
                $output->writeln('<error>HTTP driver requires NADI_API_KEY.</error>');

                return ExitCode::UNSPECIFIED_ERROR;
            }
            if (empty($driverConfig['app_key'])) {
                $output->writeln('<error>HTTP driver requires NADI_APP_KEY.</error>');

                return ExitCode::UNSPECIFIED_ERROR;
            }
        }

        // Check transporter
        $transporter = $this->nadi->getTransporter();
        if (! $transporter) {
            $output->writeln('<error>Transporter could not be initialized.</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        }

        try {
            $result = $transporter->verify();
            if ($result) {
                $output->writeln('<info>Configuration verified successfully!</info>');

                return ExitCode::OK;
            }

            $output->writeln('<error>Verification failed.</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        } catch (\Exception $e) {
            $output->writeln('<error>Verification error: ' . $e->getMessage() . '</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
