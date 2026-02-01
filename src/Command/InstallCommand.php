<?php

namespace Nadi\Yii\Command;

use Nadi\Yii\Shipper\Shipper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

class InstallCommand extends Command
{
    protected static $defaultName = 'nadi:install';

    protected static $defaultDescription = 'Install and configure Nadi monitoring for your Yii 3 application';

    public function __construct(
        private string $rootPath,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Installing Nadi Monitoring...</info>');

        // Copy default config
        $configSource = dirname(__DIR__, 2) . '/config/params.php';
        $configDest = $this->rootPath . '/config/packages/nadi/params.php';

        if (! file_exists($configDest)) {
            if (! is_dir(dirname($configDest))) {
                mkdir(dirname($configDest), 0755, true);
            }
            if (file_exists($configSource)) {
                copy($configSource, $configDest);
                $output->writeln('<info>Configuration published to config/packages/nadi/params.php</info>');
            }
        } else {
            $output->writeln('<comment>Configuration already exists.</comment>');
        }

        // Install shipper
        $output->writeln('<info>Installing Shipper Binary...</info>');

        try {
            $shipper = new Shipper($this->rootPath);
            $shipper->install();
            $output->writeln('<info>Shipper binary installed successfully.</info>');
        } catch (\Exception $e) {
            $output->writeln('<comment>Could not install shipper: ' . $e->getMessage() . '</comment>');
        }

        $output->writeln('');
        $output->writeln('<info>Add to your .env:</info>');
        $output->writeln('  NADI_API_KEY=your-api-key');
        $output->writeln('  NADI_APP_KEY=your-app-key');
        $output->writeln('');
        $output->writeln('<info>Nadi monitoring installed successfully!</info>');

        return ExitCode::OK;
    }
}
