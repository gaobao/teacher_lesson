<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/24
 * Time: 20:49
 */

class Courses extends CI_Controller {
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
        $this->load->model('userinfomdl');
        if(!$this->userinfomdl->isLogin()){
         redirect(base_url('login'));
            $this->userinfo=$this->userinfomdl->getUserInfo();
        }
    }
    public function index(){

    }

    /**
     * 创建课程
     * @return array
     */
    protected function create_courses(){
        $return=array();
        if(isset($_POST['lesson_name'])&&isset($_POST['lesson_desc'])){
            $lesson_name=$_POST['lesson_name'];
//            $lesson_code=$_POST['lesson_code'];
            $lesson_desc=$_POST['lesson_desc'];
        }
        return $return;
    }
    protected function list_courses(){

    }
    protected function delete_courses(){

    }

}