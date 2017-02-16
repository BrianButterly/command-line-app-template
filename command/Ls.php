<?php

namespace Clat\Command;

use Clat\Component\File;
use Rych\ByteSize\ByteSize;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Ls extends Command
{
    const COMMAND_NAME = 'ls';
    const COMMAND_DESCRIPTION = 'List of files';
    //const STORAGE_PATH = __DIR__ . '/../default_path/';
    private $exclude_files = ['.', '..'];
    private $columns = [
        'name',
        'type',
        'size',
        'date'
    ];
    protected $files = [];
    
    public function __construct()
    {
        $this->getFiles();
        parent::__construct();
    }

    public function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption('order-by', null, InputOption::VALUE_OPTIONAL, 'The sort order', 'date')
            ->addOption('order-as', null, InputOption::VALUE_OPTIONAL, 'Sorting direction [ASC, DESC]', 'desc');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->files)
            die($output->writeln('<info>Nothing to display</info>'));

        if (! in_array(strtolower($input->getOption('order-by')), $this->columns))
            die($output->writeln("<error>Undefined order-by column</error>"));

        $this->sort(
            strtolower($input->getOption('order-by')),
            strtolower($input->getOption('order-as'))
        );

        $table = (new Table($output))->setHeaders($this->columns);

        foreach ($this->files as $file)
        {
            $table->addRow((array) $file->format());
        }

        $table->render();
    }

    private function getFiles()
    {
        $files = array_filter(
            scandir($this->getStoragePath()),
            function($file) {
                if (in_array($file, $this->exclude_files))
                    return false;
                return true;
            }
        );

        $this->files = array_map(function ($file) {
            return new File($this->getStoragePath() . $file);
        }, $files);
    }

    private function getStoragePath()
    {
        return $_SERVER['PWD'] . '/';
    }

    private function sort($orderBy, $orderAs)
    {
        usort($this->files, function ($a, $b) use ($orderBy, $orderAs) {
            return ($orderAs == 'desc') ? $b[$orderBy] <=> $a[$orderBy] : $a[$orderBy] <=> $b[$orderBy];
        });
    }
}