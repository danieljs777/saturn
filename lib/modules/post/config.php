<?
$module_config['table_name']         = "posts";
$module_config['id_column']          = "p_id";
$module_config['object']             = 'Post';
$module_config['default_action']     = 'list_page';

$module_config['file_id_column']     = "img_id";
$module_config['file_path_column']   = "image";

$module_config['sidebar']            = true;

//$module_fields["date_add"]           = array(1, 20, 0, "DATETIME", "Data", date("Y-m-d H:i:s"), "text");
$module_fields["city_id"]            = array(0, 9, 0, "NUM", "Cidade", "", "combo");
$module_fields["section"]            = array(0, 9, 0, "NUM", "Seção", "", "combo");
$module_fields["title"]              = array(0, 100, 0, "ALPHA", "Título", "", "textfield");
$module_fields["address"]            = array(1, 100, 0, "ALPHA", "Endereço", "", "textfield");
$module_fields["telephone"]          = array(1, 15, 0, "TEL", "Telefone", "", "textfield");
$module_fields["description"]        = array(0, 5000, 0, "CALPHA", "Conteúdo", "", "html");
$module_fields["url"]                = array(1, 100, 0, "ALPHA", "URL", "", "textfield");
$module_fields["highlight"]          = array(1, 1, 0, "BOOL", "Destaque", "", "checkbox", "Será exibido na home");
$module_fields["status"]             = array(1, 9, 0, "NUM", "Status", 11, "none");
$module_fields["link_youtube"]       = array(1, 50, 0, "ALPHA", "Link Youtube", "", "textfield");
$module_fields["publish_at"]         = array(0, 19, 0, "DATETIME", "Publicar em", date("Y-m-d H:i:s"), "textfield", "Complete com o horário (dd/mm/aaaa hh:mm)");
$module_fields["preview"]            = array(1, 1, 0, "BOOL", "Rascunho", 0, "checkbox", "Desmarque para ativar a exibição no site");
