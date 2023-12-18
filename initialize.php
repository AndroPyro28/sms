<?php
// $dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
// if(!defined('base_url')) define('base_url','http://localhost/sms/');
// if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
// if(!defined('dev_data')) define('dev_data',$dev_data);
// if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
// if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
// if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
// if(!defined('DB_NAME')) define('DB_NAME',"sms_db");
?>

<?php
$dev_data = array(
    'id' => '-1',
    'firstname' => 'Developer',
    'lastname' => '',
    'username' => 'dev_oretnom',
    'password' => '5da283a2d990e8d8512cf967df5bc0d0',
    'last_login' => '',
    'date_updated' => '',
    'date_added' => ''
);

if (!defined('base_url')) define('base_url', 'http://localhost/sms/');
if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
if (!defined('dev_data')) define('dev_data', $dev_data);

// Database 1 Configuration
if (!defined('DB_SERVER_1')) define('DB_SERVER_1', "localhost");
if (!defined('DB_USERNAME_1')) define('DB_USERNAME_1', "root");
if (!defined('DB_PASSWORD_1')) define('DB_PASSWORD_1', "");
if (!defined('DB_NAME_1')) define('DB_NAME_1', "sms_db");

// Database 2 Configuration
if (!defined('DB_SERVER_2')) define('DB_SERVER_2', "localhost");
if (!defined('DB_USERNAME_2')) define('DB_USERNAME_2', "root");
if (!defined('DB_PASSWORD_2')) define('DB_PASSWORD_2', "");
if (!defined('DB_NAME_2')) define('DB_NAME_2', "mediwise");
?>