    <? Render::custom_javascript("/js/jquery.dataTables-min.js"); ?>
    <? Render::custom_javascript("/lib/components/tipsy/jquery.tipsy.js"); ?>
    

	<script language="javascript">
    $(function()
    {	
        $("#user_toolbar > .ic-edit").click( function (e)
        {
            document.location.href = "?" + $(this).attr("href").replace("#", "") + '/edit/' + $(this).attr("id");
        });
        
        $("#user_toolbar > .ic-cross-octagon").click( function (e)
        {
            var module = $(this).attr("href").replace("#", "");
            var id = $(this).attr("id");
            $( "#dialog-confirm" ).dialog({
                resizable: false,
                height:200,
                modal: true,
                buttons: {
                    Cancel: {
                            text: 'Cancel',
                            "class": 'mws-button black',
                            click : function() {
                                $( this ).dialog( "close" );
                            }
                    },
                    "Yes, I am sure": function()
                    {
                        $.ajax(
                        {
                           type: "POST",
                           url: "?" + module + "/delete/" + id, 
                           success: function(msg)
                           {
                               document.location.reload();
                            }
                        });
                    }
                }
            });
            
        });

		$("table.mws-table tbody tr:even").addClass("even");
		$("table.mws-table tbody tr:odd").addClass("odd");
		
		$(".mws-datatable").dataTable({
			"bPaginate": true,
			"bLengthChange": false,
			"bFilter": true,
			"bSort": true,
			"bInfo": false,
			"bAutoWidth": false 
		});	
		
		$('.tooltip').tipsy( { fade:true, live:true } );
		
    
    });
    
    </script>
    
	<div class="mws-panel-body">
        <div id="dialog-confirm" title="Please confirm this operation" style="display:none;">
            <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>
        </div>    
		
        <div class="mws-table"></div>

	    <form action="" class="mws-form">
	        <table class="mws-table mws-datatable">
	            <thead>
	                <tr>
	                    <th>Id</th>
	                    <th>Name</th>
	                    <th>E-Mail</th>
	                    <th>Plan</th>
	                    <th width="100">Actions</th>
                    </tr>
                </thead>
	            <tbody>
                <? foreach($db_data as $_user) { ?>
	                <tr>
	                    <td><p><? echo $_user['id']; ?></p></td>
	                    <td><p><? echo $_user['tx_first_name'] . " " . $_user['tx_last_name']; ?></p></td>
	                    <td><p><? echo $_user['tx_email']; ?></p></td>
	                    <td><p><? echo $_user['plan_name']; ?></p></td>
	                    <td class="center">
                        <p id="user_toolbar">
                        <a href="#user" id="<? echo $_user['id']; ?>" title="Delete user" class="mws-ic-16 block mws-i-16 ic-cross-octagon tooltip"></a>&nbsp;&nbsp;&nbsp;
                        <a href="#user" id="<? echo $_user['id']; ?>" title="Edit user" class="mws-ic-16 block mws-i-16 ic-edit calendar tooltip"></a>&nbsp;&nbsp;
                        </p></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
            
        </form>
	</div>
