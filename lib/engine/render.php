<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */


abstract class Render
{
	private static $is_html_opened = false;
	private static $is_html_closed = false;
	private static $div_message_class = "m-heading-1 border-red m-bordered";
	
    public static function is_mobile()
    {

        require_once LIB_ROOT . "/components/mobile-detect-2.8.0/Mobile_Detect.php";

        $detect = new Mobile_Detect();
        return ($detect->isMobile());
        
    }    	
	
	### HTML ############################################
	
	public static function toogle_full_html($opened)
	{
		self::$is_html_opened = !$opened;
		self::$is_html_closed = !$opened;
	
	}
	
	public static function to_html($content)
	{
		$content = str_replace("&lt;", "<", $content);
		$content = str_replace("&gt;", ">", $content);
		
		echo $content;
	}
	
	public static function open_html($page_title = SYSTEM_NAME)
	{

		$key_factory = KeyFactory::singleton();		
		
		if(!self::$is_html_opened)		
		{
			self::$is_html_opened = true;
			if(file_exists(APP_ROOT . $key_factory->get('app_name') . "/common/main.header.php"))
				require_once APP_ROOT . $key_factory->get('app_name') . "/common/main.header.php";
		}
	}
			
	public static function close_html()
	{	

		if(!self::$is_html_closed)		
		{
			self::$is_html_closed = true;
	
			$key_factory = KeyFactory::singleton();	

			require_once APP_ROOT . $key_factory->get('app_name') . "/common/main.footer.php";

		}
	}
	
	### Javascript ############################################
	
	public static function custom_javascript($file)
	{			
		echo "<script language='javascript' src='" . WEB_ROOT . $file . "'></script>\r\n";
	}
	
	
	public static function default_javascript()
	{		
		$key_factory = KeyFactory::singleton();

		$app    = $key_factory->get('app_name');
		$module = $key_factory->get('module_name');
		$action = $key_factory->get('module_action');
	
		if(file_exists(APP_ROOT . $app . "/" . $module . "/js/" . $action . ".js"))	
			echo "<script language='javascript' src='" . LIB_WEBROOT . "application/" . $app . "/" . $module . "/js/" . $action . ".js'></script>\r\n";
			
	}

	public static function default_module_javascript()
	{
		$key_factory = KeyFactory::singleton();

		$app    = $key_factory->get('app_name');
		$module = $key_factory->get('module_name');
		$action = $key_factory->get('module_action');

		if(file_exists(APP_ROOT . $app . "/" . $module . "/js/" . $module . ".js"))	
			echo "<script language='javascript' src='" . LIB_WEBROOT . "application/" . $app . "/" . $module . "/js/" . $module . ".js'></script>\r\n";
		
	}
	
	public static function crud_javascript()
	{			
		if(file_exists(APP_ROOT."/admin/common/js/crud.js"))	
			echo "<script language='javascript' src='" . APP_WEBROOT . "/admin/common/js/crud.js'></script>\r\n";
	}
	
	
	### CSS ############################################
	
	public static function custom_css($file)
	{		
		echo "<link rel='stylesheet' type='text/css' href=" . WEB_ROOT . $file . " media='screen' />\r\n";
	}
		
	
	### DIV ############################################
	
	public static function div_ajax_message($div_name = 'div_ajax_message', $return = false)
	{
		$content = "<div id='$div_name' class='" . self::$div_message_class . "" . "' style='display:none;'></div>\r\n";
		
		if($return)
			return $content;
		else
			echo $content;
	}
	
	### JSON Output ############################################	
	
	public static function return_ajax_error($message, $finish = true)
	{
		//$html = "<strong>Error!</strong>";
		$html = "<h2>Erro!</h2>";
		$html .="<p>$message</p>";
		//$html .= $message;

		$json_return = array("error" => true, "success" => false, "message" => $html, "div_class" => self::$div_message_class . " error");
		
		if($finish)
			die(json_encode($json_return));
		else
			return json_encode($json_return);
	}

	public static function return_ajax_success($message, $id = "", $finish = true, $redirect = "")
	{
		//$html = "<strong>Success!</strong>";
		$html = "<h2>Sucesso!</h2>";
		$html .="<p>$message</p>";
		//$html .= $message;		

		$json_return = array("success" => true, "message" => $html, "id"=> $id, "div_class" => self::$div_message_class . " success", "redirect" => $redirect);
		if($finish)
			die(json_encode($json_return));
		else
			return json_encode($json_return);
	}
	
	### Pages & Redirects ############################################	

	public static function loginScreen($errormsg = "", $page_title = SYSTEM_NAME, $fullpage = true)
	{
		$key_factory = KeyFactory::singleton();
		$app         = $key_factory->get('app_name');
		
		if(!$fullpage)
			self::open_html($page_title);
			
		require_once APP_ROOT . $app . "/common/login.php";
		
		if(!$fullpage)		
			self::close_html();
			
		die();
	}
		
	public static function crop_page(&$data, $errormsg = "", $page_title = SYSTEM_NAME)
	{
		$key_factory = KeyFactory::singleton();
		$app         = $key_factory->get('app_name');
		$module      = $key_factory->get('module_name');
		
		$image       = $data['image'][$data['_field_path_']];
		$image_dim   = getimagesize(str_replace(WEB_ROOT, PATH_ROOT, $image));
		
		$image_path  = $image;
					
		require_once APP_ROOT . "admin/common/crop.php";
					
		die();
	}
		
		
	public static function toHome()
	{
		die("<script type='text/javascript'>window.location.replace('" . WEB_ROOT . "/home');</script>");
	}
	
	public static function toAdminHome()
	{
		die("<script type='text/javascript'>window.location.replace('" . WEB_ROOT . "/home');</script>");
	}
	
