<?php

namespace Nadi\Yii\Command;

use Nadi\Yii\Shipper\Shipper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Yii\Console\ExitCode;

class UpdateShipperCommand extends Command
{
    protected static $defaultName = 'nadi:update-shipper';

    protected static $defaultDescription = 'Update the Nadi shipper binary';

    public function __construct(
        private string $rootPath,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Updating Nadi Shipper...</info>');

        try {
            $shipper = new Shipper($this->rootPath);
            $shipper->install();
            $output->writeln('<info>Shipper binary updated successfully.</info>');

            return ExitCode::OK;
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to update shipper: ' . $e->getMessage() . '</error>');

            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
