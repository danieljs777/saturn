<?
$news_ctrl      = System::load_controller("news");
$post_ctrl      = System::load_controller("post");
$video_ctrl     = System::load_controller("video");
$banner_ctrl    = System::load_controller("banner");
$city_ctrl      = System::load_controller("city");
$params_ctrl    = System::load_controller("parameter");
	
$news       = $news_ctrl->show_list_random(3, array("publish_at" => "sql:< NOW()"));
$videos     = $video_ctrl->show_list(array(), "v_id desc");
$videos_top = $video_ctrl->show_list_random(3, array());
$posts_food = $post_ctrl->show_list_random(1, array('section' => 15, 'highlight' => 1, "publish_at" => "sql:< NOW()"));
$posts_lugg = $post_ctrl->show_list_random(1, array('section' => 17, 'highlight' => 1, "publish_at" => "sql:< NOW()"));

$podcast_top = $city_ctrl->model->list_random_podcast();

$banners     = $banner_ctrl->show_list(array(), "position asc");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, target-densitydpi=device-dpi" >
	<title>O Que Vi Pelo Mundo - Portal de Turismo e Viagens</title>
	<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/clock.css" media="all" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="js/jquery.flexslider-min.js"></script>
	<script type="text/javascript" src="js/jquery.main.js"></script>
	<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
	<script type="text/javascript" src="js/font-size.js"></script>
	<script type="text/javascript" src="js/clear-inputs.js"></script>
	<script type="text/javascript" src="js/clock.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<link rel="stylesheet" media="all" type="text/css" href="css/ie.css" />
	<![endif]-->
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];	
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_PT/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<div class="wrapper">
		<header>
		<? include "_header.php"; ?>
		</header><!-- / header -->
		<div id="main">
			<? include "_sidebar.php"; ?>
			<div id="content">
				<section class="two-columns">
					<div class="col-01">
						<div class="gallery">
							<ul class="slides">
<? foreach($banners as $banner) : 
		$image     = $banner_ctrl->model->get_file(array('banner_id' => $banner['banner_id']));
		
		
?>
								<li>
                                <? 
								switch($banner['type'])
								{
									case 21:
									{										
										echo "<a href='http://www.youtube.com/embed/" . String::get_youtube_id($banner['link']) . "' class='fancybox fancybox.iframe'>";
										break;
									}
									case 20:
									{
										echo "<a href='" . $banner['link'] . "' target='_blank'>";
										break;
									}
									case 19:
									{
										echo "<a href='" . $banner['link'] . "'>";
										break;
									}
									default:
									{
										
									}
								}
?>
										<img src="<? echo $image['image']; ?>" alt="<? echo $banner['title']; ?>" />
										<span class="block">
											<strong class="title"><? echo mb_strtoupper($banner['title'], 'UTF-8'); ?></strong>
											<strong><? echo strtoupper($banner['subtitle']); ?></strong>
											<span class="btn-02"><? echo mb_strtoupper($params_ctrl->model->get_label($banner['text']), 'UTF-8'); ?></span>
										</span><!-- / block -->
									<? echo "</a>"; ?>
								</li>
