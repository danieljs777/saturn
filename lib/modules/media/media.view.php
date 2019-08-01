<?

class MediaView extends BaseView
{
	public function edit_gallery($id)
	{		
		$this->set_options('category', $this->model->select_table('sys_parameters', array('t_id' => '7'), "2"));
		
		parent::edit_gallery($id);

	}
	
	public function edit($id)
	{
		if($id == "")
			$this->add_form_config('form_action_success', $this->module . "/edit/{id}");		
					
		$this->set_options('category', $this->model->select_table('sys_parameters', array('t_id' => '7'), "2"));
		
		parent::edit($id);
	}
	
	
	
}
	
	
