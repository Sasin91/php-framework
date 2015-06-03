<?php
include_once 'index.php';

/**
 * Class Install
 */
class Install
{

    private $config = array();

    private $installClearance = false;

    private $dataPath;

    public function __construct(array $config)
    {
        /**
         * Bind the configurations we received by injection to an object.
         */
        $this->config = $config;

        require_once BASE_PATH . '/app/Http/View/install.php';

        $this->route();
    }


    public function verify($permission = false)
    {

        if($permission)
        {
            $this->installClearance = true;
            $this->dataPath = BASE_PATH . DS . 'Server Configuration' . DS;
            $this->startInstall();
        }

    }

    private function startInstall()
    {

        /**
         * Fill the database(s)
         */
        $this->database();

        /**
         * Calculate and set a good BCrypt hash expense cost.
         */
        Config::update('Config', array('User' => array('Encryption' => array('BcryptHastCost' => $this->calculateBCryptCost()))), true);

        /**
         * Verifies MaxMind GeoIP GeoLite DB exists, if not, copies it.
         */
        $this->GeoIP();

        /**
         * We're now done with installing the framework, time to flag it as so.
         */
        Config::update('Config', array('Installed' => true), true);
    }



    private function database()
    {
        /**
         * Bind configuration to in method ('local') variable
         */
        $config = $this->config['Database']['Factory']['Databases'];

        /**
         * Bind Database path to local variable
         */
        $db_dataPath = $this->dataPath . 'Database';

        /**
         * Change working path.
         */
        chdir($db_dataPath);

        /**
         * Iterate through our database configurations.
         */
        foreach ($config as $database => $credentials) {
            $sqlFile = $db_dataPath . DS . $database . '.sql';

            if (is_file($sqlFile)) {
                /**
                 * Get a PDO Instance, create a transaction and populate.
                 */
                $DB = new \System\Factories\Database\SQL\MySQL\Adapters\PDO($credentials, $database);
                if(!$DB->databaseExist($database)) {

                    $DB->newTransaction();
                    $DB->singleQuery(file_get_contents($sqlFile), $bind = array(), 'bool');
                    $DB->commit();

                }
            }
        }
    }

    private function calculateBCryptCost()
    {
        /**
         * This code will benchmark your server to determine how high of a cost you can
         * afford. You want to set the highest cost that you can without slowing down
         * you server too much. 8-10 is a good baseline, and more is good if your servers
         * are fast enough. The code below aims for ≤ 50 milliseconds stretching time,
         * which is a good baseline for systems handling interactive logins.
         */
        $timeTarget = 0.05; // 50 milliseconds

        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        return $cost;
    }

    private function GeoIP()
    {
        $GeoIPPath = BASE_PATH . $this->config['User']['GeoIP']['path'];
        if(!file_exists($GeoIPPath.'/GeoLite2-Country.mmdb'))
        {
            chdir(BASE_PATH . DS . 'Server Configuration/GeoIP/Databases');
            copy('GeoLite2-Country.mmdb', $GeoIPPath);
        }
    }

    public function route()
    {
        $request = $this->getRequest();
        if(isset($request['url']))
        {
            $method = $request['url'];
            array_shift($request);
            return $this->$method($request);
        }
        return false;
    }

    public function getRequest()
    {
       return filter_var_array($_REQUEST, FILTER_SANITIZE_STRING);
    }
}