<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 18:48
 */

namespace System\Commands\MVC;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScaffoldCommand extends \Wizard
{

    protected function configure()
    {
        $this
            ->setName('mvc:scaffold')
            ->setDescription('Scaffold a Model, View & Controller.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of your files'
            )
            ->addArgument(
                'options',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'provide an array of options, eg. namespace.'
            )
            ->addOption(
                'skeleton',
                null,
                InputOption::VALUE_NONE,
                'If set the scaffold project will be bare bone.'
            );
    }

    private $skeleton = false;
    private $options = array();

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->options = $input->getArgument('options');
        $name = $input->getArgument('name');
        if ($name) {
            $this->options['name'] = $name;
        } else {
            $output->writeln('please define a name for your project.');
        }

        if ($input->getOption('skeleton')) {
            $this->skeleton = true;
        }
        $this->make();
    }

    private function make()
    {
        if ($this->skeleton)
            $this->options['skeleton'] = true;
        $this->model();
        $this->view();
        $this->controller();
    }

    private function model()
    {
        $model = new ModelCommand();
        $model->build($this->options);
    }

    private function view()
    {
        $view = new ViewCommand();
        $view->build($this->options);
    }

    private function controller()
    {
        $controller = new ControllerCommand();
        $controller->build($this->options);
    }
}