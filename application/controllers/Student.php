<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/3/12
 * Time: 4:59
 */

class Student extends CI_Controller {
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
}