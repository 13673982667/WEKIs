<?php

/**
 * @Author: cuixudong123
 * @Date:   2018-08-07 19:52:37
 * @Last Modified by:   cuixudong123
 * @Last Modified time: 2018-08-14 04:02:54
 */

namespace App\Socket\Logic;

use EasySwoole\Core\Component\Di;


class Cb
{ 	

	private static $Prefix = 'getCbTime_';
      /**
     * 获取Redis连接实例
     * @return object Redis
     */
    protected static function getRedis()
    {
        return Di::getInstance()->get('REDIS')->handler();
    }
    public static function set($key,$val,$time = null){
    	self::getRedis()->Set(self::$Prefix.$key,$val);
    	if(!is_null($time)){
    		self::setExpire($key,$time);
    	}
    }
    public static function get($key){
    	return self::getRedis()->Get(self::$Prefix.$key);
    }

    public static function setExpire($key,$time){
    	self::getRedis()->Expire(self::$Prefix.$key,$time);
    }
   	

}