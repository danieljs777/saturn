<?

class NewsView extends BaseView
{
	
	public function __construct($module)
	{
		parent::__construct($module);
		$this->set_options('category_id', $this->model->select_table('sys_parameters', array('t_id' => '9'), "2"));
		$this->set_options('city_id', $this->model->select_table('cities', array(), "city_name"));
		$this->set_options('columnist_id', $this->model->select_table('columnists', array(), "c_name"));
		
	}
	
	public function edit($id)
	{
		if($id == "")
			$this->add_form_config('form_action_success', $this->module . "/edit/{id}");
		else
			$this->add_form_config('adv_buttons', true);		
		
		parent::edit($id);
	}


	public function edit_gallery($id)
	{		
		$this->add_form_config('adv_buttons', true);
		
		parent::edit_gallery($id);

	}
	

	public function list_podcasts(&$data)
	{

		$this->layout($data, false, "list_podcasts");
		
	}


	
}
	
	
