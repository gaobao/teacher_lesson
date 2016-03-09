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
            $res=$this->table_mdl->get($where,$limit,$offset);
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
    public function checkLessonCode(){
        $return=
        $str=$this->createLessonCode(6);
        $where=array('lesson_code'=>$str);
        $res=$this->table->getRecordNum($where);
        if($res['status']&&$res['result']>0){

        }
    }
}