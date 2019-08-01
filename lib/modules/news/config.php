<?
$module_config['table_name']       = "news";
$module_config['id_column']        = "news_id";
$module_config['object']           = 'Notícia';
$module_config['default_action']   = 'list_page';

$module_config['file_id_column']   = "img_id";
$module_config['file_path_column'] = "image";

$module_fields["news_date"]     = array(1, 50, 0, "DATE", "Data da notícia", date("Y-m-d"), "textfield");
$module_fields["title"]         = array(0, 250, 5, "ALPHA", "Título", "", "textfield");
$module_fields["description"]   = array(0, 5000, 0, "CALPHA", "Descrição", "", "html");
$module_fields["link"]          = array(1, 100, 0, "CALPHA", "Link", "http://", "textfield");
$module_fields["category_id"]   = array(1, 9, 0, "NUM", "Categoria", "", "combo");
$module_fields["city_id"]       = array(1, 9, 0, "NUM", "Cidade", "", "combo");
$module_fields["columnist_id"]  = array(1, 9, 0, "NUM", "Colunista", 0, "combo");
$module_fields["link_youtube"]  = array(1, 50, 0, "ALPHA", "Link Youtube", "", "textfield");
$module_fields["publish_at"]    = array(1, 19, 0, "DATETIME", "Publicar em", date("Y-m-d H:m"), "textfield", "Complete com o horário (dd/mm/aaaa hh:mm)");
$module_fields["preview"]       = array(1, 1, 0, "BOOL", "Rascunho", 0, "checkbox", "Desmarque para ativar a exibição no site");



	
