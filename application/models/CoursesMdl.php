<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/25
 * Time: 21:41
 */

class CoursesMdl extends CI_Model {

    private $table;
    public function __construct(){
        parent::__construct();
        $this->load->model('table_mdl');
        $this->table='le_lesson';
        $this->table_mdl->setTable($this->table);
    }

    /**
     * 创建课程
     * @param $lesson_name
     * @param $lesson_desc
     * @param $teacher_id
     * @return array
     */
    public function create($lesson_name,$lesson_desc,$teacher_id){
        $return=array();
        if(!empty($lesson_desc)&&!empty($lesson_name)&&is_numeric($teacher_id)){
            $data=array(
                'lesson_name'=>$lesson_name,
                'lesson_desc'=>$lesson_desc,
                'teacher_id'=>$teacher_id,
                'lesson_code'=>$this->createLessonCode(6)
            );
            $res=$this->table_mdl->add($data);
            $return=$res;
        }else{
            $return['status']=false;
            $return['error_mess']='传入数据有问题';
        }
        return $return;
    }
    public function getCourses($where,$limit=1000,$offset=0){
        $return=array();
        if(!empty($where)&&is_array($where)&&is_numeric($limit)&&is_numeric($offset)){
            $return=$this->table_mdl->get($where,$limit,$offset);
//            $return['status']=true;
//            $return['result']=$res;
        }else{
            $return['status']=false;
        }
        return $return;
    }
    public function update($where,$data){
        $return=array();
        if(!empty($where)&!empty($data)&&is_array($where)&&is_array($data)){
            $this->table_mdl->update($where,$data);
        }else{
            $return['status']=false;
            $return['error_mess']='更新条件或数据错误';
        }
        return $return;
    }
    public function delete($where){
        return $this->table_mdl->delete($where);
    }

    /**
     * 生成随机码
     * @param $num
     * @return string
     */
    public function createLessonCode($num){
        $str="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $returnStr='';
        for($i=0;$i<$num;$i++){
            $returnStr .=$str[mt_rand(0,35)];
        }
        return $returnStr;
    }

    public function checkLessonCode($num){
        $returnStr=$this->checkLessonCode($num);
        $where=array('lesson_code'=>$returnStr);
        $res=$this->table->getRecordNum($where);
        if($res['status']&&$res['result']>0){
            $this->checkLessonCode($num);
        }
        return $returnStr;
    }

    /**
     * 获取学生课程列表
     * @param $tudent_id
     * @return array
     */
    public function getStudentCourses($student_id){
        $return=array();
        $where=array('student_id'=>$student_id);
        $this->db->select('le_lesson.id,le_lesson.lesson_code,le_lesson.lesson_name,le_lesson.lesson_desc,le_lesson_record.status');
        $this->db->from('le_lesson');
        $this->db->join('le_lesson_record','le_lesson.id=le_lesson_record.lesson_id','inner');
        if(is_array($where)&&!empty($where)){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $res=$this->db->get();
        if($res){
            $return['status']=true;
            $return['result']=$res->result_array();
        }else{
            $return['status']=false;
            $return['error_mess']='查询出错';
        }
        return $return;
    }
    public function createCourseRecord($data){
        $return=array();
        $this->load->model('table_mdl');
        $this->table_mdl->setTable('le_lesson_record');
        $res=$this->table_mdl->add($data);
        if($res['status']){
            $return['status']=true;
        }else{
            $return['status']=false;
            $return['error_mess']='选择课程错误';
        }
        return $return;
    }
    public function getStudent($lesson_id){
        $return=array();
        $select = "select * from le_lesson_record left join le_lesson";
        $select .= " on le_lesson_record.lesson_id = le_lesson.id left join ";
        $select .= " le_student on le_lesson_record.student_id = le_student.id where le_lesson_record.lesson_id = '{$lesson_id}'";
        $query = $this->db->query($select);
        $res = $query->result_array();
        return $res;
    }
    public function getAttachment($lesson_id){
        $sql = "select * from le_attachment where lesson_id = '{$lesson_id}'";
        $query = $this->db->query($sql);
        $res = $query->result_array($query);
        return $res;
    }
    public function getMaterial($lesson_id){
        $sql = "select * from le_material where lesson_id = '{$lesson_id}'";
        $query = $this->db->query($sql);
        $res = $query->result_array($query);
        return $res;
    }
    public function delete_file($id,$type){
        $return = array();
        switch($type){
            case 'material':
                $this->table_mdl->setTable('le_material');
                break;
            case 'attachment':
                $this->table_mdl->setTable('le_attachment');
                break;
            case 'job':
                $this->table_mdl->setTable('le_job');
                break;
            default:
                break;
        }
        $where = array('id'=>$id);
        $res=$this->table_mdl->get($where);
        log_message('debug',print_r($res,true));
        foreach($res['result'] as $key=>$value){
            if($type == 'job'){
                if (unlink(ATTACHMENTPATH . $value['filename'])) {
                    $this->table_mdl->delete($where);
                }
            }else {
                if (unlink(ATTACHMENTPATH . $value['file_url'])) {
                    $this->table_mdl->delete($where);
                }
            }
        }
    }
    public function change_student_status($lesson_id,$student_id,$status){
        $where = array(
            'lesson_id' =>$lesson_id,
            'student_id' =>$student_id
        );
        $data = array(
            'status' => $status
        );
        $this->table_mdl->setTable('le_lesson_record');
        $result = $this->table_mdl->update($where,$data);
        return $result;
    }
    public function getStudentJurisdiction($lesson_id,$student_id){
        $this->load->database();
        $sql = "select status from le_lesson_record where lesson_id = '{$lesson_id}' and student_id = '{$student_id}'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
//        if(empty($result)){
//            $return['status'] = false;
//            $return['error_mess'] = '未选择该课程';
//        }else{
//            $return['status'] = '';
//            $return['result'] = $result['0']['status'];
//        }
//        return $return;
        return $result[0]['status'];
    }
}
