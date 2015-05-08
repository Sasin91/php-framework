<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 18:48
 */

namespace System\Commands\MVC;


class ControllerCommand {

    /**
     * @param array $options
     */
    public function build(array $options)
    {
        $path = 'Application\\Controllers';
        $templateUse = 'use %s;';
        $templateUseAs = 'use %s as %s;';
        $templateCode = "<?php
        %s
        class %sController extends %s
        {
            %s
        }
        ";

        $className = $options['name'];

        if (isset($options['namespace']))
        {
            $namespace = 'namespace ' .$path.'\\'. $options['namespace'] . ';'
                . PHP_EOL . PHP_EOL;
        } else {
            $namespace = 'namespace ' .$path. ';' . PHP_EOL . PHP_EOL;
        }

        $extends = 'BaseController';
        if (isset($options['extends'])) {
            if (!empty($options['extends'])) {
                $extends = $options['extends'];
            }
        }

        $construct = "
        public function __construct()
        {
            parent::__construct();
        }
        ";
        $templateThis = " \$this->%s(%s);" . PHP_EOL;


        $attributes = array();
        $content .= join('', $attributes);
        $content .= $construct;

        $code = sprintf(
            $templateCode,
            $namespace,
            $className,
            $extends,
            $content
        );
        file_put_contents(ROOT_PATH.DS.'Application/Controllers'.DS.$className.'Controller'.'.php', $code);
    }
}