<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;
class EdpController extends HomeController {

	//系统首页
    public function index(){

        $this->display('login');

    }

    //注册进入
    public function signup(){
            $this -> assign('type',EDP_REGISTER);
            $this->display('register');
    }

    //完成注册
    public function register(){
        if(IS_AJAX) {
            $mobile = I('post.mobile');
            $user = $this->userExist($mobile);
            $data['status'] = 1;
            if (!$user) {
                $pwd = I('post.password');
                $auth_code = I('post.authcode');
                $AuthCode = M('AuthCode');
                $db_auth_code = $AuthCode->where("name='%s' and type='%s' and is_valid= %d", array($mobile, EDP_REGISTER, IS_VALID))->find();
                $db_code = $db_auth_code['auth_code'];
                if ($auth_code == $db_code) {
                        $UserEdp = D('UserEdp');
                        $user_data = array('mobile' => $mobile,
                            'nick_name' => 'edp' . $mobile,
                            'real_name' => 'edp' . $mobile,
                            'password' => md5($pwd),
                            'user_type' => '暂无',
                            'last_ipaddr' => get_client_ip(),
                            'register_ip' => get_client_ip()
                        );
                        if ($UserEdp->create($user_data)) {
                            if ($UserEdp->add()) {
                            $code_data = array('auth_code_id' => $db_auth_code['auth_code_id'], 'is_valid' => IS_INVALID);
                            $AuthCode->save($code_data);
                                /* 更新登录信息 */
                                $user_edp = D('UserEdp')->where("mobile='%s'", $mobile)->find();
                                $user_data = array(
                                    'user_id'             => $user_edp['user_id'],
                                    'login_times'         => array('exp', '`login_times`+1'),
                                    'last_login_time' => NOW_TIME,
                                    'last_ipaddr'   => get_client_ip(1),
                                );
                                $Edp = M('UserEdp');
                                $Edp->save($user_data);
                                /* 记录登录SESSION和COOKIES */
                                $auth = array(
                                    'user_id'             => $user_edp['user_id'],
                                    'username'        => $user_edp['nickname'],
                                    'mobile'        => $user_edp['mobile']
                                );
                                session('user_edp_auth', $auth);
                                session('user_auth_edp_sign', data_auth_sign($auth));
                            }
                        } else {
                            exit($UserEdp->getError());
                        }
                } else {
                    $data['status'] = 0;
                    $data['info'] = '验证码错误或失效!';
                }
            } else {
                $data['status'] = 0;
                $data['info'] = '该手机号已注册';
            }
            $this->ajaxReturn($data);
        }else{
            $this->display('login');
        }
    }

    //找回密码
    public function findpwd(){
        if(IS_AJAX) {
            $mobile = I('post.mobile');
            $user = $this->userExist($mobile);
            $data['status'] = 1;
            if ($user) {
                $pwd = I('post.password');
                $auth_code = I('post.authcode');
                $AuthCode = M('AuthCode');
                $db_auth_code = $AuthCode->where("name='%s' and type='%s' and is_valid= %d", array($mobile, EDP_FINDPWD, IS_VALID))->find();
                $db_code = $db_auth_code['auth_code'];
                if ($auth_code == $db_code) {
                    $code_data = array('auth_code_id' => $db_auth_code['auth_code_id'], 'is_valid' => IS_INVALID);
                    $AuthCode->save($code_data);
                    /* 更新登录信息 */
                    $user_edp = D('UserEdp')->where("mobile='%s'", $mobile)->find();
                    $user_data = array(
                        'user_id'             => $user_edp['user_id'],
                        'password' => md5($pwd),
                        'login_times'         => array('exp', '`login_times`+1'),
                        'last_login_time' => NOW_TIME,
                        'last_ipaddr'   => get_client_ip(1),
                    );
                    $Edp = M('UserEdp');
                    $Edp->save($user_data);
                    /* 记录登录SESSION和COOKIES */
                    $auth = array(
                        'user_id'             => $user_edp['user_id'],
                        'username'        => $user_edp['nickname'],
                        'mobile'        => $user_edp['mobile']
                    );
                    session('user_edp_auth', $auth);
                    session('user_auth_edp_sign', data_auth_sign($auth));
                } else {
                    $data['status'] = 0;
                    $data['info'] = '验证码错误或失效!';
                }
            } else {
                $data['status'] = 0;
                $data['info'] = '该手机号未注册';
            }
            $this->ajaxReturn($data);
        }else{
            $this->display('login');
        }
    }
    //用户是否存在
    protected function userExist($mobile){
        $UserEdp = M('UserEdp');
        $user = $UserEdp->where("mobile='%s'", $mobile)->find();
        if ($user){
            return true;
        }
        return false;
    }

    public function editprofile(){
        if(IS_AJAX){
            $data['status'] = 1;
            $post = I('post.');
            $UserEdp = M('UserEdp');
            $post['user_id']=session('user_edp_auth')['user_id'];
            $post['status']=IS_VALID;
            $UserEdp->save($post);
            $this->ajaxReturn($data);
        }else{
            $this->display('login');
        }
    }

