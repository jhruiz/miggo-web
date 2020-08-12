<?php
class DATABASE_CONFIG
{

    public $test = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'test_database_name',
    );
    public $default = array(
      'datasource' => 'Database/Mysql',
      'persistent' => false,
      'host' => 'localhost',
      'login' => 'miposcom_mysql',
      'password' => 'root9002',
      'database' => 'miposcom_torque',
    );
}
