<?

class AdminView extends BaseView
{
	
	public function edit($id)
	{			
		$this->set_options('luid', $this->model->select_table('sys_parameters', array('t_id' => '1')));
	
		parent::edit($id);
	}

}
	
	
