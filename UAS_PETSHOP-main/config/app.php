<?php

define('APP_NAME', 'UAS Pet Shop');
define('APP_VERSION', '1.0.0');

if (!defined('BASE_URL')) {
    define('BASE_URL', "/index.php?url=auth/login");
}

define('UPLOAD_PATH', BASE_PATH . '/public/assets/images/products/');
define('UPLOAD_URL', BASE_URL . 'assets/images/products/');

define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', 1);

