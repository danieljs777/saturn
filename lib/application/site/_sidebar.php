<?
$sd_continent_ctrl = System::load_controller("parameter");
$sd_country_ctrl   = System::load_controller("country");
$sd_param_ctrl     = System::load_controller("parameter");
$sd_post_ctrl      = System::load_controller("post");

$sd_sections       = $sd_param_ctrl->show_list(array("t_id" => 4), "p_id");

$sd_continents     = $sd_param_ctrl->show_list(array("t_id" => 2));

foreach($sd_continents as $sd_continent)
	$sd_countries[$sd_continent["p_id"]] = $sd_post_ctrl->model->list_by_continent($sd_continent["p_id"]);

?>


			<aside id="sidebar">
				<nav id="main-nav">
					<ul>
						<li>
							<a href="<? echo System::make_link("index"); ?>" class="ico-01">HOME</a>
						</li>
                        <li>
							<a href="<? echo System::make_link("institucional"); ?>" class="ico-02">O QUE VI PELO MUNDO</a>
						</li>
                        <li>
							<a href="<? echo System::make_link("noticias"); ?>" class="ico-03">O QUE É NOTÍCIA</a>
						</li>
<?
 $sd_ico = 4;
 foreach($sd_sections as $sd_section) : 
	$sd_section['p_name'] = str_replace("<br>", "", $sd_section['p_name']);
?>
                        
                        <li>
							<a href="#<? echo $sd_section['seo_hash']; ?>" class="ico-0<? echo $sd_ico; ?>"><? echo $sd_section['p_name']; ?></a>
							<ul>
	<?	$sd_c = 0;
        foreach($sd_continents as $sd_continent) : 
            $sd_c++;	
?>
                           
								<li class="link-0<? echo $sd_c; ?>">
									<a href="<? echo System::make_link("continente", $sd_continent["p_id"],  $sd_continent["p_name"]); ?>"><? echo $sd_continent["p_name"]; ?></a>
                                    <!--
									<ul>
	<? 
			foreach($sd_countries[$sd_continent['p_id']] as $sd_country) :
	?>
                                    
										<li><a href="<? echo System::make_link("pais", $sd_country['country_id'], $sd_country['country_name']); ?>#<? echo $sd_section['seo_hash']; ?>">
										<? echo $sd_country['country_name']; ?></a></li>
		<? endforeach; ?>
									</ul>
<-->
								</li>
	<? endforeach; ?>                                
							</ul>
						</li>
<? $sd_ico++; endforeach; ?>                        
                        <li>
							<a href="<? echo System::make_link("o-que-voce-ja-viu"); ?>" class="ico-10">O QUE VOCÊ JÁ VIU</a>
						</li>
						<li>
							<a href="<? echo System::make_link("o-que-voce"); ?>" class="ico-09">O QUE VOCÊ QUER VER?</a>
						</li>
						<li>
							<a href="<? echo System::make_link("videos"); ?>" class="ico-11">VÍDEOS</a>
						</li>
						<li>
							<a href="<? echo System::make_link("na-midia"); ?>" class="ico-12">NA MÍDIA</a>
						</li>
						<li>
							<a href="<? echo System::make_link("colunistas"); ?>" class="ico-13">COLUNISTAS</a>
						</li>
						<li>
							<a href="<? echo System::make_link("contato"); ?>" class="ico-14">CONTATO</a>
						</li>
					</ul>
				</nav><!-- / main-nav -->
				<div class="info">
					<div class="box">
						<div class="img">
							<a href="<? echo System::make_link("colunistas"); ?>"><img src="/images/img-01.jpg" alt="image" /></a>
						</div><!-- / img -->
						<div class="block">
							<a href="<? echo System::make_link("colunistas"); ?>"><strong class="title">PAULO PANAYOTIS</strong></a>
							<p><a href="<? echo System::make_link("colunistas"); ?>">É jornalista e atual Presidente do Centro Cultura Kaváfis.</a> </p>
						</div><!-- / block -->
					</div><!-- / box -->
					<div class="time">
						<div class="clock">
							<div id="clock">
								<img src="/images/img-02.png" alt="image" />
								<div id="secHand">
									<img src="/images/img-26.png" alt="image" />
								</div>
								<div id="hourHand">
									<img src="/images/img-27.png" alt="image" />
								</div>
								<div id="minHand">
									<img src="/images/img-28.png" alt="image" />
								</div>
							</div>
						</div><!-- / clock -->
						<div class="title">
							<span>A HORA</span>
							<strong>AGORA</strong>
						</div><!-- / title -->
						<p>Horário Local: <span><span class="real-hours">11</span>h<span class="real-minutes">13</span></span></p>
					</div><!-- / time -->
				</div><!-- / info -->
				<div class="search-time">
					<form action="#">
						<fieldset>
							<strong class="title">QUER SABER A HORA CERTA EM ALGUMA CIDADE?</strong>
							<div class="input">
								<input class="txt" type="text" value="Digite o nome da cidade" />
							</div><!-- / input -->
							<div class="row">
								<input class="btn-01" type="submit" value="BUSCAR" />
							</div><!-- / row -->
						</fieldset>
					</form>
				</div><!-- / search-time -->
				<div class="news">
					<div class="title">
						<span>NEWSLETTER</span>
						<strong>NOVIDADES POR EMAIL</strong>
					</div><!-- / title -->
					<p>Cadastre seu e-mail para ficar por dentro das atualizações do portal:</p>
					<div class="form">
						<form action="#">
							<fieldset>
								<div class="input"><input class="txt" type="text" value="Digite seu email" /></div>
								<input class="btn-01" type="submit" value="ok" />
							</fieldset>
						</form>
					</div><!-- / form -->
				</div><!-- / news -->
				<div class="widget">
					<div class="fb-like-box" data-href="https://www.facebook.com/FacebookDevelopers" data-width="100%" data-height="170" data-show-faces="true" data-stream="false" data-show-border="true" data-header="false"></div>
				</div><!-- / widget -->
			</aside><!-- / sidebar -->
