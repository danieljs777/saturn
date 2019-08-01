<?
$module_config['table_name'] = "videos";
$module_config['id_column'] = "v_id";
$module_config['object'] = 'Vídeo';
$module_config['default_action'] = 'list_page';

$module_config['file_id_column'] = "file_id";
$module_config['file_path_column'] = "image";


$module_fields["v_title"]    = array(0, 150, 0, "ALPHA", "Título", "", "textfield");
$module_fields["city_id"]    = array(1, 9, 0, "NUM", "Cidade", System::is_not_empty($_REQUEST["city_id"]), "combo");
$module_fields["length"]     = array(1, 8, 0, "ALPHA", "Duração", "00:00:00", "textfield");
$module_fields["type"]       = array(1, 9, 0, "NUM", "Tipo", 0, "combo");
$module_fields["video_url"]  = array(0, 100, 0, "ALPHA", "Link", "", "textfield", "");


