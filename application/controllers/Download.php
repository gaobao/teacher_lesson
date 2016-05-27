<?php
/**
 * Created by PhpStorm.
 * User: gaoxu
 * Date: 2016/4/28
 * Time: 23:44
 */

class Download extends CI_Controller {
    private $_url = ATTACHMENTPATH;
//    public function index(){
//        if($_GET['filename']){
//
//            $file = $this->_url.$_GET['filename'];
//            $filename = basename($file);
//
//            header("Content-type: application/octet-stream");
//
//            //处理中文文件名
//            $ua = $_SERVER["HTTP_USER_AGENT"];
//            $encoded_filename = rawurlencode($filename);
//            if (preg_match("/MSIE/", $ua)) {
//                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
//            } else if (preg_match("/Firefox/", $ua)) {
//                header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
//            } else {
//                header('Content-Disposition: attachment; filename="' . $filename . '"');
//            }
//
//            //让Xsendfile发送文件
//            header("X-Sendfile: $file");
//        }else{
//            header("HTTP/1.1 404 Not Found");
//        }
//    }
public function index(){
    if($_GET['filename']){

        header("Content-type:text/html;charset=utf-8");
        $file = $this->_url.$_GET['filename'];
        $this->downloadFile($file, time().rand(10000,99999));

    }

}
    public function downloadTemplate(){
        header("Content-type:text/html;charset=utf-8");
        $file =iconv('UTF-8','GB2312',ATTACHMENTPATH.'../template/'.$_GET['filename']);
        $down_name=time().rand(10000,99999);
        $suffix = substr($file,strrpos($file,'.')); //获取文件后缀
        $down_name = $down_name.$suffix; //新文件名，就是下载后的名字
        //判断给定的文件存在与否
        if(!file_exists($file)){
            die("您要下载的文件已不存在，可能是被删除");
        }
        $fp = fopen($file,"r");
        $file_size = filesize($file);
        //下载文件需要用到的头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:".$file_size);
        header("Content-Disposition: attachment; filename=".$down_name);
        $buffer = 1024;
        $file_count = 0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count < $file_size){
            $file_con = fread($fp,$buffer);
            $file_count += $buffer;
            echo $file_con;
        }
    }
    public  function downloadFile($file, $down_name){
        $suffix = substr($file,strrpos($file,'.')); //获取文件后缀
        $down_name = $down_name.$suffix; //新文件名，就是下载后的名字
        //判断给定的文件存在与否
        if(!file_exists($file)){
            die("您要下载的文件已不存在，可能是被删除");
        }
        $fp = fopen($file,"r");
        $file_size = filesize($file);
        //下载文件需要用到的头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:".$file_size);
        header("Content-Disposition: attachment; filename=".$down_name);
        $buffer = 1024;
        $file_count = 0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count < $file_size){
            $file_con = fread($fp,$buffer);
            $file_count += $buffer;
            echo $file_con;
        }
    }
}