<?php
/**
 * Created by PhpStorm.
 * User: cuiyingjia
 * Date: 2016/12/26
 * Time: 下午5:44
 */
namespace Home\Model;
use Think\Model;

/**
 * edp用户模型
 */
class UserEdpModel extends Model{

    protected $_auto = array (
        array('reg_time','time',1,'function') // 对request_time字段在新建的时候写入当前时间戳
    );


    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_edp_auth', null);
        session('user_auth_edp_sign', null);
    }


}
