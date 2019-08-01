$(function ()
{	
	$("#form_changepwd").submit(
	function(e)
	{	
	
		formData = $(this).serializeArray();
		reset_message();

		if($("#tx_password").val() != $("#tx_password2").val())
		{
			$("#div_ajax_message").html("Passwords doesn´t match!");
			$("#div_ajax_message").attr('style', 'display:block;');
			return false;
		}
	
		$.ajax(
		{
		   type: "POST",
		   url: $(this).attr("action"), 
		   data: formData,
		   success: function(msg)
		   {
			    var _return = eval("(" + msg + ")");
				if(_return.success == false)
				{
					$("#div_chpwd_message").html(_return.message);
					$("#div_chpwd_message").removeClass().addClass(_return.div_class);					
					$("#div_chpwd_message").log("ajax_msg").attr('style', 'display:block;');
				}
				else
					document.location.href = $("#success_action").val().replace('{id}', _return.message);
		   }
		});
		
		return e.preventDefault();
	});

	$("#button_cancel").click(
	function ()
	{
		history.go(-1);
	});
	
});	
