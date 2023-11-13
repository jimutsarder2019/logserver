<?php
/*
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);*/

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

if(isset($config['components']['db']) && !empty($config['components']['db'])){
	$dsn_arr = explode("dbname=", @$config['components']['db']['dsn']);
	$host_arr = explode("host=", @$config['components']['db']['dsn']);
	$host_arr2 = explode(";", @$host_arr[1]);
	if(isset($dsn_arr[1], $host_arr2[0]) && $dsn_arr[1] && $host_arr2[0]){
		$servername = $host_arr2[0];
		$username = @$config['components']['db']['username'];
		$password = @$config['components']['db']['password'];
		$dbname = @$dsn_arr[1];

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		  die("Connection failed: " . $conn->connect_error);
		}

		// sql to create user table
		$create_table_sql_list = [
			
			"CREATE TABLE IF NOT EXISTS `user` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) DEFAULT NULL,
			  `mobile` varchar(20) DEFAULT NULL,
			  `username` varchar(50) NOT NULL,
			  `password` varchar(50) NOT NULL,
			  `authKey` varchar(100) DEFAULT NULL,
			  `accessToken` varchar(50) DEFAULT NULL,
			  `role` tinyint(4) NOT NULL DEFAULT 2,
			  `status` tinyint(4) NOT NULL DEFAULT 0,
			  `date` datetime NOT NULL,
			   PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
			
			"CREATE TABLE IF NOT EXISTS `login_history` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `session_id` varchar(100) NOT NULL,
			  `ip` varchar(30) NOT NULL,
			  `username` varchar(100) NOT NULL,
			  `checkin` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
			
			"CREATE TABLE IF NOT EXISTS `report_backup` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `report_type` varchar(20) NOT NULL,
			  `from_date` varchar(50) NOT NULL,
			  `to_date` varchar(50) NOT NULL,
			  `match1` mediumtext NOT NULL,
			  `match2` mediumtext NOT NULL,
			  `match_type` varchar(20) NOT NULL,
			  `file_name` varchar(500) NOT NULL,
			  `status` tinyint(4) NOT NULL DEFAULT 0,
			  `process_data` int(20) DEFAULT NULL,
			  `total_possible_data` int(20) DEFAULT NULL,
			  `total_possible_size` varchar(50) DEFAULT NULL,
			  `date` date NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
			
			"CREATE TABLE IF NOT EXISTS `router` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) NOT NULL,
			  `identity` varchar(50) NOT NULL,
			  `type` varchar(50) NOT NULL,
			  `ip` varchar(30) NOT NULL,
			  `api_port` varchar(20) NOT NULL,
			  `api_username` varchar(50) NOT NULL,
			  `api_password` varchar(50) NOT NULL,
			  `status` varchar(20) NOT NULL,
			  `ipv6` varchar(20) NOT NULL,
			  `connection` varchar(50) DEFAULT NULL,
			  `date` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
			
			"CREATE TABLE IF NOT EXISTS `settings` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `company_name` varchar(100) DEFAULT NULL,
			  `license_number` varchar(50) DEFAULT NULL,
			  `company_address` varchar(100) DEFAULT NULL,
			  `company_phone` varchar(20) DEFAULT NULL,
			  `login_logo` varchar(100) DEFAULT NULL,
			  `user_logo` varchar(100) DEFAULT NULL,
			  `favicon` varchar(100) DEFAULT NULL,
			  `email_username` varchar(100) DEFAULT NULL,
			  `email_password` varchar(100) DEFAULT NULL,
			  `email_port` varchar(100) DEFAULT NULL,
			  `email_smtp_secure` varchar(100) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
			
			"INSERT IGNORE INTO `user` (`id`, `name`, `mobile`, `username`, `password`, `authKey`, `accessToken`, `role`, `status`, `date`) VALUES
            (1, 'admin', '+8801737294267', 'admin', 'admin123', 'engrahuldeb@gmail.com', NULL, 1, 1, '2023-07-04 12:04:53')",
			
			"INSERT IGNORE INTO `settings` (`id`, `company_name`, `license_number`, `company_address`, `company_phone`, `login_logo`, `user_logo`, `favicon`, `email_username`, `email_password`, `email_port`, `email_smtp_secure`) VALUES
            (1, 'LogServer', '20231112', 'Khulna', NULL, NULL, NULL, NULL, 'demo@gmail.com', 'demo', '587', 'tls')"
		];

		foreach($create_table_sql_list as $sql){
			if ($conn->query($sql) === TRUE) {
			  //echo "Table MyGuests created successfully";
			} else {
			  echo "Error creating table: " . $conn->error;
			  die;
			}
		}
		$conn->close();
	}
}

(new yii\web\Application($config))->run();
