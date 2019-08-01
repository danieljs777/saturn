<?
$module_config['table_name'] = "columnists";
$module_config['id_column'] = "c_id";
$module_config['object'] = 'Colunista';
$module_config['default_action'] = 'list_page';

$module_config['file_id_column'] = "img_id";
$module_config['file_path_column'] = "image";

$module_fields["c_name"]       = array(0, 150, 0, "ALPHA", "Nome", "", "textfield");
$module_fields["occupation"]   = array(1, 100, 0, "ALPHA", "Profissão", "", "textfield");
$module_fields["website"]      = array(1, 60, 0, "ALPHA", "Website", "http://", "textfield");
$module_fields["email"]        = array(1, 80, 0, "EMAIL", "Email", "", "textfield");
$module_fields["briefing"]     = array(1, 200, 0, "ALPHA", "Apresentação", "", "textfield");

//if(!strpos($this->get_method(), "list") && $this->get_method() != "")
/*if($this->get_method() == "create" && $this->get_method() == "save")
{
	$module_fields["login"]     = array(0, 20, 0, "ALPHA", "Login", "", "textfield");
	$module_fields["passwd"]    = array(0, 20, 6, "ALPHA", "Senha", "", "password");
}*/