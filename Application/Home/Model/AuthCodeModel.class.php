<?php
/**
 * Created by PhpStorm.
 * User: cuiyingjia
 * Date: 2016/12/26
 * Time: 下午5:40
 */

namespace Home\Model;
use Think\Model;

/**
 * 验证码模型
 */
class AuthCodeModel extends Model{

    protected $_auto = array (
        array('is_valid',IS_VALID),  // 新增的时候把status字段设置为1
        array('request_time','time',1,'function') // 对request_time字段在新建的时候写入当前时间戳
    );

}
