<?php
class DATABASE_CONFIG {

	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'user',
		'password' => 'password',
		'database' => 'test_database_name',
	);
	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'miposcom_mysql',
		'password' => 'root9002',
		'database' => 'miposcom_torque',	    
		//'datasource' => 'Database/Mysql',
		//'persistent' => false,
		//'host' => 'localhost',
		//'login' => 'miposcom_miposcomtest',
		//'password' => 'Tba!cr=JUqc&',
		//'database' => 'miposcom_torquetest',
	);
}
