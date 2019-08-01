<?

class CityView extends BaseView
{
	public function edit($id, $form_config = array())
	{			

		$this->set_options('country_id', $this->model->select_table('countries', array(), 'country_name'), "country_name");
//		$this->set_options('timezone', System::generate_timezone_list());

		if($id == "")
			$this->add_form_config('form_action_success', $this->module . "/edit/{id}");
		else
			$this->add_form_config('adv_buttons', true);

		parent::edit($id);
	}
	

	public function edit_gallery($id)
	{	
		$this->set_options('country_id', $this->model->select_table('countries', array(), 'country_name'), "country_name");
				
		//$this->add_form_config('form_action', $this->module . "/save/" . $id);
		$this->add_form_config('form_action_success', $this->module);
		$this->add_form_config('allow_delete', true);
		$this->add_form_config('adv_buttons', true);
		
		parent::edit_gallery($id);

	}

	public function list_images(&$data)
	{
		$this->layout($data, false, "list_images");
		
	}

	public function list_podcasts(&$data)
	{

		$this->layout($data, false, "list_podcasts");
		
	}

	public function list_videos(&$data)
	{
		

		$this->layout($data, false, "list_videos");
		
	}
	
	
}
	
	
