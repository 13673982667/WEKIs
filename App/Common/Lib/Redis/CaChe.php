<?php

/**
 * @Author: cuixudong123
 * @Date:   2018-08-07 19:52:37
 * @Last Modified by:   cuixudong123
 * @Last Modified time: 2018-08-11 00:53:52
 */

namespace App\Common\Lib\Redis;

use EasySwoole\Core\Component\Di;


class CaChe
{ 
	private static $CaChePrefix = 'cache_';

    /**
     * 获取Redis连接实例
     * @return object Redis
     */
    protected static function getRedis()
    {
        return Di::getInstance()->get('REDIS')->handler();
    }
    public static function set($key,$val,$time = null){
    	self::getRedis()->Set(self::$CaChePrefix.$key,$val);
    	if(!is_null($time)){
    		self::setExpire($key,$time);
    	}
    }
    public static function get($key){
    	return self::getRedis()->Get(self::$CaChePrefix.$key);
    }

    public static function setExpire($key,$time){
    	self::getRedis()->Expire(self::$CaChePrefix.$key,$time);
    }
   	
}