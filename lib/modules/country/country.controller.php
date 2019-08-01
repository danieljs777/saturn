<?
class CountryController extends BaseController
{
	public function __construct($action = "")
	{
		parent::__construct($action);
		
		$this->combo_data = $this->model->execute("SELECT p_id `id`, p_name `desc` FROM sys_parameters WHERE t_id = 2");	
		$this->view->set_sidebar_data($this->combo_data);
		
	}
	
	/*******************************************************************************/
	/* Entry Actions ***************************************************************/

	public function create()
	{
		$this->set_method('edit');
		parent::edit();
	}
	
	public function edit($id = "")
	{
		$this->set_method('edit');
		parent::edit();
	}			
	

}

?>