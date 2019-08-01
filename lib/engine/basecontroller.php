<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

abstract class BaseController
{
	protected $module;
	public  $action;

	public $model;
	public $view;
	
	private $class_model;
	private $class_view;
	protected $key_factory;

	protected $config;
	public $data;
	protected $model_entity;

	### Internal methods ############################################

	public function __construct($action = "")
	{				

		require_once LIB_ROOT . "/engine/supermodel.php";

		$this->module = strtolower(str_replace(array("Controller"), "", get_class($this)));

		$this->key_factory = KeyFactory::singleton();				
		$this->key_factory->set('module_name', $this->module);			
		
		$this->set_method($action);
		
		$this->config = $this->load_config($this->module);
		
		if($this->get_method() == "")
			$this->set_method($this->config['default_action']);
		
		$this->model  = $this->load_model($this->module);
		$this->key_factory->set($this->module . '_model', $this->model);

		$this->view   = $this->load_view($this->module);
		
		

	}
	
	protected function load_config($module)
	{
		if(file_exists(LIB_ROOT . "/modules/" . $module . "/config.php"))
		{
			require LIB_ROOT . "/modules/" . $module . "/config.php";
			
			$this->key_factory->set($module . '_fields', $module_fields);
			$this->key_factory->set($module . '_config', $module_config);
		}		

		return $module_config;	
	}
	
	protected function load_view($module)
	{

		if(file_exists(LIB_ROOT . "/modules/" . $module . "/" . $module . ".view.php"))
		{
			require_once LIB_ROOT . "/modules/" . $module . "/" . $module . ".view.php";
			
			$this->class_view = ucwords($module) . "View";
			$view  = new $this->class_view($module);
			$this->key_factory->set('view_instance', $view);
			return $view;
			
		}
		
	}
	
	protected function load_model($module)
	{

		if(!$this->key_factory->get($module . '_config'))
			$this->load_config($module);
			
		if($this->key_factory->get($module . '_config'))
		{			
			

			if(file_exists(LIB_ROOT . "/modules/" . $module . "/" . $module . ".model.php"))
			{
				require_once LIB_ROOT . "/modules/" . $module . "/" . $module . ".model.php";
				$class_model = ucwords($module) . "Model";
				return new $class_model($module);			
			}
			else
				return null;
		}
		else
			return null;
	}

	public function set_default_method($new_method)
	{
		if($this->action == "")
		{		
			$this->action = $new_method;
			$this->key_factory->set('module_action', $this->action);
		}
	}

	public function set_method($method)
	{		
		$this->action = $method;
		$this->key_factory->set($this->module . '_action', $this->action);
	}
	
	public function get_method()
	{		
		return $this->action;
	}

	public function get_config_key($key)
	{
		return $this->config[$key];
	}

	public function is_ajax_request()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	public function get_fields()
	{
		return $this->key_factory->get($this->module . '_fields');
	}

	### Front Controllers ############################################
	
	public function index($filter = array())
	{
		$this->data = $this->model->list_all($filter, "2");
		
		$this->view->index($this->data);
	}

	public function list_all($filter = array())
	{
		$this->data = $this->model->list_all($filter, "2");
		// if($this->key_factory->get('app_name') == 'admin')
		// 	$this->view->show_datagrid($this->key_factory->get($this->module . '_fields'), $this->data);
		// else
			$this->view->index($this->data);
		
	}
	
	public function list_page($page = 1, $filter = array())
	{
		$this->data = $this->model->list_page($filter, $page, $this->model->get_max_regs_per_page());
		
		// if($this->key_factory->get('app_name') == 'admin')
		// 	$this->view->show_datagrid($this->key_factory->get($this->module . '_fields'), $this->data);
		// else
			$this->view->index($this->data);
		
	}

    public function detail($id = "")
    {
        $db_data = array();

        if ($id != "")
        {

            $this->data['action_label'] = "Edit";

            $db_data = $this->model->get_info($id);
            if (!is_array($db_data) || sizeof($db_data) < 1)
                Render::not_found("Objeto não encontrado");

            foreach ($db_data as $field => $value)
            {
                $db_data[$field] = $this->view->format($field, $value);
            }
        }
        else
        {
            $this->data['action_label'] = "Create";

            foreach ($this->view->fields as $field => $param)
                $db_data[$field] = $param[BaseView::$_value];

            $db_data[$this->config['id_column']] = "";
        }

        $this->view->default_layout($db_data);
    }		

