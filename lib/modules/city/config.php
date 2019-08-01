<?
$module_config['table_name'] = "cities";
$module_config['id_column'] = "city_id";
$module_config['object'] = 'Cidades';
$module_config['default_action'] = 'list_page';
$module_config['sidebar']        = true;

$module_config['file_id_column'] = "img_id";
$module_config['file_path_column'] = "image";


$module_config['img_type']       = array(
										array("label" => "Galeria", "width" => 700, "height" => 470, "thumb_width" => 155, "thumb_height" => 104),
										array("label" => "Fundo", "width" => 870, "height" => 205),
										array("label" => "Perfil", "width" => 127, "height" => 127),
										array("label" => "Destaque", "width" => 235, "height" => 132)
										);

$module_fields["country_id"]     = array(0, 9, 0, "NUM", "País", "", "combo", "", "", null, "country");
$module_fields["city_name"]      = array(0, 150, 0, "ALPHA", "Nome", "", "textfield");
$module_fields["ddd"]            = array(1, 60, 0, "ALPHA", "DDD", "", "textfield");
//$module_fields["region"]         = array(1, 100, 0, "ALPHA", "Região", "", "textfield");
//$module_fields["timezone"]       = array(1, 50, 0, "ALPHA", "Fuso Horário", "", "text");
$module_fields["description"]    = array(0, 3000, 0, "CALPHA", "Descrição", "", "html");
$module_fields["preview"]        = array(1, 1, 0, "BOOL", "Rascunho", 0, "checkbox", "Desmarque para ativar a exibição no site");


