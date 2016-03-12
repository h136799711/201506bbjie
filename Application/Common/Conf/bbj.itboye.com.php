<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/12/16
 * Time: 18:27
 */

// 必须定义
define('UC_AUTH_KEY', '#p}{v6xDM-@l(.qBet&1y]o4m+WudQ9TN<?nc|Yz'); //加密KEY
define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_DB_DSN', 'mysql://root:fdc656d3e7@localhost:3306/boye_bbj'); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', 'itboye_'); // 数据表前缀，使用Model方式调用API必须配置此项

define("ITBOYE_CDN","http://bbj.cdn.itboye.com");

return array(

    'SITE_URL'=>'http://bbj.itboye.com',

    'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  'localhost',//rdsrrbifmrrbifm.mysql.rds.aliyuncs.com
    'DB_NAME'                   =>  'boye_bbj', //boye_ceping
    'DB_USER'                   =>  'root',//boye
    'DB_PWD'                    =>  'fdc656d3e7',//bo-ye2015BO-YE
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'itboye_',

    //调试
    'LOG_DB_CONFIG'=>array(
        'dsn'=>UC_DB_DSN //本地日志数据库
    ),
);