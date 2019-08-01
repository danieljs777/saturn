<?
$ads_ctrl       = System::load_controller("advertising");

$partners       = $ads_ctrl->show_by_area(41, 3);
$investors      = $ads_ctrl->show_by_area(42, 5);

?>
			<div class="holder">
				<div class="col-01">
					<strong class="title">PATROCINADORES</strong>
					<div class="carousel">
						<div class="wrap">
							<ul class="list">
								<? foreach($investors as $investor) : 
										$image = $ads_ctrl->model->get_file(array('ad_id' => $investor['ad_id']));
								?>
								<li style="width:74px;"><a href="#"><img src="<? echo $image['image']; ?>" alt="image" height="89" /></a></li>
								<? endforeach; ?>
							</ul>
						</div><!-- / wrap -->
						<a href="#" class="next">next</a>
						<a href="#" class="prev">prev</a>
					</div><!-- / carousel -->
				</div><!-- / col-01 -->
				<div class="col-02">
					<strong class="title">PARCEIROS</strong>
					<div class="carousel">
						<div class="wrap">
							<ul class="list">
								<? foreach($partners as $partner) : 
										$image = $ads_ctrl->model->get_file(array('ad_id' => $partner['ad_id']));
								?>                            
								<li style="width:115px;"><a href="#"><img src="<? echo $image['image']; ?>" alt="image" height="65" /></a></li>
								<? endforeach; ?>
							</ul>
						</div><!-- / wrap -->
						<a href="#" class="next">next</a>
						<a href="#" class="prev">prev</a>
					</div><!-- / carousel -->
				</div><!-- / col-02 -->
			</div><!-- / holder -->
			<div class="bottom">
				<div class="holder">
					<div class="logo-area">
						<strong class="logo"><a href="#">O que vi pelo MUNDO</a></strong>
						<span>&copy; 2013 - Direitos Reservados</span>
					</div><!-- / logo-area -->
					<strong class="sub-logo"><a href="http://www.tazzoom.com.br">tazzom</a></strong>
					<div class="lists">
						<ul>
							<li><a href="<? echo System::make_link("institucional"); ?>">O QUE VI PELO MUNDO</a></li>
							<li><a href="<? echo System::make_link("noticias"); ?>">O QUE É NOTÍCIA</a></li>
							<li><a href="<? echo System::make_link("cidade"); ?>#tab-02">O QUE É IMPERDÍVEL</a></li>
							<li><a href="<? echo System::make_link("cidade"); ?>#tab-03">O QUE COMER E BEBER</a></li>
						</ul>
						<ul>
							<li><a href="<? echo System::make_link("cidade"); ?>#tab-04">O QUE COMPRAR</a></li>
							<li><a href="<? echo System::make_link("cidade"); ?>#tab-05">O QUE LEVAR</a></li>
							<li><a href="<? echo System::make_link("cidade"); ?>#tab-06">O QUE EVITAR</a></li>
							<li><a href="<? echo System::make_link("cidade"); ?>#tab-07">O QUE VOCÊ QUER VER?</a></li>
						</ul>
						<ul>
							<li><a href="#">O QUE VI PELO MUNDO</a></li>
							<li><a href="<? echo System::make_link("o-que-voce-ja-viu"); ?>">O QUE VOCÊ JÁ VIU</a></li>
							<li><a href="<? echo System::make_link("videos"); ?>">VÍDEOS</a></li>
							<li><a href="<? echo System::make_link("colunistas"); ?>">COLUNISTAS</a></li>
						</ul>
						<ul>
							<li><a href="<? echo System::make_link("contato"); ?>">CONTATO</a></li>
							<li><a href="<? echo System::make_link("contato"); ?>">ANUNCIE</a></li>
						</ul>
					</div>
				</div><!-- / holder -->
			</div><!-- / bottom -->
