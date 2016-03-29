
$(document).ready(function(){
    $('#create-course').click(function(){
        var course_name= $('#course-name').val();
        var course_desc= $('#course-desc').val();
        if(course_name == '' ){
            alert('课程名称不能为空');
            return false;
        }
        var _data={};
        _data['lesson_name']=course_name;
        _data['lesson_desc']=course_desc;
        $.post(base_url+'courses/create_courses',_data,function(data){
            var createRes= $.parseJSON(data);
            if(createRes['status']){
                location.href=base_url+'courses';
            }else{
                alert('创建课程错误');
            }
        });
    });
});