<?
$columnist_ctrl = System::load_controller("columnist");
$news_ctrl      = System::load_controller("news");
$city_ctrl      = System::load_controller("city");
$param_ctrl     = System::load_controller("parameter");
	
$new_id     = $objid;

$news       = $news_ctrl->show($new_id);
$image      = $news_ctrl->model->get_file(array('news_id' => $news['news_id'], 'is_cover' => 1));
$columnist  = $columnist_ctrl->model->get_info($news['columnist_id']);
$city       = $city_ctrl->model->get_city_name($news['city_id']);
$category   = $news_ctrl->model->get_reg_value($news['category_id']);
$images     = $news_ctrl->model->list_files($news['news_id'], "image", "news_id", array('is_cover' => 0));
$podcasts   = $news_ctrl->model->list_podcasts($news['news_id']);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, target-densitydpi=device-dpi" >
	<title>O Que Vi Pelo Mundo - <? echo $news['title']; ?></title>
	<link rel="stylesheet" type="text/css" href="/css/clock.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/css/form.css" media="all" />
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
						<li>O QUE É NOTÍCIA</li>
					</ul><!-- / breadcrumbs -->
					<div class="head-area">
						<h1>O QUE É NOTÍCIA</h1>
					</div><!-- / heading -->
					<div class="content-holder">
						<div class="info-block">
							<div class="img">
								<img src="<? echo $image['thumb']; ?>" alt="<? echo $news['title']; ?>" />
							</div><!-- / img -->
							<div class="block">
								<h2><? echo $news['title']; ?></h2>
								<div class="info">
                                	<? if(System::is_filled($news['news_date'])) : ?>
									<span class="date"><? echo Date::format($news['news_date']); ?></span>
                                    <? endif; ?>
                                    
                                    <? if (System::is_filled($columnist)) : ?>
									<span>Por: <strong><? echo $columnist['c_name']; ?></strong></span>
                                    <? endif; ?>
									<span>Categoria: <strong><? echo ($category); ?></strong></span>
								</div><!-- / info -->
							</div><!-- / block -->
						</div><!-- / info-area -->

                        <article class="article">

                            <? echo ($news['description']); ?>
                            
                            <? if($news["link_youtube"] != "") : ?>
                            <iframe width="814" height="458" src="http://www.youtube.com/embed/<? echo String::get_youtube_id($news["link_youtube"]); ?>" frameborder="0" allowfullscreen></iframe>
                            <? endif; ?>
                        
                            <? foreach($podcasts as $podcast): ?>
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
                
                            <? if(System::is_filled($images)) : ?>
                            
                            <div class="heading ico-01" style="margin-top: 15px;">
                                        <h2>GALERIA DE IMAGENS</h2>
                                        <span>CLIQUE PARA AMPLIAR AS IMAGENS</span>
                                    </div><!-- / heading -->
                                    <div class="videos">
                                        <div class="carousel" style="height: 104px;">
                                            <div class="wrap">
                                                <ul class="list">
                                                <? foreach($images as $image): ?>
                                                    <li>
                                                        <div class="img">
                                                            <a class="fancybox" data-fancybox-group="gallery" href="<? echo $image['image']; ?>" title="<? //echo $news['title']; ?>"><img width="155" height="104" src="<? echo $image['thumb']; ?>" alt="<? echo $news['title']; ?>" /></a>
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
                        </article><!-- / text -->

						<article class="article">
							<p></p>
							<div class="facebook-holder">
								<h3>COMENTÁRIOS DO FACEBOOK</h3>
								<div class="fb-comments" data-href="http://developers.facebook.com/" data-width="100%" data-num-posts="2"></div>
							</div>
						</article><!-- / text -->
						<div class="btn-block">
							<a href="<? echo System::make_link("noticias"); ?>" class="btn-02">VER TODAS AS NOTÍCIAS</a>
						</div><!-- / btn-block -->
					</div><!-- / content -->
				</div><!-- / container -->
			</div><!-- / content -->
		</div><!-- / main -->
		<footer>
<? include APP_ROOT . "/site/_footer.php"; ?>
</footer><!-- / footer -->
	</div><!-- / wrapper -->
</body>
</html>