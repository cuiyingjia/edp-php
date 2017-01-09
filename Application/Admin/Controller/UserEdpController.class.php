<?php
/**
 * Created by PhpStorm.
 * User: GanZiB
 * Date: 16/12/27
 * Time: 下午5:01
 */

namespace Admin\Controller;


class UserEdpController extends AdminController {

    /**
     * 会员管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){

        $list  =   M("UserEdp")->select();
        $UserType = M("UserType")->getField('type_value,mean');
        int_to_string($list,array('user_type'=>$UserType));
        if($list) {

            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->meta_title = '会员管理';
        $this->display();
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function edit(){
        $id = I('id','');
        $map['user_id'] =  $id;
        empty($id) && $this->error('参数不能为空！');
        $data = M('UserEdp')->where($map)->find();
        $type = M('UserType')->select();

        $this->assign('userEdp',$data);
        $this->assign('userTypes',$type);
        $this->meta_title = '编辑行为';
        $this->display();
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){

       $id =  I('post.id',0);
       $typeValue = I('post.typeValue');
       if($id!=0){
           $map['user_id'] =  $id;
           $data = M('UserEdp')->where($map)->find();
           $UserEdpTemp = M('UserEdp')->create($data);
           if($typeValue!=''){
               $UserEdpTemp['user_type']=$typeValue;
           }
           $UserEdp = M('UserEdp');
           $res = $UserEdp ->where($map)->save($UserEdpTemp);
           if(!$res){
               $this->error($UserEdp->getError());
           }else{
               $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
           }
       }else{
           $this->error('参数不能为空！');
       }

        $this->meta_title = '编辑行为';
        $this->display();

    }

}