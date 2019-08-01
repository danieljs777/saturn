<?

class ParameterView extends BaseView
{
	public function edit($id)
	{
		$this->set_options('t_id', $this->model->execute("SELECT t_id `id`, t_name `desc` FROM sys_parameters_tables WHERE t_id = " . $_SESSION['parameters_table']));
		
		$this->add_form_config('form_action_success', 'parameter/list_page/&search_table=' . $_SESSION['parameters_table']);		
		
		parent::edit($id);
	}
	
}
	
	
