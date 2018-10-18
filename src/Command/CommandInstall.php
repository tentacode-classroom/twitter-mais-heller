<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;

class CommandInstall extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:install')
            ->setDescription('Install the project')
            ->setHelp('This command allows you to install the project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Project Heller Installer',
            '============',
            '',
        ]);

        
 
        $output->writeln('Installing the project ... ');

        $commands = [
            'composer require orm-fixtures --dev',
            'composer require twig/extensions',
        ];

        foreach ($commands as $command) {
            $process = new Process(
                $command
            );
            $process->run();
        }
    }
}
