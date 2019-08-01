					<div class="pad20">
						<!-- Tabs -->
						<div id="tabs">
							<ul>
								<li><a href="#tabs-1">Geral</a></li>
								<li><a href="#tabs-2">Foto</a></li>

							</ul>
							
							<div id="tabs-1">
                            <? echo $view_general; ?>
							</div>

							<div id="tabs-2">
                                  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="5">
                                    <tr>
                                      <td>
                                        <form id="insert_file" name="insert_file" action="?<? echo($_module); ?>/upload" method="post" target="gallery" enctype="multipart/form-data">
                                        <table width="100%"  border="0" align="center" cellpadding="0" class="fullwidth">
                                            <tr>
                                                <td>
                                                  Inserir imagem : &nbsp;
                                                  <input name="<? echo($config['id_column']); ?>" type="hidden" value="<? echo $id; ?>" class="field">
                                                  <input name="image" type="file" class="field">
                                                  <input name="Submit" type="submit" class="button" value="Inserir Imagem">  
                                              </td>
                                            </tr>
                                           </table>
                                         </form>
                                         </td>
                                         <tr>
                                    <tr>
                                      <td valign="top" align="right">
                                          <iframe name="gallery" frameborder="0" scrolling="auto" marginheight="0" marginwidth="0" src="?<? echo $_module; ?>/list_images/<? echo $id; ?>" width="100%" height="250">
                                          </iframe>
                                      </td>
                                      </tr>
                                    <tr>
                                      <td valign="top" align="right">&nbsp;
                                      </td>
                                      </tr>
                                      
                                  </table>
                                </form>
                            </div>
                            
                    </div>                    
                </div>