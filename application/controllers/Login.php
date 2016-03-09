<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/24
 * Time: 21:35
 */

class login extends CI_Controller {
    public function __construct(){
        parent::__construct();
        
        $this->load->model('userinfomdl');
        $isLogin=$this->userinfomdl->isLogin();
        if($isLogin['status']){
                redirect(base_url('courses'));//
        }
    }
    public function index(){
        $this->load->view('login.html');
    }
    public function loginIn(){
        if(isset($_POST['name'])&&$_POST['pwd']&&isset($_POST['role'])){
            $email = $_POST['name'];
            $passwd = $_POST['pwd'];
            $type=$_POST['role'];
            $return = $this->_login($email,$passwd,$type);
        }else{
            $return['status']=false;
            $return['error_mess']='邮箱或密码为空';
        }
        echo json_encode($return);
    }
    /**登陆
     * @param $email
     * @param $passwd
     * @param $type
     * @return array
     */
    protected function _login($email,$passwd,$type){
        $return=array();
        if(empty($email)||empty($passwd)||empty($type)){
            $return['status']=false;
            $return['error_mess']='邮箱或密码为空';
        }else{
            return $this->userinfomdl->login($email,$passwd,$type);
        }
        return $return;
    }

    /**
     *退出登录
     */
    public function loginOut(){
        $this->userinfomdl->loginOut();
        redirect(base_url('login'));
    }

    /**
     * 登录
     * @return array
     */
//    public function loginIn(){
//        $return=array();
//        if(isset($_POST['type'])){
//            $email=$_POST['email'];
//            $passwd=$_POST['passwd'];
//            $type=$_POST['type'];
//            $return=$this->_login($email,$passwd,$type);
//        }
//        return json_encode($return);
//    }


}