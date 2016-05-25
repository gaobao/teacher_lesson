//$("#upload-file").fileinput({
//    uploadUrl: base_url+'job/update_job_file',
//    autoReplace: true,
//    overwriteInitial: true,
//    showUploadedThumbs: false,
//    maxFileCount: 1,
//    initialPreviewShowDelete: false,
//    showRemove: false,
//    showClose: false,
//    layoutTemplates: {actionDelete: ''}, // disable thumbnail deletion
//    allowedFileExtensions: ["docx", "doc"],
//    done:function(e,data){
//        alert(data);
//    }
//});
//$("#upload-file").on("fileuploaded", function(event, data, previewId, index) {
//    console.log(event);
//    console.log(data);
//    console.log(previewId);
//    console.log(index);
//});
$(document).ready(function(){
    console.log($('select'));
    $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    $("#job-submit").click(

    );
    //console.log(getUrlParam('course_id'));
    $("input[name='lesson_id']").val(getUrlParam('course_id'));
    $('select').change(function(){
        console.log($(this));
        var student_id = $(this).next().val();
        var lesson_id = $(this).next().next().val();
        console.log($(this).prevAll().find('.lesson_id'));
        var status = $(this).val();
        var _data = {};
        _data['lesson_id'] = lesson_id;
        _data['student_id'] =student_id;
        _data['status'] = status;
        console.log(_data);
        $.post(base_url+'courses/change_student_status',_data,function(data){
            var result = $.parseJSON(data);
            console.log(result);
        });
    });
});


