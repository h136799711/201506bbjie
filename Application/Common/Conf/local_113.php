<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved.
 */
// 必须定义
define('UC_AUTH_KEY', '#p}{v6xDM-@l(.qBet&1y]o4m+WudQ9TN<?nc|Yz'); //加密KEY
define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_DB_DSN', 'mysql://root:1@192.168.0.100:3306/boye_bbj'); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', 'itboye_'); // 数据表前缀，使用Model方式调用API必须配置此项
define("ITBOYE_CDN","http://192.168.0.100/github/201506bbjie/cdn");

return array(


    // 伪静态配置
    'URL_HTML_SUFFIX'=>'shtml'	,
    // 路由配置
    'URL_MODEL'                 =>  1, // 如果你的环境不支持PATHINFO 请设置为3
    // 数据库配置
    'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  '192.168.31.112',//rdsrrbifmrrbifm.mysql.rds.aliyuncs.com
    'DB_NAME'                   =>  'boye_bbj', //boye_ceping
    'DB_USER'                   =>  'root',//boye
    'DB_PWD'                    =>  '1',//bo-ye2015BO-YE
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'itboye_',



    //调试
     'LOG_DB_CONFIG'=>array(
        'dsn'=>UC_DB_DSN //本地日志数据库
    ),



);



