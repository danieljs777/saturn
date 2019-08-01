<?
$module_config['table_name']     = "sys_parameters";
$module_config['id_column']      = "p_id";
$module_config['object']         = 'Parâmetro';
$module_config['datagrid_max']   = 2;
$module_config['default_action'] = 'list_page';
$module_config['sidebar']        = true;

$module_fields["p_name"]   = array(0, 100, 0, "ALPHA", "Descrição", "", "textfield");
$module_fields["t_id"]     = array(0, 9, 0, "NUM", "Tabela", System::is_not_empty($_SESSION['parameters_table']), "combo");



	