    public function logout(){
            session('user_edp_auth', null);
            session('user_auth_edp_sign', null);
            $this->display('login');
    }
    //注册进入
    public function login(){
        //判断是否已登录
        if (is_edp_login()){
            //判断是否完成个人资料
            $mobile = session('user_edp_auth')['mobile'];
            $UserAuth = M('UserEdp')->where("mobile='%s'", $mobile)->find();
            $status = $UserAuth['status'];
            if ($status == IS_INVALID){//未填写完整
                $this -> display('message');//个人资料
            }else{
                $this -> assign('edpuser',$UserAuth);
                $this -> display('personal');//个人中心
            }
        }else{
            $this -> display('login');//登录
        }
//        session('user_edp_auth', null);
//        session('user_auth_edp_sign', null);
//        $this->display('login');
    }
    //由于微信缓存，首次登录的刷新必须在页面跳转到不用的uri
    public function logged(){
        //判断是否已登录
        if (is_edp_login()){
            //判断是否完成个人资料
            $mobile = session('user_edp_auth')['mobile'];
            $UserAuth = M('UserEdp')->where("mobile='%s'", $mobile)->find();
            $status = $UserAuth['status'];
            if ($status == IS_INVALID){//未填写完整
                $this -> display('message');//个人资料
            }else{
                $this -> assign('edpuser',$UserAuth);
                $this -> display('personal');//个人中心
            }
        }else{
            $this -> display('login');//登录
        }
    }


    //登录进入
    public function signin(){
        if (IS_AJAX){
            $data  = array('status' => 1);
            $pwd = I('post.password');
            $mobile = I('post.mobile');
            if($this -> userExist($mobile)){
                $UserEdp = D('UserEdp')->where("mobile='%s'", $mobile)->find();
                if ($UserEdp['password'] == md5($pwd)){
                    /* 更新登录信息 */
                    $user_data = array(
                        'user_id'             => $UserEdp['user_id'],
                        'login_times'         => array('exp', '`login_times`+1'),
                        'last_login_time' => NOW_TIME,
                        'last_ipaddr'   => get_client_ip(1)
                    );
                    $Edp = M('UserEdp');
                    $Edp->save($user_data);
                    /* 记录登录SESSION和COOKIES */
                    $auth = array(
                        'user_id'             => $UserEdp['user_id'],
                        'username'        => $UserEdp['nickname'],
                        'mobile'        => $UserEdp['mobile'],
                    );
                    session('user_edp_auth', $auth);
                    session('user_auth_edp_sign', data_auth_sign($auth));
                }else{
                    $data['status'] = 0;
                    $data['info'] = '密码错误';
                }
            }else{
                $data['status'] = 0;
                $data['info'] = '该手机未注册';
            }
            $this->ajaxReturn($data);
        }else{
            $this->display('login');//登录
        }
    }


    //忘记密码
    public function forget(){
        $this -> assign('type',EDP_FINDPWD);
        $this->display();
    }

    //个人中心
    public function personal(){

        $this->display();
    }
    //填写个人资料
    public function message(){

        $this->display();
    }

    /**
     * 注册发送短信
     */
    public function sendMsg(){
        if(IS_AJAX) {// AJAX提交
            $data  = array('status' => 1);
            $mobile = I('post.mobile');
            $type = I('post.type');
            $user = M('UserEdp')->field(true)->find($mobile);
            if(!$user) {
                $array = $this -> sendAuthCode($mobile,$type);
                if(!$array){
                    $data['status'] = 0;
                }
            }else{
                $data['info'] = '该手机号已注册';
            }
            $this->ajaxReturn($data);
        }
    }

    protected function sendAuthCode($mobile,$type){//发送验证码
        $code = $this -> getAuthCode($mobile,$type);

        $text="【重庆大学EDP】您的验证码是".$code;
        $post_data=array('text'=>$text,'mobile'=>$mobile,'apikey' =>'9cdc4b0314171199079fd6c09d8eb34a');
        $url = 'https://sms.yunpian.com/v2/sms/single_send.json';
        $ch = curl_init();
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /* 设置通信方式*/
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $data = curl_exec($ch);
        curl_close($ch);
        //解析返回结果（json格式字符串）
        $json_data =json_decode($data,true);
        if($json_data['code'] == 0){
            $AuthCodeLog = D('AuthCodeLog');
            $log_data = array('auth_code_mobile' => $mobile,
                'auth_code_type' => $type,
                'auth_code' => $code ,
                'auth_code_ipaddr' => get_client_ip());
            if($AuthCodeLog -> create($log_data)) {
                $AuthCodeLog->add();
            }else{
                exit($AuthCodeLog->getError());
            }

            return true;

        }else{
            return false;
        }

    }


    protected function getAuthCode($name,$type){//判断验证码
        $AuthCode = M('AuthCode');
        $last_code = $AuthCode -> where("name='%s' and type='%s' and is_valid= %d",array($name,$type,IS_VALID)) -> find();
        if(empty($last_code)){
            return $this -> generateAuthCode($name,$type);
        }
        //TODO 判断当前时间与创建时间超过30分钟时指定时间验证码失效并重新生成验证码
        return $last_code;
    }

    protected function generateAuthCode($name,$type){//生成验证码
        $AuthCode = D('AuthCode');
        $code = rand(1000,9999);
        $data = array('name' => $name, 'type' => $type,'auth_code' => $code);
        if($AuthCode -> create($data)) {
            if ($AuthCode->add()){
                return $code;
            }
        }else{
            exit($AuthCode->getError());
        }
    }



    protected function test(){
        $ch = curl_init();
        $apikey = "9cdc4b0314171199079fd6c09d8eb34a";
        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/user/get.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('apikey' => $apikey)));
        $json_data = curl_exec($ch);
        curl_close($ch);
        $array = json_decode($json_data,true);
        $this ->assign("info",$array);
        $this->display();
    }

}