<?
class ColumnistController extends BaseController
{
	/*******************************************************************************/
	/* Entry Actions ***************************************************************/

	public function edit($id = "")
	{
		$this->set_method('edit_gallery');
		parent::edit_gallery($id);
	}

	public function list_all()
	{		
		$template['db_data'] = $this->model->list_all(array());
		$this->view->index_ajax($template, false);
	}

	public function upload()
	{
		require_once PATH_ROOT . "/lib/system/upload.php";
		
		$id = (isset($_POST[$this->config['id_column']]) ? $_POST[$this->config['id_column']] : '');
		
		$banner = $this->model->get_info($id);
		
		if(isset($_FILES["image"]) && $id != '')
		{
			$destination_root  = STORAGE_ROOT . '/' . $this->module;
			$destination_path  = STORAGE_PATH . '/' . $this->module;

			$Upload = new Upload(array('max_size' => 3096000, 'path' => $destination_root, 'webpath' => $destination_path));
			$files = $Upload->upload_image('image', 132, 132);
			//$files = $Upload->upload_file('image');
			
			if ($Upload->error_status == 1)
				Javascript::show_message("Upload : ".$Upload->error_msg, "back");

			$_file['image'] = $destination_path . "/" . $files['_filename_'];

			$this->save_file($id, $_file);
		}
		else
			print_r($_POST);
		
		Render::redirect($this->module, 'list_images', $id);
	}
	


}

?>