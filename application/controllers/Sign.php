<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/5/2
 * Time: 11:54
 */

class Sign extends CI_Controller {
    /**
     * 传给view的数据
     * @var
     */
    private $_data;
    /**
     * 用户个人信息
     * @var
     */
    private $userinfo;

    public function __construct(){
        parent::__construct();
        $this->load->library('filelib');
        $this->load->model(array('userinfomdl','coursesmdl','signmdl'));
        $isLogin=$this->userinfomdl->isLogin();
        if(!$isLogin['status']){
            redirect(base_url('login'));

        }
        $this->userinfo=$this->userinfomdl->getUserInfo();
        $this->_data['userinfo']=$this->userinfo;
    }
    public function index(){
        if(isset($_GET['lesson_id'])){
            $this->_data['lesson_id'] = $_GET['lesson_id'];
            $this->_data['signList'] = $this->signmdl->getSignByLesson($_GET['lesson_id']);
            $this->load->view('header',$this->_data);
            $this->load->view('sign.html',$this->_data);
            $this->load->view('footer',$this->_data);
        }else{
            echo "<script>alert('请先选择课程');location.href='".base_url('courses')."';</script>";
        }
    }
    public function create_sign(){
        $lesson_id = isset($_POST['lesson_id'])?$_POST['lesson_id']:0;
        $sign_name = isset($_POST['sign_name'])?$_POST['sign_name']:0;
        $return = array();
//        var_dump($_POST);
        if($lesson_id ==0||$sign_name ==0){
            $return = array(
                'status'=>false,
                'error_mess'=>'请求参数错误'
            );
        }else{
            $data = array(
                'sign_name' =>$sign_name,
                'lesson_id' =>$lesson_id
            );
            $return = $this->signmdl->createSign($data);
        }
        echo json_encode($return);
    }
    public function signStudent(){
        if(isset($_GET['sign_id'])){
            $sign_id = $_GET['sign_id'];
            $this->_data['sign_id']=$sign_id;
            $this->_data['student_list']=$this->signmdl->getStudentSign($sign_id);
//        var_dump($this->_data['student_list']);
//            exit();
            $this->load->view('header',$this->_data);
            $this->load->view('signStudent.html',$this->_data);
            $this->load->view('footer');
        }else{
            echo "<script>alert('请先选择考勤');history.back(-1);</script>";
        }

    }
    public function getSignExcel(){
        if(isset($_GET['sign_id'])){
            include APPPATH.'/third_party/PHPExcel.php';
            include APPPATH.'/third_party/PHPExcel/IOFactory.php';
            include APPPATH.'/third_party/PHPExcel/Writer/Excel5.php';
            $sign_id = $_GET['sign_id'];
            $signList = $this->signmdl->getStudentSign($sign_id);
            //新建
            header('Content-Type: text/html; charset=utf-8');  //设置网页编码方式，最好是utf-8

            $objPHPExcel = new PHPExcel();  //创建PHPExcel实例

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '学生姓名')
                ->setCellValue('B1', '学生工号')
                ->setCellValue('C1', '考勤标识')
                ->setCellValue('D1', '考勤状态');
            $i=2;                //定义一个i变量，目的是在循环输出数据是控制行数
            foreach($signList as $item){
                if(empty($item['sign_id'])){
                    $status = '未签到';
                }else{
                    $status = '已签到';
                }
                $objPHPExcel->setActiveSheetIndex(0)

                    ->setCellValue("A".$i, $item['name']) //向单元格中填写数据
                    ->setCellValue("B".$i, $item['studentCode'])  //由于我的这一列是中文，所以在上面进行了编码
                    ->setCellValue("C".$i, $item['sign_name'])
                    ->setCellValue("D".$i, $status);
                $i++;
            }
            $objPHPExcel->getActiveSheet()->setTitle('Example1');      //设置sheet的名称
            $objPHPExcel->setActiveSheetIndex(0);           //设置sheet的起始位置
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $outputFileName = "考勤.xlsx";
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");//文件流
            header("Content-Type: application/download"); //下载文件
            header('Content-Disposition:attachment;filename="'.$outputFileName.'"');
            header("Content-Transfer-Encoding: binary");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");//上一次修改时间
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache"); //不缓存页面
            $objWriter->save('php://output'); //输出到浏览器

        }else{
            echo "<script>alert('导出失败');history.back(-1);</script>";

        }
    }
    public function signStudentCreate(){
        $sign_id = isset($_GET['sign_id'])?$_GET['sign_id']:0;
        $student_id = $_POST['student_id'];
        if($sign_id ==0||empty($student_id)){
            $return['status'] = false;
            $return['error_mess'] = '签到失败';
        }else{
            $status = $_POST['status'];

            if($status==0){
                $return=$this->signmdl->deleteSign($sign_id,$student_id);
            }else{
                $data = array(
                    'sign_id'=>$sign_id,
                    'student_id'=>$student_id
                );
                $return = $this->signmdl->createStudentSign($data);
            }
        }
        echo json_encode($return);
    }
}