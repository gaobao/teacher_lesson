<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/4/30
 * Time: 11:10
 */

class JobRecordMdl extends CI_Model {
    private $_table;
    public function __contruct(){
        parent::__construct();
        $this->load->model('table_mdl');
        $this->_table='le_table_record';
        $this->table_mdl->setTable($this->_table);
    }
    public function getJobRecord($job_id,$student_id=0){
        $sql = "select le_job_record.id, le_student.name ,le_job_record.finish_time,le_job.filename,le_job_record.comment,le_job_record.mark_time,le_job_record.grade,le_student.student_id ";
        $sql .= " from le_job LEFT join le_job_record on le_job_record.job_id = le_job.id ";
        $sql .= " left join le_student on le_job_record.student_id = le_student.id";
        $sql .= " where le_job_record.job_id = '{$job_id}'";
        if($student_id !=0){
            $sql .= " and le_job_record.student_id = '{$student_id}'";
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
    public function  createJobRecord($data){
        $this->table_mdl->setTable('le_job_record');
        $return = $this->table_mdl->add($data);
        return $return;
    }
    public function updateJobRecord($id,$data){
        $this->table_mdl->setTable('le_job_record');
        $return = $this->table_mdl->update(array('id'=>$id),$data);
        return $return;
    }
}