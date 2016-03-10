var base_url='http://localhost/cloud_system/';
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
		if(_name == ""){
			$_loginName.parents(".form-group").toggleClass("has-error");
            return false;
		}	
		if(_pwd == ""){
			$_loginPwd.parents(".form-group").toggleClass("has-error");
            return false;
		}
		_data["name"] = _name;
		_data["pwd"] = _pwd;
		_data["role"] = _role;
		$.post(base_url+"/login/loginin", _data, function(data){
			if(data["status"] == false){
				alert(data['error_mess']);
			}else if(data['status']==true){
				location.href=base_url+'courses';
			}
			
		}, "json");
	});
});