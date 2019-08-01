// JavaScript Document
$(function()
{	

	$("#form_detail").submit(
	function(e)
	{	
		formData = $(this).serializeArray();
		reset_message();
		
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
					$("#div_ajax_message").html(_return.message);
					$("#div_ajax_message").removeClass().addClass(_return.div_class);					
					$("#div_ajax_message").attr('style', 'display:block;');
				}
				else
					document.location.href = $("#success_action").val().replace('{id}', msg);
		   }
		});
		
		return e.preventDefault();
	});

	
	
});