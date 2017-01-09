<?php
/**
 * Created by PhpStorm.
 * User: GanZiB
 * Date: 16/12/28
 * Time: 下午3:46
 */

namespace Admin\Controller;


class UserTypeController extends AdminController {

    /**
     * 会员类型列表
     */
    public function index(){

        $list       =   M("UserType")->select(array('order'=>'sort'));
        if($list) {
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->meta_title = '会员管理';
        $this->display();
    }

    /**
     * 会员类型修改
     */
    public function edit()
    {
      $id = I('id');
      $map['user_type_id'] =  $id;
      empty($id) && $this->error('参数不能为空！');
      $data = M('UserType')->where($map)->find();

      $this->assign('userType',$data);
      $this->meta_title = '编辑行为';
      $this->display();

    }

    /**
     * 更新会员类型数据
     */
   public function update(){
       $id =  I('post.user_type_id',0);
       $data = I('post.');
       $UserType = M('UserType');
       if($id!=0){
           $res = $UserType->save($data);
           if(!$res){
               $this->error($UserType->getError());
           }else{
               $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
           }
       }else if($id==0){
           $res = $UserType->add($data);
           if(!$res){
               $this->error($UserType->getError());
           }else{
               $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
           }
       }
       $this->meta_title = '编辑行为';
       $this->display();
   }

   public function add(){
       $this->meta_title = '编辑行为';
       $this->display();
   }

}