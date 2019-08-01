    <? Render::custom_javascript("lib/application/admin/common/js/global.js"); ?>

	<script language="javascript">
    $(function()
    {	
		$('#form_detail').live('submit', function(e)
		{
			formData = $(this).serializeArray();
			reset_message("div_ajax_message");
					
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
						show_message("edit_ajax_message", _return.div_class, _return.message);
					}
					else
					{
						document.location.href = '?user/index';
					}
			   }
			});
			
			return e.preventDefault();
			
		});

		$( "#tabs" ).tabs();
    });
    
    </script>

    <div id="tabs">
        <ul>
            <li><a href="?user/list_all/&ajax=1">Clients</a></li>
            <li><a id="detail_tab" href="?user/create">Add Client</a></li>
        </ul>
    </div>
                    
