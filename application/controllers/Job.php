<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/1
 * Time: 14:50
 */

class Job extends CI_Controller{
    public function $_return=[];
    public function $_userinfo=[];
 
    public function __construct(){
    	$this->load->model(array('userinfomdl','jobmdl');
    	if($this->userinfomdl->isLogin()){
    		$this->_return['status']=false;
    		$this->_return['error_mess']='用户未登录';
    		exit(json_encode($this->_return));
    	}
    	$this->_userinfo=$this->userinfomdl->getUserInfo();
        	parent::__construct();
    	}
    }
    public function index(){
    	$this->load->view('job.html');
    }
    public function getJob(){
    	$limt=isset($_POST['limit'])?$_POST['limit']:100;
    	$offset=isset($_POST['offset'])?$_POST['offset']:0;
    	switch ($this->_userinfo['type']) {
    		case 'teacher':
    			$lesson_id=$_POST['lesson_id'];
    			$teacher_id=$this->_userinfo['id'];
    			$where=array('le_lesson.teacher_id'=>$teacher_id);
    			$res=$this->jobmdl->getJobList($where,$limit,$offset);
    			$this->_return=$res;
    			break;
    		case 'student':
    			
    			break;
    		default:
    			# code...
    			break;
    	}
    }
    public function createJob(){

    }
    public function deleteJob(){

    }
    public function index(){

    }
    public function getJob(){
        
    }
    public function createJob(){

    }
    public function deleteJob(){

    }
}