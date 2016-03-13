<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/3/12
 * Time: 4:59
 */

class Teacher extends CI_Controller {
    private $_data=array();
    private $_userinfo=array();
    public function __construct(){
        $this->load->model('userinfomdl');
    }

    /**
     * 展示教师信息页
     */
    public function index(){
        $this->load->view('teacher.html');
    }
}