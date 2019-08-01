<?

class ParameterController extends BaseController
{
	private $combo_data;
	public function __construct($action = "")
	{
		parent::__construct($action);
		
		$this->combo_data = $this->model->execute("SELECT t_id `id`, t_name `desc` FROM sys_parameters_tables WHERE t_id > 1");	
		$this->view->set_sidebar_data($this->combo_data);
		
	}
	
	/*******************************************************************************/
	/* Entry Actions ***************************************************************/	
		
	public function list_page($page = 1, $filter = array())
	{
		$tbl_id = (isset($_REQUEST['search_table']) ? $_REQUEST['search_table'] : $_SESSION['parameters_table']);
		$filter = array('t_id' => $tbl_id);
		
		$_SESSION['parameters_table'] = $tbl_id;
		
		parent::list_page($page, $filter);
		
		
	}
	
		
	
}
?>
