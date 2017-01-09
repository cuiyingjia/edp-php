<?php
/**
 * Created by PhpStorm.
 * User: cuiyingjia
 * Date: 2016/12/26
 * Time: 下午5:42
 */

namespace Home\Model;
use Think\Model;

/**
 * Class AuthCodeLogModel
 * @package Home\Model
 * 验证码日志模型
 */
class AuthCodeLogModel extends Model
{

    protected $_auto = array (
        array('auth_code_time','time',1,'function') // 对auth_code_time字段在新建的时候写入当前时间戳
    );


}