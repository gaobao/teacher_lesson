$(document).ready(function(){
    $('#select-course').click(function(){
        var $lesson_code=$('#lesson-code');
        var _lesson_code=$lesson_code.val();
        console.log(_lesson_code);
        if(_lesson_code.length!=6){
            $lesson_code.parents(".form-group").toggleClass("has-error");
            alert('请输入六位课程编码');
            return false;
        }
        var _data={};
        _data['lesson_code']=_lesson_code;
        var _url=base_url+'courses/select_course';
        $.post(_url,_data,function(data){
            var selectRes= $.parseJSON(data);
            console.log(selectRes);
            if(selectRes['status']==true){
                location.href=base_url+'courses';
            }else{
                alert(selectRes['error_mess']);
            }
        },'json');
    });
});