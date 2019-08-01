<?

class BannerController extends BaseController
{
	public function __construct($action = "")
	{	
		parent::__construct($action);
		
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
		$this->set_method('edit_gallery');
		parent::edit_gallery($id);
	}			
	
	public function upload()
	{
		require_once PATH_ROOT . "/lib/system/upload.php";
		
		$id = (isset($_POST[$this->config['id_column']]) ? $_POST[$this->config['id_column']] : '');
		
		$banner = $this->model->get_info($id);
		
		if(isset($_FILES["image"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . $this->module;
			$destination_path  = STORAGE_PATH . $this->module;

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path));
			$files = $Upload->upload_image('image', 528, 195, true);
			//$files = $Upload->upload_file('image');
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['image'] = $files['image'];

			$this->save_file($id, $_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'crop', $files['_filename_'] . "|528|195");
	}
	
	
}
?>
