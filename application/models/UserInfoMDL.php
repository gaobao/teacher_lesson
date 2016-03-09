<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/20
 * Time: 20:48
 */

class UserInfoMDL extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('table_mdl');
    }

    /**
     * 判断用户是否登录
     * @return mixed
     */
    public function isLogin(){
        $userinfo=$this->session->userdata('userinfo');
        if(!empty($userinfo)){
            $return['status']=true;
        }else{
            $return['status']=false;
        }
        return $return;
    }

    /**
     * 登录
     * @param $email
     * @param $passwd
     * @param $type
     */
    public function login($email,$passwd,$type){
        $return=array();
        switch($type){
            case 'teacher':
                $table='le_teacher';
                $this->table_mdl->setTable($table);
                $user=$this->table_mdl->get(array('email'=>$email));
                if($user['status']&&!empty($user['result'])){
                    if($user['result']['0']['passwd']==sha1($passwd)){
                        $return['status']=true;
                        $this->updateSession($email,$type);
                    }else{
                        $return['status']=false;
                        $return['error_mess']='密码错误';
                    }
                }else{

                    $return['status']=false;
                    $return['error_mess']='用户不存在';
                }
                break;
            case 'student':
                $table='le_student';
                $this->table_mdl->setTable($table);
                $user=$this->table_mdl->get(array('email'=>$email));
                if($user['status']&&!empty($user['result'])){
                    if($user['result']['0']['passwd']==sha1($passwd)){
                        $return['status']=true;
                        $this->updateSession($email,'student');
                    }else{
                        $return['status']=false;
                        $return['error_mess']='密码错误';
                    }
                }else{
                    $return['status']=false;
                    $return['error_mess']='用户不存在';
                }
                break;
            default:
                $return['status']=false;
                $return['error_mess']='用户类别错误';
                break;
        }
        return $return;
    }

    /**
     * 用户注册
     * @param $email
     * @param $passwd
     * @param $conPasswd
     * @param $type
     * @return array
     */
    public function register($email,$passwd,$conPasswd,$type){
        $return =array();
        if($this->common->strIsEmail($email)){
            if($passwd==$conPasswd){
                if(5<strlen($passwd)&&strlen($passwd)<30){
                    $data=array(
                        'email'=>$email,
                        'passwd'=>sha1($passwd)
                    );

                    switch($type){
                        case 'teacher':
                            $table='le_teacher';
                            $this->table_mdl->setTable($table);
                            $user=$this->table_mdl->getRecordNum(array('email'=>$email));

                            if($user['status']&&$user['result']==0){
                                $res=$this->table_mdl->add($data);
                                if($res['status']){
                                    $return['status']=true;
                                    $this->updateSession($email,'teacher');
                                }else{
                                    $return['status']=false;
                                    $return['error_mess']='数据写入出错';
                                }
                            }else{
                                $return['status']=false;
                                $return['error_mess']='该邮箱已经注册';
                            }
                            break;
                        case 'student':
                            $table='le_student';
                            $this->table_mdl->setTable($table);
                            log_message('debug',$table);
                            $user=$this->table_mdl->getRecordNum(array('email'=>$email));
                             log_message('debug',$user['status'].$user['result']);
                            if($user['status']&&$user['result']==0){
                                $res=$this->table_mdl->add($data);
                                if($res['status']){
                                    $return['status']=true;
                                    $this->updateSession($email,'teacher');
                                }else{
                                    $return['status']=false;
                                    $return['error_mess']='数据写入出错';
                                }
                            }else{
                                $return['status']=false;
                                $return['error_mess']='该邮箱已经注册';
                            }
                            break;
                        default:
                            $return['status']=false;
                            break;
                    }
                }else{
                    $return['error_mess']='密码长度需大于5小于30';
                    $return=false;
                }
            }else{
                $return['error_mess']='两次密码输入不一样';
                $return['status']=false;
            }
        }else{
            $return['error_mess']='邮箱格式不正确';
            $return['status']=false;
        }
        return $return;

    }

    /**
     * 退出登录
     * @return array
     */
    public function loginOut(){
        $return=array();
        if($this->session->unset_userdata('userinfo')){
            $return['status']=true;
        }else{
            $return['status']=false;
        }
        return $return;
    }

    /**
     * 更新用户信息
     * @param $userinfo
     * @param $type
     * @param $email
     * @return mixed
     */
    public function updateInfo($userinfo,$type,$email){
        switch($type){
            case 'teacher':
                $table='le_teacher';
                $this->table_mdl->setTable($table);
                if($this->table_mdl->update(array('email'=>$email),$userinfo)){
                    $return['status']=true;
                }else{
                    $return['status']=false;
                }
                break;
            case 'student':
                $table='le_student';
                $this->table_mdl->setTable($table);
                if($this->table_mdl->update(array('email'=>$email),$userinfo)){
                    $return['status']=true;
                }else{
                    $return['status']=false;
                }
                break;

            default:
                $return['status']=false;
                break;
            return $return;
        }
    }

    /**
     * 更新用户session
     * @param $email
     * @return array
     */
    public function updateSession($email,$type){
        $return=array();
        $user=$this->table_mdl->get(array('email'=>$email));
        if($user['status']&&$user['result'][0]) {
            $user['result'][0]['type']=$type;
            $this->session->set_userdata(array('userinfo'=>$user['result'][0]));
            $return['status']=true;
        }else{
            $return['status']=false;
            $return['error_mess']='没有此用户';
        }
        return $return;
    }

    /**
     * 获取session中userinfo
     * @return mixed
     */
    public function getUserInfo(){
        return $this->session->userdate('userinfo');
    }
}