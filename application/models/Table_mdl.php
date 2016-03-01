<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/2/21
 * Time: 17:28
 */

class Table_mdl extends CI_Model {
    public function __construct(){
        parent::__construct();
    }
    /**
     * 表名称
     * @var
     */
    public $table;

    /**
     * 增加记录
     * @param $data
     * @return bool
     */
    public function add($data){
        $return=array();
        $return['result']=$this->db->insert($data,$this->table);
        if($return['result']){
            $return['level']='info';
            $return['status']=true;
            $return['mess']='insert '.$this->table.' data '.print_r($data,true);
        }else{
            $return['level']='info';
            $return['status']=false;
            $return['mess']='insert error'.$this->table.' data '.print_r($data,true);
        }
        log_message($return['level'],$return['mess']);
        return $return;
    }

    /**
     * 删除记录
     * @param $where
     */
    public function delete($where){
        $return=array();
        if(!empty($where)){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
            $return['result']=$this->db->delete($this->table);
            if($return['result']){
                $return['level']='info';
                $return['mess']='delete '.$this->table.' where '.print_r($where,true);
                $return['status']=true;
            }else{
                $return['level']='info';
                $return['mess']='delete  error'.$this->table.' where '.print_r($where,true);
                $return['status']=false;
            }
        }else{
            $return['level']='info';
            $return['mess']='delete  error'.$this->table.' where is null';
            $return['status']=false;
        }

    }

    /**
     * 更新数据
     * @param $where
     * @param $data
     * @return array
     */
    public function update($where,$data){
        $return=array();
        if(!empty($where)&&!empty($data)){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
            $query=$this->db->update($this->table,$data);
            if($query){
                $return['result']=$query;
                $return['status']=true;
            }else{
                $return['status']=false;
            }
        }else{
            $return['status']=false;
        }
        return $return;
    }

    /**
     * 获取数据
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param string $order_by
     * @param string $sort
     * @return array
     */
    public function get($where,$limit=1000,$offset=0,$order_by='id',$sort='desc'){
        $return=array();
        foreach($where as $key=>$value){
            $this->db->where($key,$value);
        }
        $this->db->limit($offset,$limit);
        $this->db->order_by($order_by,$sort);
        $query=$this->db->get($this->table);
        if($query){
            $return['result']=$query->result_array();
            $return['status']=true;
        }else{
            $return['status']=false;
        }
        return $return;
    }

    /**
     * 获取结果条数
     * @param $where
     * @return array
     */
    public function getRecordNum($where){
        $return=array();
        foreach($where as $key=>$value){
            $this->db->where($key,$value);
        }
        $query=$this->db->get($this->table);
        if($query){
            $return['result']=$query->num_rows();
            $return['status']=true;
        }else{
            $return['status']=false;
        }
        return $return;
    }

    /**
     * 初始化table
     * @param $table
     */
    public function setTable($table){
        $this->table=$table;
    }

}