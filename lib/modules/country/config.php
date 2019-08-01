<?
$module_config['table_name'] = "countries";
$module_config['id_column'] = "country_id";
$module_config['object'] = 'Países';
$module_config['default_action'] = 'list_page';
$module_config['sidebar']        = true;

$module_fields["continent_id"] = array(0, 9, 0, "NUM", "Continente", "", "combo");
$module_fields["country_name"] = array(0, 150, 0, "ALPHA", "Nome", "", "textfield");
$module_fields["ddi"]          = array(1, 3, 2, "ALPHA", "DDI", "", "textfield");
$module_fields["iso_code"]     = array(1, 100, 0, "ALPHA", "Código ISO ", "", "textfield");