<? endforeach; ?>                                
							</ul><!-- / slides -->
						</div><!-- / gallery -->
						<div class="map">
							<div class="holder">
								<div class="title">
									<strong>NAVEGUE PELO</strong>
									<h2>MAPA MUNDI</h2>
								</div><!-- / title -->
								<div class="img">
									<img src="images/img-0005.jpg" usemap="#navigation" alt="image" />
								</div><!-- / img -->
								<map name="navigation" id="navigation">
									<area id="map-01" shape="poly" coords="0,72,6,68,6,61,6,52,9,45,16,40,17,37,24,33,35,33,47,33,57,33,68,33,69,24,75,20,88,14,100,11,111,7,124,5,133,4,144,1,154,1,162,1,178,4,191,5,206,4,219,4,220,13,218,20,213,27,213,33,213,43,220,44,221,53,217,56,208,56,205,55,204,50,202,47,200,45,195,47,187,53,181,58,177,65,173,61,165,55,162,52,156,54,157,63,164,69,169,74,175,87,171,91,161,91,159,91,151,96,144,101,139,107,135,111,132,113,131,118,121,122,114,123,109,126,100,127,93,128,89,120,83,115,79,109,75,102,72,92,70,85,65,79,62,75,56,69,49,65,40,65,35,68,25,74,17,79,8,79,3,79" href="#" alt="" title="" />
									<area id="map-02" shape="poly" coords="134,155,136,150,142,145,149,145,159,150,169,158,177,164,185,169,191,171,191,180,187,185,184,197,178,202,175,205,168,216,162,220,157,229,154,236,150,241,151,248,151,254,147,257,140,253,137,244,136,232,138,225,141,212,141,202,141,193,138,189,134,187,133,184,131,177,127,169,127,162,130,155" href="#" alt="" title="" />
									<area id="map-03" shape="poly" coords="223,96,223,93,228,89,229,84,224,80,221,74,224,68,229,63,233,58,242,54,248,51,253,42,260,37,272,35,281,35,292,39,299,41,311,39,319,39,326,46,328,56,328,69,325,74,321,78,314,82,305,81,301,80,298,79,291,79,286,83,280,85,278,91,278,96,275,99,270,103,264,105,260,104,255,104,247,102,242,102,237,103,231,106,225,105" href="#" alt="" title="" >
									<area id="map-04" shape="poly" coords="327,57,328,54,326,47,326,43,320,40,312,40,307,38,302,31,307,21,315,18,327,18,331,26,341,24,347,20,349,14,353,5,362,7,373,13,382,15,389,23,401,27,414,27,424,21,439,25,440,34,455,34,469,37,487,43,478,59,472,59,462,64,452,69,451,80,444,85,432,95,425,105,415,114,407,119,403,124,402,137,404,148,407,156,407,164,404,173,401,179,395,179,388,179,380,177,372,173,368,167,361,159,359,151,361,144,358,139,351,137,347,142,343,156,339,152,330,146,329,140,326,131,323,126,319,124,316,132,313,134,308,139,301,141,295,141,291,137,288,132,286,127,283,119,277,112,279,107,276,105,270,105,268,102,267,98,273,95,276,89,277,86,285,84,288,81,293,78,300,79,310,83,319,81,325,78,331,73,331,66,331,63" href="#" alt="" title="" />
									<area id="map-05" shape="poly" coords="224,107,231,104,244,103,255,105,263,109,272,111,278,111,283,117,286,127,296,142,306,142,307,151,301,158,297,165,293,171,294,180,302,177,307,181,306,193,303,200,300,205,292,204,287,195,285,200,280,209,272,216,263,219,259,213,256,205,253,195,252,185,251,175,251,167,246,161,242,153,235,154,226,158,222,158,219,151,212,142,211,131,211,124,215,118,220,113" href="#" alt="" title="" >
									<area id="map-06" shape="poly" coords="384,195,390,190,397,186,404,180,413,176,411,171,406,166,407,160,415,160,425,165,433,165,440,167,447,169,459,180,462,192,470,205,478,216,475,224,470,231,463,238,455,245,448,243,448,233,452,225,453,217,455,204,452,198,446,185,436,181,430,183,432,191,437,196,437,209,437,216,435,223,432,230,429,234,421,234,416,226,413,216,404,214,399,217,392,222,383,219,383,212,383,200" href="#" alt="" title=""   />
									<area id="map-07" shape="poly" coords="104,131,110,130,119,129,121,124,131,124,140,128,148,133,146,138,140,141,138,143,134,145,131,150,125,153,122,148,114,143,111,140,102,137,102,133" href="#" alt="" title=""   />
								</map>
								<div id="hover-01" class="hover">
									<a href="<? echo System::make_link("continente", 5, "América do Norte"); ?>"><img src="images/map-01.png" alt="image" width="248" height="139" /></a>
								</div><!-- / hover -->
								<div id="hover-02" class="hover">
									<a href="<? echo System::make_link("continente", 3, "Ámérica do Sul"); ?>"><img src="images/map-02.png" alt="image" width="93" height="126" /></a>
								</div><!-- / hover -->
								<div id="hover-03" class="hover">
									<a href="<? echo System::make_link("continente", 6, "Europa"); ?>"><img src="images/map-03.png" alt="image" width="113" height="88" /></a>
								</div><!-- / hover -->
								<div id="hover-04" class="hover">
									<a href="<? echo System::make_link("continente", 7, "Ásia"); ?>"><img src="images/map-04.png" alt="image" width="242" height="183" /></a>
								</div><!-- / hover -->
								<div id="hover-05" class="hover">
									<a href="<? echo System::make_link("continente", 8, "África"); ?>"><img src="images/map-05.png" alt="image" width="115" height="123" /></a>
								</div><!-- / hover -->
								<div id="hover-06" class="hover">
									<a href="<? echo System::make_link("continente", 9, "Oceânia"); ?>"><img src="images/map-06.png" alt="image" width="104" height="87" /></a>
								</div><!-- / hover -->
								<div id="hover-07" class="hover">
									<a href="<? echo System::make_link("continente", 4, "América Central"); ?>"><img src="images/map-07.png" alt="image" width="66" height="38" /></a>
								</div><!-- / hover -->
								<div id="tooltip-01" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 5, "América do Norte"); ?>">América do Norte</a></h3>
								</div><!-- / tooltip -->
								<div id="tooltip-02" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 3, "Ámérica do Sul"); ?>">Ámérica do Sul</a></h3>
								</div><!-- / tooltip -->
								<div id="tooltip-03" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 6, "Europa"); ?>">Europa</a></h3>
								</div><!-- / tooltip -->
								<div id="tooltip-04" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 7, "Ásia"); ?>">Ásia</a></h3>
								</div><!-- / tooltip -->
								<div id="tooltip-05" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 8, "África"); ?>">África</a></h3>
								</div><!-- / tooltip -->
								<div id="tooltip-06" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 9, "Oceânia"); ?>">Oceânia</a></h3>
								</div><!-- / tooltip -->
								<div id="tooltip-07" class="tooltip">
									<span class="arrow">arrow</span>
									<h3><a href="<? echo System::make_link("continente", 4, "América Central"); ?>">AMÉRICA CENTRAL</a></h3>
								</div><!-- / tooltip -->
							</div><!-- / holder -->
						</div><!-- / map -->
					</div><!-- / col-01 -->
					<div class="col-02">
						<div class="video">
							<h2>VÍDEO</h2>
							<div class="flexslider">
								<ul class="slides">
