<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/24
 * Time: 21:39
 */

class Register extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('userinfomdl');
    }
    public function index(){
        $this->load->view('register.html');
    }
    public function register_in(){
        $return=array();
        if(isset($_POST['type'])){
            $email=$_POST['email'];
            $passwd=$_POST['passwd'];
            $re_passwd=$_POST['re_passwd'];
            $type=$_POST['type'];
            $return=$this->_register($email,$passwd,$re_passwd,$type);
        }
        echo  json_encode($return);
    }
    /**
     * 注册
     * @param $email
     * @param $passwd
     * @param $re_passwd
     * @param $type
     * @return mixed
     */
    protected function _register($email,$passwd,$re_passwd,$type){
        $reutrn=array();
        if(empty($email)||empty($passwd)||empty($type)||empty($re_passwd)){
            $return['status']=false;
            $return['error_mess']='邮箱或密码为空';
        }else{
            $return=$this->userinfomdl->register($email,$passwd,$re_passwd,$type);
        }
        return $return;
    }
}