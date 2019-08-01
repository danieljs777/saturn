<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */


class Action
{

    protected $controller_file;
    protected $class_name;
    protected $method;
    protected $arguments = array();
    private $key_factory;
    public $params = 0;
    private $module;
    private $action;

    public function __construct($app_name, $module = "", $action = "", $params = "")
    {
        $this->key_factory = KeyFactory::singleton();

        if (($module == "") && ($action == ""))
        {
//            if(APACHE_RWENGINE == 'Off')
//            {
                $request = str_replace("//", "/", $_SERVER['REQUEST_URI']);

                @list($app, $module, $action, $params) = explode("/", $request);

                $module = str_replace("?", "", $module);

                $params = str_replace("?", "&", $params);

                @list($params) = explode("&", $params);

//            }

            if (($module == "") && ($action == ""))
            {
                $module = isset($_REQUEST['m']) ? $_REQUEST['m'] : '';
                $action = isset($_REQUEST['a']) ? $_REQUEST['a'] : '';
                $params = isset($_REQUEST['i']) ? $_REQUEST['i'] : '';
            }

            // var_dump($module);
            // var_dump($action);
            // var_dump($params);


        }

        $this->module = ($module == null) ? 'home' : strtolower($module);
        $this->action = strtolower($action);
        $this->params = $params;
        
        $this->key_factory->set('app_name', $app_name);
        $this->key_factory->set('module', $module);
        $this->key_factory->set('action', $action);
        $this->key_factory->set('module_params', $this->params);
        
        $this->pre_action($app_name);
        $this->load($this->params);
    }

    public function pre_action($app_name)
    {
           
        // if($app_name == "admin")
        // {
            Acl::log($this->module, $this->action, $this->params);

            if ($this->action != "request_login" && $this->action != "auth")
                Acl::singleton($this->module, $this->action, $this->params);

        //}
    }

    public function load($params = "")
    {
        $module = $this->module;
        $action = $this->action;

        $controller_class = StringHelper::underscore_to_camel(ucwords($module)) . "Controller";
        $module_file = str_replace("_", "", $module);
        $this->controller_file = LIB_ROOT . "/modules/$module/$module_file.controller.php";

        if (file_exists($this->controller_file))
        {
            require_once $this->controller_file;

            $module_controller = new $controller_class($action);
            $action = $module_controller->get_method();

            if (!is_callable(array($module_controller, $action)))
            {
                Render::not_found("$module.$action not acessible!");
                exit();
            }

            if (($action != ""))
            {
                if ($params != "")
                {
                    if (strpos($params, "=") > 0)
                    {
                        $this->$params = str_replace("|", "&", $params);
                        parse_str($this->$params, $params);
                    }

                    return $module_controller->$action($params);
                }
                else
                    return $module_controller->$action();
            }
        }
        else
        {
            Render::not_found("$module Controller not found!");
        }
    }

    public static function redirect($module, $action, $params = "")
    {

        if ($action != "request_login" && $action != "auth")
            Acl::singleton($module, $action, $params);

        $controller_class = StringHelper::underscore_to_camel(ucwords($module)) . "Controller";
        $module_file = str_replace("_", "", $module);
        $controller_file = LIB_ROOT . "/modules/$module/$module_file.controller.php";

        if (file_exists($controller_file))
        {
            require_once $controller_file;

            $module_controller = new $controller_class($action);
            $action = $module_controller->get_method();

            if (!is_callable(array($module_controller, $action)))
            {
                Render::not_found("$module.$action not acessible!");
                exit();
            }

            if (($action != ""))
            {
                if ($params != "")
                {
                    if (strpos($params, "=") > 0)
                    {
                        $_params = str_replace("|", "&", $params);
                        parse_str($_params, $params);
                    }

                    return $module_controller->$action($params);
                }
                else
                    return $module_controller->$action();
            }
        }
        else
        {
            Render::not_found("$module Controller not found!");
        }
    }

    public static function load_controller($module)
    {
        $controller_class = StringHelper::underscore_to_camel($module) . "Controller";
        $module_file = str_replace("_", "", $module);
        $controller_file = LIB_ROOT . "/modules/$module/$module_file.controller.php";

        if (file_exists($controller_file))
        {
            require_once $controller_file;

            return new $controller_class();
        }
    }

    public static function load_model($module)
    {
        $key_factory = KeyFactory::singleton();

        if (!$key_factory->get($module . '_config'))
            $key_factory->set($module . '_config', self::load_config($module));

        if ($key_factory->get($module . '_config'))
        {
            if (file_exists(LIB_ROOT . "/modules/" . $module . "/" . $module . ".model.php"))
            {
                require_once LIB_ROOT . "/modules/" . $module . "/" . $module . ".model.php";
                $class_model = ucwords($module) . "Model";
                return new $class_model($module);
            }
        }
        else
            Render::not_found("Não é possível acessar o config(" . $module . ") agora");
    }

    public static function load_config($module)
    {
        $key_factory = KeyFactory::singleton();

        if (file_exists(LIB_ROOT . "/modules/" . $module . "/config.php"))
        {
            require LIB_ROOT . "/modules/" . $module . "/config.php";

            $key_factory->set($module . '_fields', $module_fields);
            $key_factory->set($module . '_config', $module_config);

            if (isset($datagrid_fields))
                $key_factory->set($module . '_datagrid', $datagrid_fields);


            return $module_config;
        }
    }

    public function get_controllerfile()
    {
        return $this->controller_file;
    }

    public function get_classname()
    {
        return $this->class_name;
    }

    public function get_method()
    {
        return $this->method;
    }

    public function get_args()
    {
        return $this->arguments;
    }

}

?>