<? foreach($videos_top as $video_top) : 
		$image     = $video_ctrl->model->get_file(array('v_id' => $video_top['v_id']));
?>
									<li>
										<div class="img">
											<a href="http://www.youtube.com/embed/<? echo String::get_youtube_id($video_top['video_url']); ?>" class="fancybox fancybox.iframe">
												<span class="play">play</span>
												<img src="<? echo $image['image']; ?>" alt="<? echo $video_top['v_title']; ?>" />
											</a>
										</div><!-- / img -->
										<strong class="title"><? echo $video_top['v_title']; ?></strong>
									</li>
<? endforeach;

?>
								</ul><!-- / slides -->
							</div><!-- / flexslider -->
						</div><!-- / video -->
<?
$ads_ctrl       = System::load_controller("advertising");

$ads            = $ads_ctrl->show_by_area(37, 1);

if(System::is_filled($ads)) : 
	$image = $ads_ctrl->model->get_files(array('ad_id' => $ads[0]['ad_id']), "RAND()");

?>
                        
						<div class="ad">
							<img src="<? echo $image[0]['image']; ?>" alt="image" />
						</div><!-- / ad -->
<? endif; ?>
                        
                        <? if(System::is_filled($podcast_top)) : ?>
						<div class="music">
							<div class="podcast">
								<div class="title">
									<strong>OUÇA NOSSO</strong>
									<h2>PODCAST</h2>
								</div><!-- / title -->
								<div class="area">
									<div class="number">
										<strong><? echo Date::format($podcast_top['pod_date'], "d"); ?></strong>
										<span><? echo Date::get_abbrev_month_name($podcast_top['pod_date']); ?></span>
									</div><!-- / number -->
									<div class="block">
										<p><a href="noticia"><? echo $podcast_top['title']; ?></a></p>
									</div><!-- / block -->
								</div><!-- / area -->
								<div class="player">
									<div id="jplayer-01" class="jp-jplayer" data-mp3="<? echo $podcast_top['podcast_file']; ?>"></div>

									<div id="jp_container_1" class="jp-audio">
										<div class="jp-type-single">
											<div class="jp-gui jp-interface">
												<div class="jp-progress">
													<div class="jp-seek-bar">
														<div class="jp-play-bar"></div>
													</div>
												</div>
												<ul class="jp-controls">
													<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
													<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
												</ul>
												<div class="jp-time-holder">
													<div class="jp-current-time"></div>
													<span>/</span>
													<div class="jp-duration"></div>
												</div>
											</div>
										</div>
									</div>
								</div><!-- / player -->
							</div><!-- / holder -->
						</div><!-- / music -->
                        <? endif; ?>
					</div><!-- / col-02 -->
				</section><!-- / two-columns -->
				<div class="videos">
					<div class="title">
						<a href="<? echo System::make_link("videos"); ?>" class="btn-03">VEJA A GALERIA COMPLETA</a>
						<strong>ASSISTA AOS</strong>
						<h2>VÍDEOS</h2>
					</div><!-- / title -->
					<div class="carousel">
						<div class="wrap">
							<ul class="list">
