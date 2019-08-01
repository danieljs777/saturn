
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

							</ul>
							
							<!-- First tab -->
							<div id="tabs-1">
                            <? echo $view_general; ?>
							</div>
                            
							<div id="tabs-2">
								<div id="resizable" class="ui-widget-content"> 
                                  <iframe name="gallery" id="gallery" frameborder="0" scrolling="auto" marginheight="0" marginwidth="0" src="?<? echo $_module; ?>/list_images/<? echo $id; ?>" width="100%" height="100%">
                                  </iframe>
                                  </div> 
                                      
                            </div>
                        </div>
                    </div>
