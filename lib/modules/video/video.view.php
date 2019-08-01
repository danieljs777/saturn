<?

class VideoView extends BaseView
{
	
	public function edit($id, $form_config)
	{			

		$this->set_options('city_id', $this->model->select_table('cities', array()));
		$this->set_options('type', $this->model->select_table('sys_parameters', array('t_id' => '8')));
		
		if($id == "")
			$this->add_form_config('form_action_success', $this->module . "/edit/{id}");		
		
		parent::edit($id);
		
		
	}
	
	public function edit_gallery($id)
	{		
		$this->set_options('city_id', $this->model->select_table('cities', array()));
		$this->set_options('type', $this->model->select_table('sys_parameters', array('t_id' => '8')));
		
		parent::edit_gallery($id);

	}
	
}
	
	
