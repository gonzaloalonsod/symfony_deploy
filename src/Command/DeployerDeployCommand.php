<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

// use Deployer\Deployer;

class DeployerDeployCommand extends Command
{
    protected static $defaultName = 'deployer:deploy';
    protected static $defaultDescription = 'dep deploy';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('depDirectory', InputArgument::OPTIONAL, 'Project depDirectory')
            ->addArgument('name', InputArgument::OPTIONAL, 'Project name')
            ->addArgument('script', InputArgument::OPTIONAL, 'Project script')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $depDirectory = $input->getArgument('depDirectory');
        $name = $input->getArgument('name');
        $script = $input->getArgument('script');

        if ($depDirectory) {
            $io->note(sprintf('You passed an argument: %s', $depDirectory));
        }
        if ($name) {
            $io->note(sprintf('You passed an argument: %s', $name));
        }
        if ($script) {
            $io->note(sprintf('You passed an argument: %s', $script));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('dep deploy --help to see your options.');

        $executableFinder = new ExecutableFinder();
        $depPath = $executableFinder->find('dep');
        if ($depPath) {
            if ($depDirectory && $name && $script) {
                $process = new Process([$script]);
            } else {
                $process = new Process([$depPath, '-h']);
            }
            $process->run(function ($type, $buffer) use ($io) {
                if (Process::ERR === $type) {
                    $io->error($buffer);
                } else {
                    $io->text($buffer);
                }
            });
        } else {
            $io->error('Not exist binary dep');
        }

        return Command::SUCCESS;
    }
}
