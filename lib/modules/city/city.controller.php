<?
class CityController extends BaseController
{
	/*******************************************************************************/
	/* Entry Actions ***************************************************************/

	public function __construct($action = "")
	{
		parent::__construct($action);
		
		$this->combo_data = $this->model->execute("SELECT country_id `id`, country_name `desc` FROM countries ORDER BY country_name");	
		$this->view->set_sidebar_data($this->combo_data);
		
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
				
		$post_type = System::get_value($_POST['type']);
		$type = $this->config['img_type'][$post_type];
				
		if(isset($_FILES["image"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . '/' . $this->module;
			$destination_path  = STORAGE_PATH . '/' . $this->module;

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path));
			$files = $Upload->upload_image_with_thumb('image', $type['width'], $type['height'], 155, 104, true);
			//$files = $Upload->upload_file('image');
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['image'] = $files['image'];
			$_file['thumb'] = $files['image_thumb'];
			$_file['type']  = $post_type;

			$this->save_file($id, $_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'crop', $files['_filename_'] . "|" . $type['width'] . "|" . $type['height']);
	}
	
	//#########################################################################
	// Videos functions

	public function list_videos($id)
	{
		$this->view->set_default($this->config['id_column'], $id);
		
		$data['videos']   = $this->model->list_videos($id);
		$data['module']   = $this->module;
		$data['id']       = $id;
		$data['config']   = $this->config;
		$data['type_list'] = $this->model->select_table('sys_parameters', array('t_id' => '8'), "2");
		
		
		$this->view->list_videos($data);
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
			$_file['city_id']      = $id;
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