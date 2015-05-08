<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 18:48
 */

namespace System\Commands\Composer;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ComposerCommand extends \Wizard
{
    private $path = '';
    protected function configure()
    {
        $this
            ->setName('composer')
            ->setDescription('Perform Composer tasks.')
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'define which action you want to perform, eg. update.'
            );
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->path = \Config::get('Config')['Paths']['sfw_Libraries'];
        $action = $input->getArgument('action');
        return $this->$action();
    }

    public function __call($name, $argument)
    {
        chdir(BASE_PATH.DS.'app'.DS.$this->path);
        shell_exec('composer '.$name);
    }



}