
<style type="text/css"> 
	#resizable { width: 100%; height: 500px; padding: 0.5em; }
	.ui-resizable-helper { border: 15px solid #EFEFEF; margin: 0px; }
	#gallery {
		width: 100%;
		height: 100%;
		border: none;    
	}
</style> 
<script type="text/javascript"> 
	$(function() {
	  $("#resizable").resizable({
		  helper: "ui-resizable-helper",
		  stop: function(event, ui) { resize(); },
		  maxWidth: 1180,
	  });
	});
</script> 


					<div class="pad20">
						<!-- Tabs -->
						<div id="tabs">
							<ul>
								<li><a href="#tabs-1">Geral</a></li>
								<li><a href="#tabs-2">Imagens</a></li>
								<li><a href="#tabs-3">Podcasts</a></li>
								<li><a href="#tabs-4">Vídeos</a></li>
							</ul>
							
							<div id="tabs-1">
                            <? echo $view_general; ?>
							</div>
                            
							<div id="tabs-2">
								<div id="resizable" class="ui-widget-content"> 
                                  <iframe name="gallery" id="gallery" frameborder="0" scrolling="auto" marginheight="0" marginwidth="0" src="?<? echo $_module; ?>/list_images/<? echo $id; ?>" width="100%" height="100%">
                                  </iframe>
                                  </div> 
                                      
                            </div>
                            
                            <div id="tabs-3">
                                  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="5">
                                    <tr>
                                      <td>
                                        <form id="insert_podcast" name="insert_podcast" action="?<? echo($_module); ?>/upload_podcast" method="post" target="list_podcasts" enctype="multipart/form-data">
                                        <table width="100%"  border="0" align="center" cellpadding="0" class="fullwidth">
                                            <tr>
                                                <td>
                                                  Inserir PodCast : &nbsp;
                                                  <input name="<? echo($config['id_column']); ?>" type="hidden" value="<? echo $id; ?>" class="field">
                                                  <input name="podcast" type="file" class="field">
                                                  &nbsp; Data
                                                  <input name="podcast_date" type="textfield" value="" class="field">
													&nbsp; Título
                                                  <input name="title" type="textfield" value="" class="field">                                                  
                                                  <input name="Submit" type="submit" class="button" value="Inserir Áudio">  
                                              </td>
                                            </tr>
                                           </table>
                                         </form>
                                         </td>
                                         <tr>
                                    <tr>
                                      <td valign="top" align="right">
                                          <iframe name="list_podcasts" frameborder="0" scrolling="auto" marginheight="0" marginwidth="0" src="?<? echo $_module; ?>/list_podcasts/<? echo $id; ?>" width="100%" height="250">
                                          </iframe>
                                      </td>
                                      </tr>
                                    <tr>
                                      <td valign="top" align="right">&nbsp;
                                      </td>
                                      </tr>
                                      
                                  </table>
                            </div>

							<div id="tabs-4">
								<div id="resizable" class="ui-widget-content"> 
                                  <iframe name="videos" id="videos" frameborder="0" scrolling="auto" marginheight="0" marginwidth="0" src="?<? echo $_module; ?>/list_videos/<? echo $id; ?>" width="100%" height="100%">
                                  </iframe>
                                  </div> 
                                      
                            </div>
                            
                    </div>
                </div>