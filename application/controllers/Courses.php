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
        $this->_data['userinfo']=$this->userinfo;
    }
    public function index(){
        if($this->userinfo['type']=='teacher'){
            $where=array('teacher_id'=>$this->userinfo['id']);
            $course=$this->coursesmdl->getCourses($where);
            $this->_data['course']=$course['result'];
            $this->load->view('header',$this->_data);
            $this->load->view('teacher_index.html',$this->_data);
            $this->load->view('footer');
//        var_dump($this->userinfo);
        }else{
            $student_id=$this->userinfo['id'];
            $student_id;
            $course=$this->coursesmdl->getStudentCourses($student_id);

            $this->_data['course']=$course['result'];
            $this->load->view('header',$this->_data);
            $this->load->view('student_index.html',$this->_data);
            $this->load->view('footer');
        }

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
                $return=$this->coursesmdl->create($lesson_name,$lesson_desc,$teacher_id);
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
        if(isset($_GET['course_id'])){
            $course_id=$_GET['course_id'];
            $this->coursesmdl->delete(array('id'=>$course_id));

        }
        redirect(base_url('courses'));
    }
    public function details(){
        if(isset($_GET['course_id'])){
            $where=array('id'=>$_GET['course_id']);
            $course=$this->coursesmdl->getCourses($where);
//            var_dump($course);
            if($course['status']&&!empty($course['result']['0'])){
                $this->_data['course']=$course['result']['0'];
            }else{
                $this->_data['course']=array(
                    'lesson_code'=>'该课程不存在'
                );
            }

        }else{

        }
        $this->load->view('header',$this->_data);
        $this->load->view('details.html',$this->_data);
        $this->load->view('footer');
    }
    public function select_course(){
        $return = array();
        $code=isset($_POST['lesson_code'])?strtoupper($_POST['lesson_code']):null;
        if($code){
            $where=array('lesson_code'=>$code);
            $course=$this->coursesmdl->getCourses($where);
            if($course['status']&&!empty($course['result']['0'])){
                $lesson_id=$course['result']['0']['id'];
                $student_id=$this->userinfo['id'];
                $status='wait';
                $data=array(
                    'lesson_id'=>$lesson_id,
                    'student_id'=>$student_id,
                    'status'=>$status
                );
                $return=$this->coursesmdl->createCourseRecord($data);
            }else{
                $return['status']=false;
                $return['error_mess']='找不到课程';
            }
        }else{
            $return['status']=false;
            $return['error_mess']='请输入课程编码';
        }
        echo json_encode($return);
    }

}