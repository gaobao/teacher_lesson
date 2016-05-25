<?php

class SignMdl extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('table_mdl');
    }
    public function createSign($data){
        $this->table_mdl->setTable('le_sign');
        return $this->table_mdl->add($data);
    }
    public function getSignList($lesson_id){
        $sql = "select * from le_sign where lesson_id = '{$lesson_id}'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    public function getSignByLesson($lesson_id){
        $sql = "select * from le_sign where lesson_id = '{$lesson_id}' order by id DESC ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    public function getStudentSign($sign_id){
        $sql = "select le_sign_record.sign_id,le_student.id as student_id,le_student.name,le_student.student_id as studentCode ,le_sign.sign_name from le_student";
        $sql .=" left join le_lesson_record on le_lesson_record.student_id = le_student.id";
        $sql .=" left join le_sign on le_lesson_record.lesson_id = le_sign.lesson_id ";
        $sql .=" left join le_sign_record on le_sign_record.sign_id = le_sign.id and le_sign_record.student_id = le_student.id ";
        $sql .=" where le_sign.id = '{$sign_id}' and le_lesson_record.status = 'agree' ";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    public function deleteSign($sign_id,$student_id){
        $return = array();
        $sql = "delete le_sign_record where sign_id = '{$sign_id}' and student_id = '{$student_id}'";
        $query = $this->db->query($sql);
        if($query){
            $return['status'] = true;
        }else{
            $return['status'] = false;
        }
        return $return;
    }
    public function createStudentSign($data){
        $this->table_mdl->setTable('le_sign_record');
        return $this->table_mdl->add($data);
    }

}