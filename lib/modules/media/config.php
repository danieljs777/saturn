<?
$module_config['table_name'] = "media";
$module_config['id_column'] = "m_id";
$module_config['object'] = 'Mídia';
$module_config['default_action'] = 'list_page';

$module_config['file_id_column']   = "img_id";
$module_config['file_path_column'] = "image";

$module_fields["title"]        = array(0, 100, 0, "ALPHA", "Título", "", "textfield");
$module_fields["description"]  = array(0, 2000, 0, "CALPHA", "Conteúdo", "", "html");
$module_fields["link"]         = array(1, 200, 0, "ALPHA", "Link", "http://", "textfield");
$module_fields["category"]     = array(1, 9, 0, "NUM", "Categoria", "1", "combo");
$module_fields["m_date"]       = array(0, 12, 0, "DATE", "Data", "", "textfield");