	public function create()
	{		
		$this->set_method('edit');
		self::edit();
	}

	public function edit($id = "")
	{
		
		if($id != "")
		{
			$this->data['action_label'] = "Edit";
			
			$db_data = $this->model->get_info($id);
			if(!is_array($db_data) || sizeof($db_data) < 1)
				Render::not_found();

			foreach($db_data as $field => $value)
				$db_data[$field] = $this->view->format($field, $value);

		}
		else
		{
			$this->data['action_label'] = "Create";
			
			foreach($this->key_factory->get($this->module . '_fields') as $field => $param)
				$db_data[$field] = $this->view->format($field, $param[$this->view->get_constant('_value')]);
		}
		
		$this->view->edit($id, array('form_action' => $this->module . "/save/" . $id, 'form_action_success' => $this->module, 'allow_delete' => true));
	}

	public function edit_ajax($id = "")
	{
		
		if($id != "")
		{
			$this->data['action_label'] = "Edit";			
			
			$db_data = $this->model->get_info($id);
			if(!is_array($db_data) || sizeof($db_data) < 1)
				Render::not_found();

			foreach($db_data as $field => $value)
				$db_data[$field] = $this->view->format($field, $value);
			
		}
		else
		{
			$this->data['action_label'] = "Create";
			
			foreach($this->key_factory->get($this->module . '_fields') as $field => $param)
				$db_data[$field] = $this->view->format($field, $param[$this->view->get_constant('_value')]);
		}
		
		$this->view->edit_ajax($id, array('form_action' => $this->module . "/save/" . $id, 'form_action_success' => $this->module));
	}

	public function edit_tabbed($id)
	{		
		if($id != "")
		{
			$db_data = $this->model->get_info($id);
			
			if(!is_array($db_data) || sizeof($db_data) < 1)
				Render::not_found();

			foreach($db_data as $field => $value)
				$db_data[$field] = $this->view->format($field, $value);

			
			$this->view->edit_tabbed($id);
			
		}
	
	}
	
	public function preview($id)
	{		
		if($id != "")
		{
			$db_data = $this->model->get_info($id);
			
			if(!is_array($db_data) || sizeof($db_data) < 1)
				Render::not_found();
			
			$data = array('objid' => $id);
			$this->view->layout($data);
			
		}
	
	}
	

	### External Controllers ############################################

	public function show($id = "")
	{
		global $module;
		if($id != "")
		{
			$db_data = $this->model->get_info($id);

			if(!is_array($db_data) || sizeof($db_data) < 1)
				Render::not_found($this->config['object'] . " not found.");
			else
				return $db_data;
		}
	}

	public function edit_external($id = "")
	{
		Render::toogle_full_html(false);
		
		if($id != "")
		{
			$this->data['action_label'] = "Edit";			
			
			$db_data = $this->model->get_info($id);
			if(!is_array($db_data) || sizeof($db_data) < 1)
				Render::not_found();

			foreach($db_data as $field => $value)
				$db_data[$field] = $this->view->format($field, $value);
			
		}
		else
		{
			$this->data['action_label'] = "Create";
			
			foreach($this->key_factory->get($this->module . '_fields') as $field => $param)
				$db_data[$field] = $this->view->format($field, $param[$this->view->get_constant('_value')]);
		}
		
		$this->view->edit($id, array('form_action' => $this->module . "/save/" . $id, 'form_action_success' => $this->module, 'allow_delete' => true));
	}
	
	
	public function show_list($filter = array(), $order = null)
	{
		return $this->model->list_all($filter, $order);
	}
	
	public function show_list_page($criteria, $page, $limit, $order = null)
	{		
		return $this->model->list_page($criteria, $page, $limit, $order);
	}

	public function show_list_random($num, $criteria)
	{
		return $this->model->list_page($criteria, 1, $num, "RAND()");
	}
	
	public function show_by_criteria($filter = array())
	{
		return $this->model->select_first($filter);
	}
	
