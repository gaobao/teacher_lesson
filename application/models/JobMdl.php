<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/1
 * Time: 14:51
 */

class JobMdl extends CI_Model {
    /**
     * 返回值
     * @var array
     */
    private $_return=[];
    /**
     * 表名称
     * @var string
     */
    private $_table='le_job';
    public function __construct(){
        parent::__construct();
        $this->load->model('table_mdl');
        $this->table_mdl->setTable($this->_table);
    }

    /**
     * 创建作业
     * @param $data
     * @return array
     */
    public function createJob($data){
        if(!empty($data)&&is_array($data)){
            if(isset($data['lesson_id'])){//必须存在课程id
                $res=$this->table_mdl->add($data);
                if($res['status']){
                    $return=$res;
                }else{
                    $this->_return['status']=false;
                    $this->_return['error_mess']='写入数据出错';
                }
            }else{
                $this->_return['status']=false;
                $this->_return['error_mess']='课程id为空';
            }
        }else{
            $this->_return['status']=false;
            $this->_return['error_mess']='数据为空';
        }
        return $this->_return;
    }

    /**
     * 获取作业列表
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param string $order_by
     * @param string $sort
     */
    public function getJobList($where,$limit=1000,$offset=0,$order_by='le_job.timestamp',$sort='desc'){
        $this->db->select('le_job.id as job_id,le_job.title as job_name,le_job.lesson_id,le_job.end_time as job_end_time,
        le_job.timestamp as job_time,le_lesson.lesson_name,le_lesson.lesson_desc,le_lesson.teacher_id,
        le_teacher.name as teacher_name,le_teacher.teacher_code');

        $this->db->from($this->_table);
        $this->db->join('le_lesson','le_job.lesson_id=le_lesson.id','left');
        $this->db->join('le_teacher','le_teacher.id=le_lesson.teacher_id','left');
        if(is_array($where)&&!empty($where)){
            foreach($where as $key=>$value){
                $this->db->where($key,$where);
            }
        }
        $this->db->limit($limit,$offset);
        $this->db->order_by($order_by,$sort);
        $res=$this->db->get();
        if($res){
            $this->_return['status']=true;
            $this->_return['result']=$res;
        }else{
            $this->_return['status']=false;
            $this->_return['error_mess']='查询出错';
        }
    }
    public function getStudentJob($lesson_id,$student_id){
            $this->db->select();
            $this->db->join();
    }

    /**
     * 删除作业
     * @param $id
     * @return array
     */
    public function deleteJob($id){
        if(is_numeric($id)){
            $where=array('id'=>$id);
            $res=$this->table_mdl->delete($where);
            $this->_return=$res;
        }else{
            $this->_return['status']=false;
            $this->_return['error_mess']='id 不是数字';
        }
        return $this->_return;
    }
}