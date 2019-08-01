$(document).ready(function ()
{	

	var _new_obj_link;

	$(".btn_modal").click(
		function (e)
		{
			_new_obj_link = $(this).attr('data-link');
			console.log("Link " + _new_obj_link, this);
			
			$("#new_object").dialog('open');
			
		}
	);
			
	 $("#loading_screen").bind("ajaxSend", function(){
	   $(this).show();
	 }).bind("ajaxComplete", function(){
	   $(this).hide();
	 });
	 
	$("#new_object").dialog({
		modal: true,
		autoOpen: false,
		open: function ()
		{
			$(this).load(_new_obj_link);

		},         
		height: 230,
		width: 400,
		title: 'Crie um novo'			
	});
	 

	$("#form_detail").submit(
	function(e)
	{	
	
		if(typeof(CKEDITOR) != 'undefined')
		{
			for(var instanceName in CKEDITOR.instances)
				CKEDITOR.instances[instanceName].updateElement();
		}
		
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
					show_message("edit_ajax_message", _return.div_class, _return.message);
				else
				{
					if($("#preview"))
						action = ($("#preview").attr('checked')) ? $("#preview_action") : $("#success_action");
					else
						action = $("#success_action");
					
					if(action.val().indexOf('new:') > -1)
						window.open(action.val().replace('new:', '').replace('{id}', _return.id));
					else	
						document.location.href = action.val().replace('{id}', _return.id);
				}
		   }
		});
		
		return e.preventDefault();
	});

	$("#button_cancel").click(
	function ()
	{
		history.go(-1);
	});
	
	$("#button_draft").click(
	function ()
	{
		$("#preview").attr('checked', 'checked');
		$("#form_detail").submit();
	});
/*	
	function reset_message()
	{
		$("#div_ajax_message").html('');
		$("#div_ajax_message").attr('style', 'display:none;');
	}
	
	$("#toogle_status").click(
	function ()
	{
		formData = $(this).serializeArray();
	
		$.ajax(
		{
		   type: "POST",
		   url: $("#form_detail").attr("action"), 
		   data: formData,
		   success: function(msg)
		   {
				$("#div_ajax_message").html(msg);
				$("#div_ajax_message").dialog('open');
		   }
		});
		
		return e.preventDefault();
	});
*/
});