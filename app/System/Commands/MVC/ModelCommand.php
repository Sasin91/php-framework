<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 18:48
 */

namespace System\Commands\MVC;


class ModelCommand
{


    /**
     * @param array $options
     */
    public function build(array $options)
    {
        $path = 'Application\\Models';
        $templateUse = 'use %s;';
        $templateUseAs = 'use %s as %s;';
        $templateCode = "<?php
        %s
        class %sModel extends %s
        {
            %s
        }
        ";

        $className = $options['name'];

        if (isset($options['namespace'])) {
            $namespace = 'namespace ' . $path . '\\' . $options['namespace'] . ';'
                . PHP_EOL . PHP_EOL;
        } else {
            $namespace = 'namespace ' . $path . ';' . PHP_EOL . PHP_EOL;
        }

        $extends = 'BaseModel';
        if (isset($options['extends'])) {
            if (!empty($options['extends'])) {
                $extends = $options['extends'];
            }
        }

        if (isset($options['table'])) {
            $database = $options['table'];
        } else {
            $database = '';
        }

        $construct = "
        public function __construct()
        {
            parent::__construct();
        }
        ";
        $templateThis = " \$this->%s(%s);" . PHP_EOL;

        $templateTable = "protected \$database = $database;";

        $attributes = array();
        $content = join('', $attributes);
        $content .= $construct;
        $content .= $templateTable;

        $code = sprintf(
            $templateCode,
            $namespace,
            $className,
            $extends,
            $content
        );
        file_put_contents(ROOT_PATH . DS . 'Application/Models' . DS . $className . 'Model' . '.php', $code);
    }
}