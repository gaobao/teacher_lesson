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
        $this->load->model(array('userinfomdl','coursesmdl'));
        $isLogin=$this->userinfomdl->isLogin();
        if(!$isLogin['status']){
         redirect(base_url('login'));

        }
        $this->userinfo=$this->userinfomdl->getUserInfo();
    }
    public function index(){
        $this->load->view('teacher_index.html');
//        var_dump($this->userinfo);
    }

    /**
     * 创建课程
     * @return array
     */
    public function create_courses(){
        $return=array();
        if($this->userinfo['type']=='teacher'){
            if(isset($_POST['lesson_name'])&&isset($_POST['lesson_desc'])){
                $lesson_name=$_POST['lesson_name'];
                $lesson_desc=$_POST['lesson_desc'];
                $teacher_id=$this->userinfo['id'];
                $return=$this->coursemdl->create($lesson_name,$lesson_desc,$teacher_id);
            }else{
                $return['status']=false;
                $return['error_mess']='提交数据错误';
            }
        }else{
            $return['status']=false;
            $return['error_mess']='没有权限';
        }

        echo json_encode($return);
    }
    public function list_courses(){
        $return=array();
        $type=$this->userinfo['type'];
        if($type=='teacher'){
            $teacher_id=isset($_POST['teacher_id'])?$_POST['teacher_id']:0;
            $where=array();

        }elseif($type=='student'){

        }else{

        }
        $limit=10;

        $offset=$this->uri->segment(3)?$this->uri->segment(3):0;

    }
    public function delete_courses(){

    }

}