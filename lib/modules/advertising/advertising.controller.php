<?

class AdvertisingController extends BaseController
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
	
	public function show_by_area($area, $limit)
	{
		$param_model = parent::load_model("parameter");
		
		$position = $param_model->get_info($area);
				
		if(!System::is_filled($position))
			return array();
		
		$criteria['position'] = $position['p_id'];
		$criteria['enabled']  = "1";
				
		return $this->show_list_random($limit, $criteria);
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
		
		$ad = $this->model->get_info($id);
		
		$position = $this->model->get_reg_value($ad['position'], 'sys_parameters', 'p_id', 'seo_hash');
		
		$type = $this->config['img_type'][$position];
		
		if(isset($_FILES["image"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . $this->module;
			$destination_path  = STORAGE_PATH . $this->module;

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path));
			$files = $Upload->upload_image('image', $type['width'], $type['height']);
			//$files = $Upload->upload_file('image');
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['image'] = $files['image'];

			$this->save_file($id, $_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'list_images', $id);
	}
	
	
}
?>
