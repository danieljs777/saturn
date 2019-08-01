<?

class AdvertisingView extends BaseView
{
	public function edit_gallery($id)
	{		
		$this->set_options('position', $this->model->select_table('sys_parameters', array('t_id' => '11'), "2"));
				
		parent::edit_gallery($id);

	}
	
	public function edit($id)
	{
		if($id == "")
			$this->add_form_config('form_action_success', $this->module . "/edit/{id}");
		
		$this->set_options('position', $this->model->select_table('sys_parameters', array('t_id' => '11'), "2"));

		parent::edit($id);
	}
	
	
	public function list_images(&$data)
	{
		$this->layout($data, false, "list_images");
		
	}
	
}
	
	
