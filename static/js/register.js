
$(function(){
	$(".input-exec").on("change", function(){
		var $_parent = $(this).parents(".form-group");
		$_parent.hasClass("has-error") && $_parent.removeClass("has-error");
	});
	$("#submit-btn").on("click", function(){
		var _data = {},
			$_loginName = $("#login-name"),
			$_loginPwd = $("#login-pwd"),
			_name = $.trim($_loginName.val()),
			_pwd  = $.trim($_loginPwd.val()),
			_role = $("#login-role").val();
			_re_passwd=$("#login-re-pwd").val();
		if(_name == ""){
			$_loginName.parents(".form-group").toggleClass("has-error");
            return false;
		}	
		if(_pwd == ""){
			$_loginPwd.parents(".form-group").toggleClass("has-error");
            return false;
		}
		if(_re_passwd == ""){
			$_loginPwd.parents(".form-group").toggleClass("has-error");
            return false;
		}
		_data["email"] = _name;
		_data["passwd"] = _pwd;
		_data["type"] = _role;
		_data["re_passwd"]=_re_passwd;
		$.post(base_url+"/register/register_in", _data, function(data){
			if(data["status"] == false){
				alert(data['error_mess']);
			}
            location.href=base_url+'courses';
			//to url index html
			// window.location.href =
            //location.href=base_url+'login';
		}, "json");
	});
});