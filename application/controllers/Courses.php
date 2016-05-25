<?php
/**
 * Created by PhpStorm.
 * User:
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

    public function delete_courses(){
        if(isset($_GET['course_id'])){
            $course_id=$_GET['course_id'];
            $this->coursesmdl->delete(array('id'=>$course_id));

        }
        redirect(base_url('courses'));
    }
    public function details(){
        if(isset($_GET['course_id'])){
            $this->_data['lesson_id']=$_GET['course_id'];
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
            if($this->userinfo['type'] == 'student'){
                $this->_data['jurisdiction'] = $this->coursesmdl->getStudentJurisdiction($this->_data['lesson_id'],$this->userinfo['id']);
            }

            $this->load->model('jobmdl');
            $result =$this->jobmdl->get_lesson_job($_GET['course_id']);
            $this->_data['attachment_list'] =$this->coursesmdl->getAttachment($this->_data['lesson_id']);
            $this->_data['material_list'] = $this->coursesmdl->getMaterial($this->_data['lesson_id']);
//            var_dump($result);
            $this->_data['student_list'] = $this->coursesmdl->getStudent($this->_data['lesson_id']);
//            var_dump($this->_data['student_list']);
            if($result['status']){
                $this->_data['job_list']=$result['result'];
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
    public function getStudent(){
        $lesson_id = isset($_POST['lesson_id'])?$_POST['lesson_id']:0;
        if($lesson_id==0){
            $return['status']=false;
            $return['error_mess']='选择课程';
        }else{

        }
    }
    public function uploadFile(){
        $this->load->model('table_mdl');
        if(isset($_GET['type'])){
            $type=$_GET['type'];
            $file=$_FILES['file'];
            $uploadRes=$this->filelib->upload($file);
            $filename = $uploadRes['final_filename'];
            $fileUrl = $uploadRes['filename'];
            $lesson_id = $_GET['lesson_id'];
            $data=array(
                'filename'=>$fileUrl,
                'file_url'=>$filename,
                'lesson_id'=>$lesson_id
            );
            switch($type){
                case 'attachment':
                    $this->table_mdl->setTable('le_attachment');
                    $this->table_mdl->add($data);
                    break;
                case 'material':
                    $this->table_mdl->setTable('le_material');
                    $this->table_mdl->add($data);
                    break;
                default:
                    break;
            }
        }else{
            $return['status']=false;
            $return['error_mess']='上传类别错误';
        }
        redirect(base_url('courses/details?course_id='.$_GET['lesson_id']));
    }
    public function delete_file(){
        $type = isset($_GET['type'])?$_GET['type']:false;
        $id = isset($_GET['id'])?$_GET['id']:flase;
        $lesson_id = $_GET['lesson_id'];
        $this->coursesmdl->delete_file($id,$type);
        redirect(base_url('courses/details?course_id='.$_GET['lesson_id']));
    }
    public function change_student_status(){
        $return = array();
        $student_id = isset($_POST['student_id'])?$_POST['student_id']:0;
        $status = isset($_POST['status'])?$_POST['status']:0;
        $lesson_id = isset($_POST['lesson_id'])?$_POST['lesson_id']:0;
        if($student_id===0||$status===0||$lesson_id===0){
            $return['status'] = false;
            $return['error_mess']='参数不正确';
        }else{
            $return = $this->coursesmdl->change_student_status($lesson_id,$student_id,$status);
        }
        echo json_encode($return);
    }

}