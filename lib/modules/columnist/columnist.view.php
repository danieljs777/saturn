<?

class ColumnistView extends BaseView
{
	
	public function edit($id)
	{
		if($id == "")
			$this->add_form_config('form_action_success', $this->module . "/edit/{id}");
						
		parent::edit($id);

	}
	
}
	
	