<? foreach($videos as $video) : 
		$image     = $video_ctrl->model->get_file(array('v_id' => $video['v_id']));
?>                            
								<li>
									<div class="img">
										<a href="http://www.youtube.com/embed/<? echo String::get_youtube_id($video['video_url']); ?>" class="fancybox fancybox.iframe"><img src="<? echo $image['image']; ?>" alt="image" /></a>
									</div><!-- / img -->
									<strong class="ttl"><? echo $video['v_title']; ?></strong>
								</li>
<? endforeach; ?>                               
							</ul><!-- / slides -->
						</div><!-- / wrap -->
						<a href="#" class="next">next</a>
						<a href="#" class="prev">prev</a>
					</div><!-- / flexslider -->
				</div><!-- / videos -->
				<ul class="columns">
					<li>
						<div class="title">
							<strong>NOVIDADES</strong>
							<h3>O QUE É NOTÍCIA</h3>
							<span>Todas as novidades do portal você encontra aqui</span>
						</div><!-- / title -->
						<ul class="list">
<? foreach($news as $new) : ?>
							<li>
								<div class="number">
                                <? if($new['news_date'] != "") : ?>
									<strong><? echo Date::format($new['news_date'], "d"); ?></strong>
									<span><? echo Date::get_abbrev_month_name($new['news_date']); ?></span>
                                <? endif; ?>
								</div><!-- / number -->
								<p><a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>"><? echo $new['title']; ?></a></p>
							</li>
<? endforeach; ?>
						</ul>
						<a href="<? echo System::make_link("noticias"); ?>" class="btn-03">VER TODAS</a>
					</li>
					<li>
						<div class="title">
							<strong>GASTRONOMIA</strong>
							<h3>O QUE COMER</h3>
							<span>Lugares imperdíveis para aguçar seu paladar</span>
						</div><!-- / title -->
<? foreach($posts_food as $post) : 
	$image     = $post_ctrl->model->get_file(array('p_id' => $post['p_id'], 'is_cover' => 1));
	$city      = $city_ctrl->show_by_criteria(array("city_name" => $post['city_id'])); 
	
?>
						<div class="img">
							<a href="<? echo System::make_link("cidade", $city['city_id'], $city['city_name']); ?>#o-que-comer-e-beber"><img src="<? echo $image['image']; ?>" alt="<? echo $post['title']; ?>" /></a>
						</div><!-- / img -->
						<div class="text">
							<p><a href="<? echo System::make_link("cidade", $city['city_id'], $city['city_name']); ?>#o-que-comer-e-beber"><? echo $post['title']; ?></a></p>
						</div><!-- / text -->
						<a href="<? echo System::make_link("cidade", $city['city_id'], $post['city_id']); ?>#o-que-comer-e-beber" class="btn-03">VEJA MAIS</a>
<? endforeach; ?>                       
					</li>
					<li>
						<div class="title">
							<strong>GUIA DE VIAGEM</strong>
							<h3>O QUE LEVAR</h3>
							<span>Não vá despreparado. Prepamos um guia pra você</span>
						</div><!-- / title -->
<? foreach($posts_lugg as $post) : 
	$image     = $post_ctrl->model->get_file(array('p_id' => $post['p_id'], 'is_cover' => 1));
	$city      = $city_ctrl->show_by_criteria(array("city_name" => $post['city_id'])); 
	
?>                                            
						<div class="img">
							<a href="<? echo System::make_link("cidade", $city['city_id'], $post['city_id']); ?>#o-que-levar">
                            <img src="<? echo $image['image']; ?>" alt="<? echo $post['title']; ?>" /></a>
						</div><!-- / img -->
						<div class="text">
							<p><a href="<? echo System::make_link("cidade", $city['city_id'], $post['city_id']); ?>#o-que-levar"><? echo $post['title']; ?></a> </p>
						</div><!-- / text -->
						<a href="<? echo System::make_link("cidade", $city['city_id'], $post['city_id']); ?>#o-que-levar" class="btn-03">VEJA MAIS</a>
<? endforeach; ?>                       
					</li>
				</ul><!-- / columns -->
			</div><!-- / content -->
		</div><!-- / main -->
		<footer>
		<? include "_footer.php"; ?>
        </footer><!-- / footer -->
	</div><!-- / wrapper -->
</body>
</html>