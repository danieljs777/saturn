<?
$module_config['table_name'] = "banners";
$module_config['id_column'] = "banner_id";
$module_config['object'] = 'Banner';
$module_config['default_action'] = 'list_page';

$module_config['file_id_column'] = "img_id";
$module_config['file_path_column'] = "image";

$module_fields["ins_date"]     = array(1, 50, 0, "DATE", "Data Inclusão", date("Y-m-d H:i:s"), "text");
$module_fields["type"]         = array(0, 9, 0, "NUM", "Tipo", "", "combo");
$module_fields["title"]        = array(1, 50, 0, "ALPHA", "Título", "", "textfield");
$module_fields["subtitle"]     = array(1, 100, 0, "ALPHA", "Sub-Título", "", "textfield");
$module_fields["link"]         = array(1, 100, 0, "ALPHA", "Link", "http://", "textfield");
$module_fields["position"]     = array(0, 9, 0, "NUM", "Posição", "1", "textfield");
$module_fields["text"]         = array(1, 9, 0, "NUM", "Texto", "", "combo");



	
