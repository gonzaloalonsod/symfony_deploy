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

class DeployerDeployCommand extends Command
{
    protected static $defaultName = 'deployer:deploy';
    protected static $defaultDescription = 'dep deploy';

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('dep deploy --help to see your options.');

        $executableFinder = new ExecutableFinder();
        $depPath = $executableFinder->find('dep');
        if ($depPath) {
            $process = new Process([$depPath, '-h']);
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
