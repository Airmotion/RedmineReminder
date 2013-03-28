<?php
namespace Airmotion\RedmineReminder\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListResolvedIssuesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('issues:resolved:list')
            ->setDescription('Show resolved issues that have not been updated for a while (Default 5 days)')
            ->addOption('last-updated', 'l', InputOption::VALUE_OPTIONAL, 'Show only issues that have not been updated for X day (Default: 5, minimum: 1)', 5)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>I am going to do something very useful</info>');
    }
}