<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/5/26
 * Time: 21:22
 */

class Review extends CI_Controller {
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


        if(isset($_GET['filename'])&&file_exists(ATTACHMENTPATH.$_GET['filename'])){
            if(@strtolower(end(explode('.',$_GET['filename'])))=='pdf'){
                $filename = base_url('/file/'.$_GET['filename']);
            }else{
                $filename = 0;
                echo '<script>alert("文件格式不支持");</script>';
            }
        }else{
            $filename = 0;
            echo '<script>alert("文件不存在");</script>';
        }
        $this->_data['filename']=$filename;
        //echo $filename;
        $this->load->view('header',$this->_data);
        $this->load->view('viewer.html',$this->_data);
        $this->load->view('footer',$this->_data);
    }

}