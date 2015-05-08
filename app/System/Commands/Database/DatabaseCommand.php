<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 18:48
 */

namespace System\Commands\Database;

use System\Commands\Database\Seeder\TableSeeder;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCommand extends \Wizard
{

    protected function configure()
    {
        $this
            ->setName('db')
            ->setDescription('Perform Database related actions.')
            ->addArgument(
                'type',
                InputArgument::REQUIRED,
                'Name of the database type you want to communicate with, eg. mysql'
            )
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'define which action you want to perform, eg. cli for getting a shell.'
            )
            ->addArgument(
                'db',
                InputArgument::REQUIRED,
                'define which database to connect to.'
            )
            ->addOption(
                'table',
                null,
                InputOption::VALUE_OPTIONAL,
                'Which table to act on.'
            );
    }

    private $client = '';
    private $db = '';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $action = $input->getArgument('action');
        $this->db = $input->getArgument('db');

        return $this->$type()->$action($input->getOption('table'));
    }

    private function mysql()
    {
        $this->client = 'mysql';
        return $this;
    }

    private function seed($table  = '')
    {
        if(isset($this->client) && isset($this->db) && isset($table))
        {
            $seeder = new TableSeeder($this->db);
            $seeder->seed($table);
        }
    }
}