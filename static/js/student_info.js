/**
 * Created by gaoxu on 2016/3/26.
 */
$(document).ready(function(){
    getUserInfo();
    $('#change-info').click(function(){
        var username=$('#username-input').val();
        var code=$('#code-input').val();
        var _data={};
        _data['username']=username;
        _data['code']=code;
        _data['action']='updateUserInfo';
        var _url=base_url + 'student/api';
        $.post(_url,_data,function(data){
            var updateResult= $.parseJSON(data);
            if(updateResult['status']){
                alert('修改成功');
            }else{
                alert(updateResult['error_mess']);
            }
        });

    });
    $('#change-passwd').click(function(){
        var newPasswd=$('#new_passwd').val();
        var oldPasswd=$('#old_passwd').val();
        if(oldPasswd == ''){
            alert('原始密码不能为空');
            return false;
        }
        if(newPasswd == '') {
            alert('密码不能为空');
            return false;
        }
        var _data={};
        _data['new_passwd']=newPasswd;
        _data['old_passwd']=oldPasswd;
        _data['action']='changePasswd';
        var _url=base_url+'student/api';
        $.post(_url,_data,function(data){
            changeResult= $.parseJSON(data);
            if(changeResult['status']==false){
                alert(changeResult['error_mess']);
            }else{
                alert('密码修改成功');
            }
        });

    });
    $("#upload-image").fileinput({
        uploadUrl: base_url+'student/upload_image',
        autoReplace: true,
        overwriteInitial: true,
        showUploadedThumbs: false,
        maxFileCount: 1,
        initialPreview: [
            "<img style='height:160px' src='"+base_url+'static/images/images.jpg'+"'>",
        ],
        initialCaption: 'images.jpg',
        initialPreviewShowDelete: false,
        showRemove: false,
        showClose: false,
        layoutTemplates: {actionDelete: ''}, // disable thumbnail deletion
        allowedFileExtensions: ["jpg", "png", "gif"]
    });
    function getUserInfo(){
        var _data = {};
        _data['action']="getUserInfo";
        var _url = base_url+'student/api';
        $.post(_url,_data,function(data){
            var userInfo = $.parseJSON(data);
            console.log(userInfo);
            if(userInfo['status']){
                $('#username-input').val(userInfo['result']['name']);
                $('#email-input').val(userInfo['result']['email']);
                $('#code-input').val(userInfo['result']['student_id']);
                $('#user-image').attr('src',base_url+'file/'+userInfo['result']['image_url']);
            }else{
                alert(userInfo['error_mess']);
            }
        });
    }

});
