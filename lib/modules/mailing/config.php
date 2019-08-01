<?
$module_config['table_name'] = "newsletter_mails";
$module_config['id_column'] = "id";
$module_config['object'] = 'Emails';
$module_config['default_action'] = 'list_page';

$module_fields["email_address"]   = array(0, 150, 0, "EMAIL", "Email", "", "textfield");
$module_fields["ins_date"]        = array(1, 50, 0, "DATE", "Data Inclusão", date("Y-m-d H:i:s"), "text");
