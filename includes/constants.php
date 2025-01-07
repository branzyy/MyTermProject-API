<?php
date_default_timezone_set("AFRICA/Nairobi");

    //Creating constants
    define('DBTYPE', 'PDO');
    define('HOSTNAME', '127.0.0.1');
    define('DBPORT', '3306');
    define('HOSTUSER', 'root');
    define('HOSTPASS', 'alex');
    define('DBNAME', 'api_exempt');

    $protocol = isset($_SERVER['HTTPS']) && 
    $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';

    $conf['ver_code_time'] = date("Y-m-d H:i:s", strtotime("+ 24hours"));
    $conf['ver_code_timeout'] = date("Y-m-d H:i:s", strtotime("- 24hours"));
    $conf['verification_code'] = rand(100000,999999);
    $conf['pass_length_min_limit'] = 6;
    $conf['site_initials'] = "Exempt";
    $conf['site_url'] = "$base_url". DBNAME;
    // (@strathmore.edu, @gmail.com, @yahoo.com, @mada.co.ke) and not (@yanky.net)
    $conf['valid_domains'] = ["strathmore.edu", "gmail.com", "yahoo.com", "mada.co.ke", "outlook.com"];

    // SMTP authentication
    $conf['smtp_auth'] = 'bbitalex@gmail.com';
    $conf['smtp_pass'] = 'vddo wjwf upzj qymf';
    $conf['setFrom'] = $conf['site_initials'] .'@gmail.com';