	public function show_random($filter = array())
	{
		return $this->model->select_first($filter, "RAND()");
	}

	public function show_images($id)
	{		
		return $this->model->list_files($id, $this->config['file_path_column']);
	}
	

	### Operation Controllers ############################################

	public function get_address($zip)
	{
		echo json_encode(Geolocation::search_cep($zip));
	}
	
	public function save($id = "", $return_success = true)
	{
		$this->data = Input::validate_fields($this->key_factory->get($this->module . '_fields'), $this->config['table_name']);	

		if(@$_REQUEST[$this->config['id_column']] != "")
			$id = System::get_value($_REQUEST[$this->config['id_column']]);
		
		if (Input::$error_code == 1)
			Render::return_ajax_error(Input::$error_msg);
					
		if($id == "")
		{			
			$id = $this->model->add($this->data);
			if($id != "")
			{
				if($return_success)
					Render::return_ajax_success("Saved!", $id);
				else
					return $id;
			}
			else
				Render::return_ajax_error($this->model->show_error());
		}
		else
		{
			$this->data[$this->config['id_column']] = $id;

			$result = $this->model->update($this->data);

			if($result !== false)
			{
				if($return_success)
					Render::return_ajax_success("Saved!", $id);
				else
					return $id;
				
			}
			else
				Render::return_ajax_error($this->model->show_error());
				
		}
	}
		
	public function delete($id)
	{
		if($id == '')
			return false;
		
		if($this->model->delete($id))
			return true;
	}
	
	public function delete_many()
	{
		
		$_id = System::request('id_s');
		if($_id == '')
			Render::return_ajax_error("Nenhum objeto foi selecionado!");
		
		$ids = (!is_array($_id)) ? array($id) : $_id;
			
		$this->model->delete_many($ids);

		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			Render::return_ajax_success("Deletado com sucesso!");
		else
			Render::redirect($this->module, '');

		
	}

	### File/Image Controllers ############################################	

	public function save_file($id, $data)
	{
		$this->data[$this->config['id_column']] = $id;

		foreach($data as $field => $value)
			$this->data[$field] = $value;
	
		if($this->model->save_file($this->data) === false)
		{
			$this->model->show_error();
			return Javascript::show_message("Erro interno!", "back");
		}
		else
			return true;
	}

	public function load_image($path, $width, $height)
	{
		require_once PATH_ROOT . "/lib/components/phpthumb/ThumbLib.inc.php";
		
		$image = PhpThumbFactory::create($path);
		$image->resize($width, $height);
		$image->show();
		
	}
	
	public function crop_image()
	{
	    require_once PATH_ROOT . "/lib/components/phpthumb/ThumbLib.inc.php";
	    
		$width      = $_POST['w'];
		$height     = $_POST['h'];
		$start_x    = $_POST['x'];
		$start_y    = $_POST['y'];
		
		$final_width      = $_POST['f_w'];
		$final_height     = $_POST['f_h'];
		
		$image_path = str_replace(STORAGE_PATH, STORAGE_ROOT, $_POST['image']);		
				
		$image = PhpThumbFactory::create($image_path);
		$image->crop($start_x, $start_y, $width, $height);
		$image->resize($final_width, $final_height);		
		
		$image->save($image_path);		

		if(isset($this->config['s3_bucket']) && $this->config['s3_bucket'] === true)
		{
	        require_once LIB_ROOT . "/engine/aws.php";

	        $AwsHelper = new AwsWrapper();
	        $s3_path = $AwsHelper->send($image_path);
		}
		
		//$image_path = str_replace(STORAGE_PATH, STORAGE_ROOT, "thumb_" . $_POST['image']);
		
		$file_name = System::get_last_split($image_path, "/");
		
		$image_db = $this->model->database->select_first($this->model->table_name . $this->model->get_files_suffix(), array($this->config['file_path_column'] => "sql:like '%" . $file_name . "'"));		
		

		Render::redirect($this->module, 'list_images', $image_db[$this->config['id_column']]);
		
		
	}
	
