<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/5/26
 * Time: 21:22
 */

class TemplateList extends CI_Controller {
    /**
     * 传给view的数据
     * @var
     */
    private $_data;
    /**
     * 用户个人信息
     * @var
     */
    private $userinfo;

    public function __construct(){
        parent::__construct();
        $this->load->library('filelib');
        $this->load->model(array('userinfomdl','coursesmdl'));
        $isLogin=$this->userinfomdl->isLogin();
        if(!$isLogin['status']){
            redirect(base_url('login'));

        }
        $this->userinfo=$this->userinfomdl->getUserInfo();
        $this->_data['userinfo']=$this->userinfo;
    }
    public function index(){
        if($this->userinfo['type']=='teacher'){
            $this->load->view('header',$this->_data);
            $this->load->view('template.html',$this->_data);
            $this->load->view('footer',$this->_data);
        }else{
            echo "<script>alert('没有权限');history.go(-1);</script>";
        }
    }

}