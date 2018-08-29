<?php

/**
 * @Author: cuixudong123
 * @Date:   2018-08-07 19:52:37
 * @Last Modified by:   cuixudong123
 * @Last Modified time: 2018-08-12 19:51:51
 */

namespace App\Socket\Logic;

use EasySwoole\Core\Component\Di;


class Game
{ 	

	private static $RoomUserListPrefix = 'gameRoom_';//
	private static $RoomIncPrefix = 'room_';
	private static $RoomIncKey = 'gameVal';
	private static $EndTimeKeyName = 'endTime_';

    /**
     * 获取Redis连接实例
     * @return object Redis
     */
    public static function getRedis()
    {
        return Di::getInstance()->get('REDIS')->handler();
    }

    public static function addRoomInc($roomId, $value){
    	self::getRedis()->Hincrby(self::$RoomIncKey,self::$RoomIncPrefix.$roomId,$value);
    }
    public static function getRoomInc($roomId){
    	return self::getRedis()->Hget(self::$RoomIncKey, self::$RoomIncPrefix.$roomId);
    }
    public static function delRoomInc($roomId){
    	self::getRedis()->Hdel(self::$RoomIncKey, self::$RoomIncPrefix.$roomId);
    }
    public static function addRoomUserList($roomId, $uId, $time){
    	self::getRedis()->Zadd(self::$RoomUserListPrefix.$roomId,$time,$uId);
    }
    //
    public static function getRoomUserList($roomId, $uId){
    	// echo self::$RoomUserListPrefix.$roomId;
    	return self::getRedis()->Zscore(self::$RoomUserListPrefix.$roomId,$uId);
    }
    //order desc 
    public static function getRoomUserListAll($roomId){
    	return self::getRedis()->Zrevrange(self::$RoomUserListPrefix.$roomId,0,-1,'WITHSCORES');
    }
    //移除已参加的All
    public static function delRoomUserList($roomId){
    	self::getRedis()->Zremrangebyscore(self::$RoomUserListPrefix.$roomId, '-inf', '+inf');

    }
   	public static function setEndTime($roomId,$time){
    	self::getRedis()->hset(self::$RoomIncKey,self::$EndTimeKeyName.$roomId,$time);
    }
    public static function delEndTime($roomId){
    	self::getRedis()->Hdel(self::$RoomIncKey,self::$EndTimeKeyName.$roomId);
    }
    public static function getEndTime($roomId){
    	return self::getRedis()->Hget(self::$RoomIncKey,self::$EndTimeKeyName.$roomId);
    }



    public static function clearAll($roomId){
    	self::delRoomUserList($roomId);
		self::delRoomInc($roomId);
		self::delEndTime($roomId);
    }
}