<?
$module_config['table_name'] = "advertisings";
$module_config['id_column'] = "ad_id";
$module_config['object'] = 'Anúncios';
$module_config['default_action'] = 'list_page';

$module_config['file_id_column'] = "img_id";
$module_config['file_path_column'] = "image";

$module_fields["ins_date"]     = array(1, 50, 0, "DATE", "Data Inclusão", date("Y-m-d H:i:s"), "text");
$module_fields["position"]     = array(0, 9, 0, "NUM", "Posição", "", "combo");
$module_fields["title"]        = array(1, 50, 0, "ALPHA", "Título", "", "textfield");
$module_fields["link"]         = array(1, 100, 0, "ALPHA", "Link", "http://", "textfield");
$module_fields["enabled"]      = array(1, 1, 0, "BOOL", "Ativo", "1", "checkbox");

$module_config['img_type']       = array( 
										"lateral-direita"  =>  array("width" => 267, "height" => 54),
										"lateral-esquerda" =>  array("width" => 267, "height" => 54),
										"institucional"    =>  array("width" => 235, "height" => 120),
										"rodape---parceiros" =>  array("width" => 163, "height" => 60),
										"rodape---patrocinadores" =>  array("width" => 163, "height" => 60)
										);



	