	public static function redirect($module, $method = "", $id = "")
	{
		die("<script type='text/javascript'>window.location.replace('" . WEB_ROOT . "/$module/$method/$id');</script>");
	}
	
	public static function not_found($detail = "", $page_title = SYSTEM_NAME, $has_htmltag = true)
	{
		$key_factory  = KeyFactory::singleton();
		$app          = $key_factory->get('app_name');
		
		if(!$has_htmltag)
			self::open_html();
			
		if(file_exists(APP_ROOT . $app . "/common/not_found.php"))
			require APP_ROOT . $app . "/common/not_found.php";
		
		if(!$has_htmltag)	
			self::close_html();
		
		die();
	}
	
	public static function get_file($file, $vars = array(), $return = false)
	{
		
		if(!file_exists($file))
		{
			echo 'view [' . $file . '] not found!';
			log::event(E_ERROR, 'view [' . $file . '] not found!', __FILE__, __LINE__);
			
			return false;
		}

		if ($vars !== null)
			extract((is_array($vars) ? $vars : array($vars)), EXTR_PREFIX_SAME, '_');

		ob_start();
		
		require $file;
		$content = ob_get_contents();
		
		ob_end_clean();
		
		if ($return)
			return $content;
		
		echo $content;
	}
	
	### Special Elements ############################################	


    public static function breadcrumb()
    {
        $key_factory = KeyFactory::singleton();
        $module = $key_factory->get('module_name');
        $config = $key_factory->get($module . '_config');
        $action = $key_factory->get('module_action');


        echo '
		  <ol class="breadcrumb">
			<li><a href="' . System::make_link("home") . '">Nemesys</a></li>
			<li><a href="' . System::make_link("institution", "profile", System::get_session("inst_id")) . '">' . System::get_session("inst_name") . '</a></li>
			
			';

        echo '<li ' . (($action) ? ' class="active"' : '') . '><a href="' . System::make_link($module) . '">' . (isset($config['object']) ? $config['object'] : SYSTEM_NAME) . '</a></li>';

        switch ($action)
        {
            case "list_all":
            case "list_page":
                echo '<li class="active">Listagem</li>';
                break;
            case "create":
                echo '<li class="active">Inclusão</li>';
                break;
            case "detail":
                echo '<li class="active">Detalhe</li>';
                break;
            case "edit":
            case "update":
                echo '<li class="active">Alteração</li>';
                break;
        }
        echo '</ol>';
        echo '<h1>' . (isset($config['object']) ? $config['object'] : SYSTEM_NAME) . '</h1>';
    }

    public static function toolbar()
    {

        $key_factory = KeyFactory::singleton();
        $app = $key_factory->get('app_name');
        $module = $key_factory->get('module_name');
        $action = $key_factory->get('module_action');

        $id = $key_factory->get('module_params');

        if (file_exists(APP_ROOT . $app . "/" . $module . "/toolbar.php"))
            require APP_ROOT . $app . "/" . $module . "/toolbar.php";
    }

    public static function panel_info($title, $message)
   {
        $out = '<div class="alert alert-dismissable alert-info" style="width:100%; left-padding:20px; left-margin: 20px;">
						<strong>' . $title . '</strong> ' . $message . '
		                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		            </div>		';
        return $out;
    }

	
	
	public static function app_link($action)
	{
		$key_factory = KeyFactory::singleton();		
			
		if($key_factory->get('app_name') == 'admin')
			return ADMIN_WEBROOT . $action;
		else
			return "/" . $action;
	}
	
	public static function _html_editor($field_name, $field_value)
	{
		include PATH_ROOT . '/lib/components/ckeditor/ckeditor.php';
		include PATH_ROOT . '/lib/components/ckeditor/ckfinder/ckfinder.php';
		
		
		$CKEditor = new CKEditor();
		$CKEditor->returnOutput = true;
		$CKEditor->basePath                            = LIB_WEBROOT . '/components/ckeditor/';
/*
		$CKEditor->config['filebrowserBrowseUrl']      = LIB_WEBROOT . '/components/ckeditor/ckfinder/ckfinder.html';
		$CKEditor->config['filebrowserImageBrowseUrl'] = LIB_WEBROOT . '/components/ckeditor/ckfinder/ckfinder.html?type=Images';
		$CKEditor->config['filebrowserFlashBrowseUrl'] = LIB_WEBROOT . '/components/ckeditor/ckfinder/ckfinder.html?type=Flash';
		$CKEditor->config['filebrowserUploadUrl']      = LIB_WEBROOT . '/components/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
		$CKEditor->config['filebrowserImageUploadUrl'] = LIB_WEBROOT . '/components/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
		$CKEditor->config['filebrowserFlashUploadUrl'] = LIB_WEBROOT . '/components/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';		
*/
	
		CKFinder::SetupCKEditor( $CKEditor, '/lib/components/ckeditor/ckfinder/' ) ;

		return $CKEditor->editor($field_name, $field_value);
		
	}
	
	public static function html_editor($field_name, $field_value)
	{
		echo self::_html_editor($field_name, $field_value);	
	}
	
	
	public static function select_list($field, $options, $selected, $label = "" , $params = "")
	{		
		$html = "<select name='".$field."' id='".$field."' $params>\r\n&nbsp;"; 
		if($label != "")
			$html .= "<option value=''>$label</option>\r\n"; 
		
		foreach($options as $value)
		{
			$values_idx = array_values($value);			
			$html .= "<option value='".$values_idx[0]."' " . ($selected == $values_idx[0] ? ' selected' : '') . ">".$values_idx[1]."</option>\r\n"; 
		}
		$html .= "</select>\r\n&nbsp;";
		
		echo $html;
		
	}
	
}
