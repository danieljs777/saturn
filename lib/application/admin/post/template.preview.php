<?

$param_ctrl     = System::load_controller("parameter");
$post_ctrl      = System::load_controller("post");
$news_ctrl      = System::load_controller("news");
$video_ctrl     = System::load_controller("video");
$city_ctrl      = System::load_controller("city");
$country_ctrl   = System::load_controller("country");

if(!isset($objid))
	header("Location: /");

$post_id        = System::get_value($objid);

$_post          = $post_ctrl->show($post_id);

$city_id        = $_post['city_id'];
$city           = $city_ctrl->show($_post['city_id']);
$sections       = $param_ctrl->show_list(array("t_id" => 4), "p_id");
$country        = $country_ctrl->show($city['country_id']);
$continent      = $param_ctrl->show($country['continent_id']);

$images         = $city_ctrl->model->list_files($city_id, "image", "city_id", array("type" => 0));
$image_profile  = $city_ctrl->model->list_files($city_id, "image", "city_id", array("type" => 2));
$image_bg       = $city_ctrl->model->list_files($city_id, "image", "city_id", array("type" => 1));

$podcasts       = $city_ctrl->model->list_podcasts($city_id);

$last_news      = $news_ctrl->show_list_page(array("city_id" => $city_id), 1, 3, "news_id desc");
$news           = $news_ctrl->show_list(array("city_id" => $city_id));
$videos         = $video_ctrl->show_list(array("city_id" => $city_id));

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, target-densitydpi=device-dpi" >
	<title>O Que Vi Pelo Mundo: <? echo $city['city_name']; ?></title>
	<link rel="stylesheet" type="text/css" href="/css/clock.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/css/styles.css" media="all" />
	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="/js/jquery.flexslider-min.js"></script>
	<script type="text/javascript" src="/js/jquery.main.js"></script>
	<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
	<script type="text/javascript" src="/js/font-size.js"></script>
	<script type="text/javascript" src="/js/clear-inputs.js"></script>
	<script type="text/javascript" src="/js/clock.js"></script>
	<script type="text/javascript" src="/js/scripts.js"></script>
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
		<? include APP_ROOT . "site/_header.php"; ?>
		</header><!-- / header -->
		<div id="main">
			<? include APP_ROOT . "site/_sidebar.php"; ?>
			<div id="content">
				<div class="container">
					<ul class="breadcrumbs">
						<li><a href="<? echo System::make_link("index"); ?>">HOME</a></li>
						<li><a href="<? echo System::make_link("institucional"); ?>">O QUE VI PELO MUNDO</a></li>
						<li><a href="<? echo System::make_link("continente", $continent["p_id"],  $continent["p_name"]); ?>"><? echo mb_strtoupper($continent['p_name'], 'UTF-8'); ?></a></li>
						<li><a href="<? echo System::make_link("pais", $country["country_id"],  $country["country_name"]); ?>"><? echo mb_strtoupper($country['country_name'], 'UTF-8'); ?></a></li>
						<li><? echo mb_strtoupper($city['city_name'], 'UTF-8'); ?></li>
					</ul>
					<div class="visual">
						<h1><? echo mb_strtoupper($city['city_name'], 'UTF-8'); ?></h1>
						<img src="<? echo $image_bg[0]['image']; ?>" alt="image">
						<a href="#" class="img"><img src="<? echo $image_profile[0]['image']; ?>" alt="image" width="127" height="127" ></a>
					</div><!-- / visual -->
					<div class="holder">
						<div class="tabset">
							<ul class="tabnav">
								<li><a href="#principal"><i class="ico ico-01"></i><span>PRINCIPAL</span></a></li>                            
                            <?
							 $sd_ico = 2;
							 foreach($sections as $section) : 
								$splitted = String::split_half(mb_strtoupper($section['p_name'], 'UTF-8'));
								
							 ?>
								<li><a href="#<? echo $section['seo_hash']; ?>"><i class="ico ico-0<? echo $sd_ico; ?>"></i>
                                <span><? echo $splitted[0] . "<br>" . $splitted[1] ;  ?></span></a></li>
							<? $sd_ico++; endforeach; ?>
								<li><a href="#noticias"><i class="ico ico-07"></i><span>NOTÍCIAS</span></a></li>
                            
							</ul><!-- / tabnav -->
                            
							<div class="tab" id="principal">
								<p><? echo $city['description']; ?></p>
                                
								<? if (System::is_filled($images)) : ?>
                                
								<div class="heading ico-01">
									<strong>GALERIA DE IMAGENS</strong>
									<span>CLIQUE PARA AMPLIAR AS IMAGENS</span>
								</div><!-- / heading -->
								<div class="videos">
									<div class="carousel">
										<div class="wrap">
										<? foreach($images as $img) : ?>
											<ul class="list">
												<li>
													<div class="img">
														<a class="fancybox" data-fancybox-group="gallery" href="<? echo $img['image']; ?>" title="<? echo $city['city_name']; ?>">
                                                        <img src="<? echo $img['thumb']; ?>" alt="<? echo $city['city_name']; ?>" /></a>
													</div><!-- / img -->
												</li>
										<? endforeach; ?>
											</ul><!-- / slides -->
										</div><!-- / wrap -->
										<a href="#" class="next">next</a>
										<a href="#" class="prev">prev</a>
									</div><!-- / flexslider -->
								</div><!-- / videos -->
                                
                                <? endif; ?>
                                
                                <? if(System::is_filled($news)) : ?>
                                
								<div class="heading ico-02">
									<strong>ÚLTIMAS NOTÍCIAS</strong>
								</div><!-- / heading -->
                                
								<? foreach($last_news as $new) : 
										$new_image     = $news_ctrl->model->get_file(array('news_id' => $new['news_id'], 'is_cover' => 1));
								?>
								<article>
									<a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>"><img src="<? echo $new_image['thumb']; ?>" alt="<? echo $new['title']; ?>"></a>
									<div class="box">
										<a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>"><strong class="ttl"><? echo $new['title']; ?></strong></a>
										<span class="date"><? echo Date::format($new['news_date']); ?></span>
										<a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>" class="btn-02">LEIA A MATÉRIA</a>
									</div>
								</article>
                                <? endforeach; ?>
                                
                                <? endif; ?>

								<div class="facebook-holder">
									<h3>COMENTÁRIOS DO FACEBOOK</h3>
                                    <? if(false) { ?>
									<div class="fb-comments" data-href="<? echo 'http://' . HOST_SITE . $_SERVER['REQUEST_URI']; ?>" data-width="544" data-num-posts="2"></div>
                                    <? } ?>
                                    
									<div class="fb-comments" data-href="http://developers.facebook.com/" data-width="100%" data-num-posts="2"></div>
								</div>
							</div><!-- / tab-01 -->
                            
                            <? 
							$sd_ico = 3;
							foreach($sections as $section) : ?>
                            
							<div class="tab" id="<? echo $section['seo_hash']; ?>">
								<div class="heading ico-0<? echo $sd_ico; ?>">
									<strong><? echo mb_strtoupper($section['p_name'], 'UTF-8') ?></strong>
								</div><!-- / heading -->
								<div class="post-holder">
                                <? $section_posts = $post_ctrl->show_list(array("city_id" => $city_id, "section" => $section['p_id'])); 
                                   foreach($section_posts as $post) : 
								   		$post_image_cover = $post_ctrl->model->get_file(array('p_id' => $post['p_id'], 'is_cover' => 1));
								   		$post_podcast     = $post_ctrl->model->list_podcasts($post['p_id']);
										$post_images      = $post_ctrl->model->list_files($post['p_id'], "image", "p_id", array('is_cover' => 0));

								?>
									<div class="post">
										<a href="#" class="btn_details" id="<? echo $post['p_id']; ?>"><img src="<? echo $post_image_cover['thumb']; ?>" alt="image"></a>
										<div class="txt-frame">
											<strong class="ttl"><a href="#" class="btn_details" id="<? echo $post['p_id']; ?>"><? echo $post['title']; ?></a></strong>
											<div class="line">
												<span><? echo $post['address']; ?></span>
												<strong class="phone">
												<? if($post['telephone'] != "") : ?>
												<? echo $country['ddi']; ?> <? echo $city['ddd']; ?> <? echo $post['telephone']; ?>
                                                <? endif; ?>
                                                </strong>
											</div>
											<div class="block">
												<a href="<? echo $post['url']; ?>" class="link" target="_blank"><? echo str_replace("http://", "", $post['url']); ?></a>
												<a style="float: right;" class="btn-02 btn_details" id="<? echo $post['p_id']; ?>" href="#">ABRIR/FECHAR</a>
											</div>
										</div>
										<div id="details_<? echo $post['p_id']; ?>" class="maximiza slideToggle" style="display:none;">
												<div class="txt-frame">
													<div class="line">
													
													</div>
													<div class="block">
			
														<? echo nl2br($post['description']); ?>
                                                        
                                                        <? if ($post['link_youtube'] != "") : ?>
														<iframe width="560" height="315" src="http://www.youtube.com/embed/<? echo String::get_youtube_id($post['link_youtube']); ?>" frameborder="0"></iframe>
														<? endif; ?>
                                                        													
														<? foreach($post_podcast as $podcast): ?>
                                                    
														<div class="music">
															<div class="podcast padding">
																<div class="title">
                                                                <h2><? echo $podcast['title']; ?></h2>
                                                                <p><? if(System::is_filled($podcast['pod_date'])) echo Date::format($podcast['pod_date']); ?></p>
																</div><!-- / title -->
																<div class="area">
																	
																</div><!-- / area -->
																<div class="player">
																	<div id="jplayer-01" class="jp-jplayer" data-mp3="<? echo $podcast['podcast_file']; ?>"></div>

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
														<? endforeach; ?>
                                                            
														<? if(System::is_filled($post_images)) : ?>
														<div class="heading ico-01">
															<h2>GALERIA DE IMAGENS</h2>
															<span>CLIQUE PARA AMPLIAR AS IMAGENS</span>
														</div><!-- / heading -->
														
														<div class="videos">
															<div class="carousel" style="height: 104px;">
																<div class="wrap">
																	<ul class="list">
																	<? foreach($post_images as $image): ?>
                                                                    
																		<li>
																			<div class="img">
																				<a class="fancybox" data-fancybox-group="gallery" href="<? echo $image['image']?>" title="">
                                                                                <img src="<? echo $image['thumb']?>" alt="" /></a>
																			</div><!-- / img -->
																		</li>
																	<? endforeach; ?>
																	</ul><!-- / slides -->
																</div><!-- / wrap -->
																<a href="#" class="next">next</a>
																<a href="#" class="prev">prev</a>
															</div><!-- / flexslider -->
														</div><!-- / videos -->
                                                        <? endif; ?>
													</div>
												</div>
										</div>
									</div><!-- / post -->
                                    <? endforeach; ?>

								</div>
								<div class="facebook-holder">
									<h3>COMENTÁRIOS DO FACEBOOK</h3>
									<div class="fb-comments" data-href="http://developers.facebook.com/" data-width="544" data-num-posts="2"></div>
								</div>
							</div><!-- / tab-0<? echo $sd_ico; ?> -->

                            <? $sd_ico++; endforeach; ?>
                            
							<div class="tab" id="noticias">
								<div class="heading ico-08">
									<strong>NOTÍCIAS</strong>
								</div><!-- / heading -->
                                
								<? foreach($news as $new) : 
										$new_image     = $news_ctrl->model->get_file(array('news_id' => $new['news_id'], 'is_cover' => 1));
								?>
								<article>
									<a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>"><img src="<? echo $new_image['thumb']; ?>" alt="<? echo $new['title']; ?>"></a>
									<div class="box">
										<a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>"><strong class="ttl"><? echo $new['title']; ?></strong></a>
										<span class="date"><? echo Date::format($new['news_date']); ?></span>
										<a href="<? echo System::make_link("noticia", $new['news_id'], $new['title']); ?>" class="btn-02">LEIA A MATÉRIA</a>
									</div>
								</article>
                                <? endforeach; ?>
                                
								<div class="facebook-holder">
									<h3>COMENTÁRIOS DO FACEBOOK</h3>
									<div class="fb-comments" data-href="http://developers.facebook.com/" data-width="544" data-num-posts="2"></div>
								</div>
							</div><!-- / tab-07 -->
						</div><!-- / tabset -->
                        
                        
						<div class="right-col">
                        <? if(System::is_filled($videos)) : ?>
							<h3>VÍDEOS</h3>
                            
							<? foreach($videos as $video) : 
									$video_image     = $video_ctrl->model->get_file(array('v_id' => $video['v_id']));
							?>
							<div class="video-block">
								<div class="frame">
									<a href="http://www.youtube.com/embed/<? echo $video['video_url']; ?>" class="fancybox fancybox.iframe">
										<img src="<? echo $video_image['image']; ?>" alt="<? echo $video['v_title']; ?>">
										<span class="play">play</span>
									</a>
								</div>
								<strong class="ttl">
									<a href="#"><? echo $video_ctrl->model->get_reg_value($video['type']); ?></a>
									<span class="time"><? echo $video['length']; ?></span>
								</strong>
							</div><!-- / video-block -->
							<? endforeach; ?>
                            
						<? endif; ?>
                        
                        <? if(System::is_filled($podcasts)) : ?>
							<? foreach($podcasts as $podcast) :  ?>

							<div class="podcast">
								<div class="title">
									<strong>OUÇA NOSSO</strong>
									<h2>PODCAST</h2>
								</div><!-- / title -->
								<div class="area">
									<div class="number">
										<strong><? echo Date::format($podcast['pod_date'], "d"); ?></strong>
										<span><? echo Date::get_abbrev_month_name($podcast['pod_date']); ?></span>
									</div><!-- / number -->
									<div class="block">
										<p><? if($podcast['title'] != "") echo $podcast['title']; ?></p>
									</div><!-- / block -->
								</div><!-- / area -->
								<div class="player">
									<div id="jplayer-01" class="jp-jplayer" data-mp3="<? echo $podcast['podcast_file']; ?>"></div>
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
							</div><!-- / podcast -->
                            
                            <? endforeach; ?>
                            
						<? endif; ?>
                            
							<div class="banner-01">
								<a href="#"><img src="/images/banner-001.jpg" alt="image"></a>
							</div>
							<div class="banner-01">
								<a href="#"><img src="/images/banner-001.jpg" alt="image"></a>
							</div>
						</div><!-- / right-col -->
					</div><!-- / holder -->
				</div><!-- / container -->
			</div><!-- / content -->
		</div><!-- / main -->
		<footer>
<? include APP_ROOT . "site/_footer.php"; ?>
</footer><!-- / footer -->
	</div><!-- / wrapper -->
</body>
</html>