	public function crop($file)
	{		
		$file = urldecode($file);
		
		list($file_name, $final_width, $final_height) = explode("|", $file);
		
		$image = $this->model->database->select_first($this->model->table_name . $this->model->get_files_suffix(), array($this->config['file_path_column'] => "sql:like '%" . $file_name . "'"));		
		if(!System::is_filled($image))
			Render::not_found();
		
		$template_data['object_id']    = $image[$this->config['id_column']];
		$template_data['module']       = $this->module;
		$template_data['final_width']  = $final_width;
		$template_data['final_height'] = $final_height;		
			
		$template_data['image'] = $image;
		$template_data['_field_path_'] = $this->config['file_path_column'];
		$template_data['path']  = explode("/", $template_data['image'][$this->config['file_path_column']]);

		Render::crop_page($template_data);
	}	
	
	public function list_images($id)
	{
		$this->set_method('list_images');

		$this->view->format($this->config['id_column'], $id);
		
		$data['images'] = $this->model->list_files($id, $this->config['file_path_column']);
		$data['module'] = $this->module;
		$data[$this->config['id_column']]     = $id;
		$data['config'] = $this->config;
		
		$this->view->list_images($data);
	}

	public function show_image_cover($id)
	{
		$this->view->format($this->config['id_column'], $id);
		$parent_cid_column = (isset($this->config['parent_id_column']) ? $this->config['parent_id_column'] : $this->config['id_column']);
		
		$image = $this->model->list_files($id, $this->config['file_path_column'], $parent_cid_column, array("is_cover" => 1));
		return $image[0]['_file_name_'];
	}

	public function delete_images()
	{
		$_id = $_POST['id_s'];
				
		$parent_id = $_POST[$this->config['id_column']];

		$ids = (!is_array($_id)) ? array($_id) : $_id;
		
		
		foreach($ids as $id)
		{
			$file = $this->model->get_file(array($this->config['file_id_column'] => $id));			
			$_path = explode('/', $file[$this->config['file_path_column']]);
			$file_name = $_path[ count($_path) - 1 ];			
			
			if(is_writable(STORAGE_ROOT . '/' . $this->module . '/' . $file_name))
			{
				@unlink(realpath(STORAGE_ROOT . '/' . $this->module . '/' . $file_name));
				@unlink(realpath(STORAGE_ROOT . '/' . $this->module . '/thumb_' . $file_name));
			}

			$this->model->delete_files(array($this->config['file_id_column'] => $id));

		}
		
		$this->list_images($parent_id);
		
	}
	
	### SEO Tags´ Controllers ############################################	
	
	public function list_tags($id)
	{
		$this->view->format($this->config['id_column'], $id);
		
		$data['tags']   = $this->model->get_tags(array($this->config['id_column'] => $id));
		$data['module'] = $this->module;
		$data['id']     = $id;
		
		$this->view->list_tags($data);
	}
	
	public function save_tag()
	{
		
		$data[$this->config['id_column']] = $_POST[$this->config['id_column']];
		
		$data['tag'] = Input::validate($_POST['new_tag'], 0, 30, 3, 'ALPHA', 'Tag', '');
		
		if (Input::$error_code == 1)
			Javascript::show_message(Input::$error_msg, "stop");
//			Render::return_ajax_error(Input::$error_msg);

		$this->model->save_tag($data);
		
		Render::redirect($this->module, 'list_tags', $data[$this->config['id_column']]);
	}

	public function delete_tags()
	{
		$_id = $_POST['id_s'];
		$parent_id = $_POST[$this->config['id_column']];

		$ids = (!is_array($_id)) ? array($_id) : $_id;
		
		foreach($ids as $id)
		{
			$this->model->delete_tags(array('tag_id' => $id));
		}
		
		Render::redirect($this->module, 'list_tags', $parent_id);
	}
	
	public function export_csv($param = array())
	{
		$output = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
		
		$data = $this->model->list_all($param);
		$columns = array('A');
				
		for($x=0; $x < sizeof($data); $x++)
			fputcsv($output, $data[$x]);
		
		rewind($output);
		$export = stream_get_contents($output);
		fclose($output);
		
		header('Content-type: application/octet-stream');
		
		header('Content-Disposition: attachment; filename="'. $this->module . '.csv"');
			 
		echo $export;

		
		
	}
	
}

