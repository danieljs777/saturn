<?

class HomeController extends BaseController
{
	
	public function __construct($action)
	{
		parent::__construct($action);
		
	}

	public function index()
	{
		$this->data['admin_name'] = ucwords($_SESSION["admin_name"]);
		$this->view->openHome($this->data);
	}
}
