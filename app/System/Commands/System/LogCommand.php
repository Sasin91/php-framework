<?php

namespace System\Commands\System;

/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 24-04-15
 * Time: 19:46
 */
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogCommand extends \Wizard
{

    protected $name;

    protected function configure()
    {
        $this
            ->setName('logs:clear')
            ->setDescription('Clear one or multiple log files')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Name of your log file(s)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $this->name = $name;
        } else {
            $this->name = '*';
        }
        $this->clear();
    }

    protected function clear()
    {
        chdir(ROOT_PATH . DS . 'app/Storage/Logs');
        shell_exec('truncate -s 0 ' . $this->name);
    }

}