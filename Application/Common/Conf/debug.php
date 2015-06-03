<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */
 
defined("BOYE_SYS_NAME") or die("未定义");

return  array(
    'LOG_TYPE'              =>  'Db',
    'LOG_RECORD'            =>  true,  // 进行日志记录
    'LOG_EXCEPTION_RECORD'  =>  true,    // 是否记录异常信息日志
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',//,WARN,NOTIC,INFO,DEBUG,SQL',  // 允许记录的日志级别
    'DB_FIELDS_CACHE'       =>  false, // 字段缓存信息
    'DB_SQL_LOG'            =>  true, // 记录SQL信息
    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_STRIP_SPACE'      =>  false,       // 是否去除模板文件里面的html空格与换行
    'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
    'URL_CASE_INSENSITIVE'  =>  false,  // URL区分大小写
    'SHOW_PAGE_TRACE'           =>  false, //显示调试信息
     // 数据库配置
     
//  'DB_TYPE'                   =>  'mysql',
//  'DB_HOST'                   =>  '127.0.0.1',//rdsrrbifmrrbifm.mysql.rds.aliyuncs.com
//  'DB_NAME'                   =>  'boye_2015_05_26_10_15_13', //boye_ceping
//  'DB_USER'                   =>  'root',//boye
//  'DB_PWD'                    =>  '1',//bo-ye2015BO-YE
//  'DB_PORT'                   =>  '3306',
//  'DB_PREFIX'                 =>  'itboye_',
);