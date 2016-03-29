<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/3/12
 * Time: 4:59
 */

class Teacher extends CI_Controller {
    private $_data=array();
    private $_userinfo=array();
    public function __construct(){
        parent::__construct();
        $this->load->model('userinfomdl');
        $isLogin=$this->userinfomdl->isLogin();
        if(!$isLogin['status']){
            redirect(base_url('login'));
        }
        $this->_userinfo=$this->userinfomdl->getUserInfo();
        $this->_data['userinfo']=$this->_userinfo;
    }

    /**
     * 展示教师信息页
     */
    public function index(){
        $this->load->view('header',$this->_data);
        $this->load->view('teacher.html');
        $this->load->view('footer');
    }
    public function api(){
        $return=array();
        if(isset($_POST['action'])){
            $action=$_POST['action'];
            switch($action){
                case "updateUserInfo":
                    $return = $this->udpateUserInfo();
                    break;
                case "changePasswd":
                   $return= $this->change_passwd();
                    break;
                case "getUserInfo":
                    $return['status']=true;
                    $return['result']=$this->getUserInfo();
                    break;
                default:
                    $return['status']=false;
                    $return['error_mess']='未知操作';
                    break;
            }
            $email=$this->_userinfo['email'];
            $type='teacher';
            $this->userinfomdl->updateSession($email,$type);
            echo json_encode($return);
        }
    }

    /**
     * 修改密码
     */
    public function change_passwd(){
        $return =array();
//        $username=isset($_POST['username'])?$_POST['username']:null;
        $passwd=isset($_POST['old_passwd'])?$_POST['old_passwd']:null;
        $re_passwd=isset($_POST['new_passwd'])?$_POST['new_passwd']:null;
        if($passwd==$re_passwd){
            $return['status']=false;
            $return['error_mess']='新密码与原始密码相同';
            exit(json_encode($return));
        }
        $type='teacher';
        $email=$this->_userinfo['email'];

        $return=$this->userinfomdl->login($email,$passwd,$type);
        if(!$return['status']){
            exit(json_encode($return));
        }
        $userinfo=array(
            'passwd'=>sha1($re_passwd)
        );
        $return = $this->userinfomdl->updateInfo($userinfo,$type,$email);
        return $return;
    }
    public function upload_image(){
        $return=array();
        if(isset($_FILES['file'])){
            $this->load->library('filelib');
            $file=$_FILES['file'];
            $updateRes=$this->filelib->upload($file);
            if($updateRes['status']){
                $image_name=$updateRes['final_filename'];
                $email=$this->_userinfo['email'];
                $type='teacher';
                $userinfo=array(
                    'image_url'=>$image_name
                );
                $this->userinfomdl->updateInfo($userinfo,$type,$email);
            }else{
                $return['status']=false;
                $return['error_mess']=$updateRes['error_mess'];
            }
        }else{
            $return['status']=false;
            $return['error_mess']='未上传文件';
        }
        echo json_encode($return);
    }
    public function udpateUserInfo(){
        $username=isset($_POST['username'])?$_POST['username']:null;
        $code=isset($_POST['code'])?$_POST['code']:null;
        $userinfo=array(
            'name'=>$username,
            'teacher_id'=>$code
        );
        $type='teacher';
        $email=$this->_userinfo['email'];
        $return = $this->userinfomdl->updateInfo($userinfo,$type,$email);
        return $return;
    }
    public function getUserInfo(){
        return $this->userinfomdl->getUserInfo();
    }
}



