<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/1
 * Time: 14:50
 */

class Job extends CI_Controller{
    public $_return = array();
    public $_userinfo = array();
    public $_data;
    public function __construct(){
        parent::__construct();
    	$this->load->model(array('userinfomdl','jobmdl','jobrecordmdl'));
        $this->load->library('filelib');
        $isLogin=$this->userinfomdl->isLogin();
    	if(!$isLogin['status']){
    		$this->_return['status']=false;
    		$this->_return['error_mess']='用户未登录';
    		exit(json_encode($this->_return));
    	}
    	$this->_userinfo=$this->userinfomdl->getUserInfo();
        $this->_data['userinfo']=$this->_userinfo;
    }
    public function index(){
        if(isset($_GET['job_id'])){
            $job_id = $_GET['job_id'];
            $this->_data['job_id']=$job_id;
            if($this->_userinfo['type']=='teacher'){
                $where = array(
                    'le_job.id'=>$job_id
                );
            }else{
                $where = array(
                    'le_job_record.student_id'=>$this->_userinfo['id'],
                    'le_job.id'=>$job_id
                );
            }

            $job = $this->jobmdl->getJobList(array(
                'le_job.id'=>$job_id
            ));
//            var_dump($job);
            $this->_data['job']=$job['result'][0];
            if($this->_userinfo['type']=='teacher'){
                $jobRecordList = $this->jobrecordmdl->getJobRecord($job_id);
            }else{
                $jobRecordList = $this->jobrecordmdl->getJobRecord($job_id,$this->_userinfo['id']);
            }

            $this->_data['jobRecordList'] = $jobRecordList;
        }
        $this->load->view('header',$this->_data);
    	$this->load->view('job.html',$this->_data);
        $this->load->view('footer',$this->_data);
    }
    public  function create_job(){
        $return=array();
        if(isset($_FILES['file'])){
            $file=$_FILES['file'];
            $updateRes=$this->filelib->upload($file);
            $filename = $updateRes['final_filename'];
            $data = array(
                'name'=>$_POST['job_name'],
                'filename'=>$filename,
                'lesson_id'=>$_POST['lesson_id'],
                'end_time'=>$_POST['end_time']
            );
            $this->jobmdl->createJob($data);
        }
        redirect(base_url('courses/details?course_id='.$_POST['lesson_id']));
    }
    public function createJobRecord(){
      $job_id = isset($_GET['job_id'])?$_GET['job_id']:0;

            $file=$_FILES['file'];
            $updateRes=$this->filelib->upload($file);
            $filename = $updateRes['final_filename'];
            $data = array(
                'job_id'=>$job_id,
                'student_id'=>$this->_userinfo['id'],
                'filename'=>$filename,
                'finish_time'=>date('Y-m-d H:i:s',time())
            );
            $res=$this->jobrecordmdl->createJobRecord($data);
//        var_dump($res);
        redirect(base_url('job/index?job_id='.$job_id));
    }
    public function updateJobRecord(){
        $id = $_POST['record_id'];
        $data = array(
            'grade' => $_POST['grade'],
            'comment' => $_POST['comment'],
            'mark_time'=>date('Y-m-d H:i:s',time())
        );
        $return = $this->jobrecordmdl->updateJobRecord($id,$data);
        echo json_encode($return);
    }



}