<?

class MediaController extends BaseController
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
			
		if(isset($_FILES["image"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . '/' . $this->module;
			$destination_path  = STORAGE_PATH . '/' . $this->module;

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path));
			$files = $Upload->upload_image_with_thumb('image', 526, 674, 202, 205);
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['image'] = $files['image'];
			$_file['image_thumb'] = $files['image_thumb'];
			$_file['is_cover']    = isset($_POST["is_cover"]) ? $_POST["is_cover"] : 0;

			$this->save_file($id, $_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'list_images', $id);
	}
		
	
}
?>
