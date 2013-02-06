<?php

/*
| ======================================================================
| ZigPHP Database Config
| ======================================================================
|
| Edit settings for accessing ZigPHP's database functionality.  
|
*/

/*
|--------------------------------------------------------------------------
| Default Config
|--------------------------------------------------------------------------
|
| Set which is the default DB config for ZigPHP to use. 
|
*/
config::set('DB_DEFAULT_CONFIG', 'default');

/*
|--------------------------------------------------------------------------
| Auto-load the Database 
|--------------------------------------------------------------------------
|
| If you want ZigPHP to auto-load a database resource, set to TRUE. 
| If set to FALSE, the database class must be opened in a controller or model. 
| To manually load the database, call $this->load->loadDB();
|
*/
config::set('DB_AUTOLOAD', FALSE);

// DO NOT EDIT THE LINE BELOW
$DB_CONFIG = array();

/*
|--------------------------------------------------------------------------
| Database Connection Information
|--------------------------------------------------------------------------
|
| Enter your database connection settings. 
|
| NOTE: db_host is usually "localhost" unless the database is located on 
| an external server. If you need to add a port, simply add it to the 
| db_host value. Ex. localhost:3306. 
| 
| 'db_host' = Hostname for database. 
| 'db_user' = Username used to connect to database.
| 'db_pass' = Password used to connect to database.
| 'db_name' = The name of your default database to connect to. 
|
|--------------------------------------------------------------------------
| Table Prefix
|--------------------------------------------------------------------------
|
| It is recommended you set a prefix to avoid collisions. 
| 
| Ex. 'db_table_prefix' = 'zig_';
|
|--------------------------------------------------------------------------
| DB Configs
|--------------------------------------------------------------------------
|
| DB config settings are stored in an array to allow for multiple 
| enviroments and connection settings if dealing with multiple databases.  
|
| Configs are defiend by adding an associative array to the DB_CONFIG array. 
| The key of the array corresponds to the config name. 
| Ex. $DB_CONFIG['default'] or $DB_CONFIG['development']
|
| Ex. $DB_CONFIG['default'] = array(
|	'db_host' => 'localhost',
|	'db_user' => 'root',
|	'db_pass' => 'root',
|	'db_name' => 'test',
|	'db_table_prefix' => 'zig_'
| );
|
*/
$DB_CONFIG['default'] = array(
	'db_host' => 'localhost',
	'db_user' => 'root',
	'db_pass' => 'root',
	'db_name' => 'test',
	'db_table_prefix' => '',
	'db_pconnect' => FALSE
);

// DO NOT EDIT THE LINE BELOW
config::set('DB_CONFIG', $DB_CONFIG);

/*
|--------------------------------------------------------------------------
| Database Driver
|--------------------------------------------------------------------------
|
| Change which database driver to use. If left blank, driver defaults to MySQL
|
| NOTE: At the moment, ZigPHP only supports MySQL so do not change the setting below. 
|
*/
config::set('DB_DRIVER', '');

// END of ZigPHP Database Config
// Filename: db.config.php 
// Location: root/application/config/