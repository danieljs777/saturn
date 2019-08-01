                        <h1>Bem vindo, <span><? echo($admin_name); ?></span>!</h1>
                        <p>O que você deseja fazer hoje?</p>
                        
                        <div class="pad20">
                        <!-- Big buttons -->
                            <ul class="dash">
                            <? if($_SESSION["admin_luid"] == 1) : ?>
                                <li>
                                    <a href="/admin/?news/create" title="Adicionar nova notícia" class="tooltip">
                                        <img src="assets/icons/8_48x48.png" alt="" />
                                        <span>Criar notícia</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/?banner/create" title="Adicionar novo banner" class="tooltip">
                                        <img src="assets/icons/8_48x48.png" alt="" />
                                        <span>Criar imagem home</span>
                                    </a>
                                </li>
                            
                                <li>
                                    <a href="/admin/?post/create" title="Adicionar um novo post" class="tooltip">
                                        <img src="assets/icons/8_48x48.png" alt="" />
                                        <span>Criar item seção</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/?city/create" title="Adicionar uma nova cidade" class="tooltip">
                                        <img src="assets/icons/8_48x48.png" alt="" />
                                        <span>Criar Cidade</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/?columnist/create" title="Adicionar novo colunista" class="tooltip">
                                        <img src="assets/icons/8_48x48.png" alt="" />
                                        <span>Criar Colunista</span>
                                    </a>
                                </li>
                                <? endif; ?>

                            </ul>
                        
                            <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                            
                            <ul class="dash">
                            <? if($_SESSION["admin_luid"] == 1) : ?>
                                <li>
                                    <a href="/admin/?news" title="Listar notícias" class="tooltip">
                                        <img src="assets/icons/19_48x48.png" alt="" />
                                        <span>Listar notícias</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/?banner" title="Listar banners" class="tooltip">
                                        <img src="assets/icons/1_48x48.png" alt="" />
                                        <span>Listar imagens home</span>
                                    </a>
                                </li>
                            
                                <li>
                                    <a href="/admin/?post" title="Listar Posts" class="tooltip">
                                        <img src="assets/icons/17_48x48.png" alt="" />
                                        <span>Listar item seção</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/?city" title="Listar Cidades" class="tooltip">
                                        <img src="assets/icons/4_48x48.png" alt="" />
                                        <span>Listar Cidades</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/?columnist" title="Listar Colunistas" class="tooltip">
                                        <img src="assets/icons/16_48x48.png" alt="" />
                                        <span>Listar Colunistas</span>
                                    </a>
                                </li>
                                <? endif; ?>

                            </ul>
                            
                        </div>
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
