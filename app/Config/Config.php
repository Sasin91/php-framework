<?php
return array(
        // ---------------------------------------------------- //
       //  This file contains system specific Configurations,  //
      //  edit with care.                                     //
     // ---------------------------------------------------- //

    /**
     * Whether or not to use Composer.
     */
    'Composer' => true,

    /**
     * Whetever or not if the framework is installed.
     */
    'Installed' => false,

    /**
     * User specific variables
     */
    'User' => array(
        'GeoIP' => array(
            'path' => '/usr/share/GeoIP/GeoLite2-Country.mmdb'
        ),

        /**
         * Encryption variables
         */
    'Encryption' => array(
        'BcryptHastCost' => '10',
        'appKey' => ''
        )
    ),
    /**
     * Classes that should be loaded and registered in runtime.
     */
    'Autoload' => array(
        'System\MVC\*',
        'Toolbox\*',
        'Libraries\DatabaseInteractive\TextHandling\Parsedown\ParsedownExtra'
    ),

    /**
     * System Paths
     */
    'Paths' => array(
        'pub_Assets' => 'Assets',
        'sfw_Libraries' => 'Libraries',
        'sfw_Core' => 'Core',
        'sfw_Application' => 'Application',
        'sfw_Config' => 'Config',
        'sfw_Locales' => 'Config/Locales'
    ),
    /**
     * System Messages
     */
    'Messages' => array(
        'mail' => array(
            'passwordResetSubject' => 'Password reset for ',
            'passwordResetContent' => 'Please click on this link to reset your password: ',
            'verifyUserSubject' => 'Account verification for ',
            'verifyUserContent' => 'Please click on this link to activate your account: '
        ),
        'feedback' => array(
            'wrongDetails' 	 => ' is not valid.',
            'takenDetails' 	 => ' is already registered.',
            'noexist' 	   	 => ' does not exist.',
            'NotActive' 	 => ' is not activated yet, please click on the confirm link in the mail.',
            'upgradeSuccess' => ' Upgraded successfully.',
            'upgradeFailure' => ' Upgrade failed.'
        )
    ),

    /**
     * Base configurations
     */
    'Base' => array(
    'url' => 'http://theupcycle.dev',
    'default_url' => 'Home',
    'title' => 'The upcycle',
    'encoding' => 'UTF-8',
    'language' => 'da',
    'timezone' => 'Europe/Copenhagen',
    'enviroment' => 'Development',
    ),

    /**
     * Database Configurations
     */
    'Database' => array(
        'Factory' => array(
            'class' => 'PDO',
        ),
        'type' => 'MySQL',
        'Databases' => array(
            'Auth' => array(
                'dsn' => 'mysql',
                'host' => 'localhost',
                #'socket' => '/var/run/mysqld/mysqld.sock',
                'port' => '3306',
                'user' => 'root',
                'pass' => '',
                'name' => 'theupcycle_auth'
            ),
            'Layout' => array(
                'dsn' => 'mysql',
                #'socket' => '/var/run/mysqld/mysqld.sock',
                'host' => 'localhost',
                'port' => '3306',
                'user' => 'root',
                'pass' => '',
                'name' => 'theupcycle_layout'
            ),
            'Shop' => array(
                'dsn' => 'mysql',
                #'socket' => '/var/run/mysqld/mysqld.sock',
                'host' => 'localhost',
                'port' => '3306',
                'user' => 'root',
                'pass' => '',
                'name' => 'theupcycle_shop'
            ),
            'Blog' => array(
                'dsn' => 'mysql',
                #'socket' => '/var/run/mysqld/mysqld.sock',
                'host' => 'localhost',
                'port' => '3306',
                'user' => 'root',
                'pass' => '',
                'name' => 'theupcycle_blog'
            )
        )
    ),

    /**
     * Cache configurations
     */
    'Cache' => array(
        'type' => 'File',

    'Factory' => array(
        'path' => '\\Adapters\\File',

        'memecached' => array(
            'host' => 'localhost',
            'port' => '11211'
            )
        )
    )

);