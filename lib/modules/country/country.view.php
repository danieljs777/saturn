<?

class CountryView extends BaseView
{
	
	
	public function edit($id)
	{			
		$this->set_options('continent_id', $this->model->select_table('sys_parameters', array('t_id' => '2'), "2"));
	
		parent::edit($id);
	}
	
	public function create($id)
	{			
	
		if($id == "")
			$form_config['form_action_success'] = $this->module . "/edit/{id}";
		
		$this->set_options('continent_id', $this->model->select_table('sys_parameters', array('t_id' => '2'), "2"));
		
		parent::edit($id);
	}
	
	public function edit_ajax($id)
	{
		$this->set_options('continent_id', $this->model->select_table('sys_parameters', array('t_id' => '2'), "2"));
		
		parent::edit_ajax($id);

	}
	
	
	
}
	
	
