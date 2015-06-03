<?php

namespace System\Commands\System;

/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 24-04-15
 * Time: 19:46
 */
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GulpCommand extends \Wizard
{

    protected function configure()
    {
        $this
            ->setName('gulp')
            ->setDescription('use Gulp to compile less & CoffeeScripts');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        chdir(ROOT_PATH . DS . 'Application/Storage/Configurations/Application/Java/npm');
        shell_exec('gulp');
    }
}