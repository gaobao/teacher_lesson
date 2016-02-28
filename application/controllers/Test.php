<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/28
 * Time: 16:52
 */

class test extends CI_Controller {
    public function index(){
        $this->load->view('login.html');
    }
}