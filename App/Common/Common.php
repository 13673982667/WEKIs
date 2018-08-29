<?php

/**
 * @Author: cuixudong123
 * @Date:   2018-08-07 03:25:24
 * @Last Modified by:   cuixudong123
 * @Last Modified time: 2018-08-13 03:22:55
 */

/**
 * 打印数据
 * @param  [type] $var    [description]
 * @param  int  是否结束
 */
function p($var, $is_die = 0) {
	if (is_array($var)) {
		echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#f5f5f5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>" . print_r($var, true) . "</pre>";
	} else {
		var_dump($var);
	}
	if ($is_die == 1) {
		exit;
	}
}
function I($value){
	if(isset($_REQUEST[$value])){
		return $_REQUEST[$value];
	}else{
		return false;
	}
}
function getMicrotime(){
	$arr = explode(' ',microtime());
	$str = floatval($arr[0]) + floatval($arr[1]);
	return (float) sprintf('%.0f',$str * 1000);
}
/**
 * 将base64转图片并保存
 * @param $base64_image_content string base64编码
 * @param $url
 */
function uploadbase64($base64_image_content, $url = '') {
	//匹配出图片的格式
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {

		$type = $result[2];
		$new_file = './App/static/uploads/images/' . date('Ymd', time());
		$new_file = empty($url) ? $new_file : $url;
		$filepath = '';
		$pathArr = explode('/', $new_file);
		foreach ($pathArr as $k => $v) {
			$filepath .= $v . '/';
			if (!is_dir($filepath)) {
				mkdir($filepath);
			}
		}
		$name = time();
		$new_file = $filepath . $name . ".{$type}";
		if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
			return $new_file;
		} else {
			return false;
		}
	}
}
//删除链接对应的文件
function deleteUrl($url) {
	//将http://192.168.0.110/  这样的给提取出来
	preg_match('/http[s]?:\/\/[^\/]+\/(.*)/i', $url, $arr);
	if (count($arr) > 1) {
		if (file_exists($arr[1])) {
			@unlink($arr[1]);
		}
	}

}