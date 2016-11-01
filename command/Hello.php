<?php

namespace Clat\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Hello extends Command
{
    const COMMAND_NAME = 'hello';
    const COMMAND_DESCRIPTION = 'Just says hello to <name>';

    public function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addArgument('name', InputArgument::REQUIRED, 'Your name');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $message = sprintf('Hello %s', $input->getArgument('name'));
        $output->writeln("<info>{$message}</info>");
    }
}