<?

class PostController extends BaseController
{
	private $combo_data;
	
	public function __construct($action = "")
	{	
		parent::__construct($action);	

		$combo_data   = $this->model->execute("SELECT city_id `id`, city_name `desc` FROM cities order by city_name");	

		$this->view->set_sidebar_data($combo_data);
			
		
	}
	
	/*******************************************************************************/
	/* Entry Actions ***************************************************************/
	
	
	public function list_all()
	{

		$filter = array();
		
		if(isset($_REQUEST['city']) && $_REQUEST['city'] != "")
			$filter['city_id'] = $_REQUEST['city'];
			
		parent::list_all($filter);
	}
	
	public function create()
	{
		$this->set_method('edit');
		parent::edit();
	}
		
	public function edit($id = "")
	{
		$this->set_method('edit_gallery');
		parent::edit_gallery($id);
	}
	
	public function upload()
	{
		require_once PATH_ROOT . "/lib/system/upload.php";
		
		$id = (isset($_POST[$this->config['id_column']]) ? $_POST[$this->config['id_column']] : '');
		
		if(isset($_FILES["image"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . '/' . $this->module;
			$destination_path  = STORAGE_PATH . '/' . $this->module;

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path));
			
			if(isset($_POST["is_cover"]))
				$files = $Upload->upload_image_with_thumb('image', 270, 142, 120, 81);
			else
				$files = $Upload->upload_image_with_thumb('image', 700, 470, 120, 81);
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['image'] = $files['image'];
			$_file['thumb'] = $files['image_thumb'];
			$_file['is_cover'] = isset($_POST["is_cover"]) ? $_POST["is_cover"] : 0;

			$this->save_file($id, $_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'list_images', $id);
	}
	
	
	//#########################################################################
	// Podcasts functions

	public function list_podcasts($id)
	{
		$this->view->set_default($this->config['id_column'], $id);
		
		$data['podcasts'] = $this->model->list_podcasts($id);
		$data['module']   = $this->module;
		$data['id']       = $id;
		$data['config']   = $this->config;
				
		$this->view->list_podcasts($data);
	}

	
	

	public function upload_podcast()
	{
		require_once PATH_ROOT . "/lib/system/upload.php";
		
		$id = (isset($_POST[$this->config['id_column']]) ? $_POST[$this->config['id_column']] : '');
								
		if(isset($_FILES["podcast"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . $this->module . '/';
			$destination_path  = STORAGE_PATH . $this->module . '/';

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path), array("ogg", "mp3"));
			$files = $Upload->upload_file('podcast');
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['podcast_file'] = $destination_path . $files['_filename_'];
			$_file['p_id']         = $id;
			$_file['pod_date']     = Input::validate($_POST['podcast_date'], 0, 10, 0, "DATE", "Data", "");
			$_file['title']        = Input::validate($_POST['title'], 0, 45, 0, "ALPHA", "Título", "");
			
			if (Input::$error_code == 1)
				Javascript::show_message(Input::$error_msg, "back");
		
			$this->model->save_podcast($_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'list_podcasts', $id);
	}
	
	public function delete_podcasts()
	{
		$_id = $_POST['id_s'];
				
		$parent_id = $_POST[$this->config['id_column']];

		$ids = (!is_array($_id)) ? array($_id) : $_id;
		

		foreach($ids as $id)
		{
			$this->model->set_files_suffix("_podcasts");
			
			$file = $this->model->get_file(array("pod_id" => $id));			
			$_path = explode('/', $file["podcast_file"]);
			$file_name = $_path[ count($_path) - 1 ];			
			
			if(is_writable(STORAGE_ROOT . '/' . $this->module . '/' . $file_name))
			{
				@unlink(realpath(STORAGE_ROOT . '/' . $this->module . '/' . $file_name));
			}
			
			$this->model->delete_podcasts(array("pod_id" => $id));

		}
		
		$this->list_podcasts($parent_id);
		
	}
	
	
}
?>
