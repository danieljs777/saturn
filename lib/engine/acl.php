<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

class Acl
{

    public $_open_modules = 
            array(
                "user/auth", 
                "user/logoff", 
                "user/save", 
                "medicine/search", 
                "exam/search", 
                "mailing/resend_invites", 
                "doctor/firststep", 
                "doctor/signup", 
                "user/forgot", 
                "user/send_password", 
                "integration/*",
                "schedule/appoint",
                "schedule/success",
                "schedule/save_appoint",
                "schedule/check_datetime",
                "schedule/save_appoint",
                "patient/search",
                "doctor/search",
                "insurance/search"
                );
    
    private $database;
    private $level_user_id;
    private $action;
    private static $instance;

    public static function singleton($module, $action, $params = "")
    {
        if (!isset(self::$instance))
            self::$instance = new Acl($module, $action, $params);

        return self::$instance;
    }

    public function __construct($module, $action, $params = "")
    {
        // session_destroy();
//        var_dump($module);
        if (!$this->islogged())
        {
            $permissions = $this->set_default();
            $this->set_permissions($permissions);
        }

        // var_dump($_SESSION);
        // die();

        $perms = $this->get_permissions();

        if(!\System::is_filled($perms) && $_SESSION['user_luid'] != "")
            $this->auth();

        // if($action == "logoff")
        // {
        //      var_dump($this->islogged());
        //     var_dump($this->is_allowed($module, $action));
        //     var_dump($_SESSION);
        //      var_dump($_SESSION['modules_permissions']);

        //     die();        

        // }

//var_dump($module);
//var_dump($action);
//die();

        $this->level_user_id = \System::get_session("user_luid");

        if (!$this->is_allowed($module, $action))
        {
            if (!$this->islogged_admin() && !$this->islogged())
            {

                $_SESSION['pre_auth_module'] = $module;
                $_SESSION['pre_auth_action'] = $action;
                $_SESSION['pre_auth_params'] = $params;
               
               Action::redirect("user", "request_login");
            }
            else
            {
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
                    Render::return_ajax_error("Você não possui acesso à ação " . $action . " do módulo: " . $module);
                else                
                    Render::not_found("Você não possui acesso à ação " . $action . " do módulo: " . $module);
            }
        }
    }
    
    public static function log($module, $action, $params)
    {
        $database = PDOHelper::singleton();

        $sql_perms = "INSERT INTO sys_log (log_date, module, action, params, user_id) VALUES (NOW(), '$module', '$action', '$params', " . \System::get_session("user_id", 0) . ")";

        $database->execute($sql_perms);        
    }

    public function set_default()
    {

        foreach ($this->_open_modules as $module)
        {
            $_arr_modules = explode("/", $module);
            $__open_mods [$_arr_modules[0]][$_arr_modules[1]] = 1;
        }

        return ($__open_mods);
    }

    public static function get_luid()
    {
        return intval(System::get_session("user_luid"));
    }

    public function auth()
    {
       $permissions = $this->get_permissions();

       $database = PDOHelper::singleton();

       $sql_perms = "SELECT * FROM sys_perms
							 WHERE luid_id = " . System::get_session("user_luid") . "
							 ORDER BY module,action";

       $_permissions = $database->execute($sql_perms);


       if (System::is_filled($_permissions))
       {
           foreach ($_permissions as $permission)
               $permissions[$permission['module']][$permission['action']] = $permission['authorized'];
       }

        $this->set_permissions($permissions);
    }

    public function set_permissions($_perms)
    {
        $_SESSION['modules_permissions'] = $_perms;
        //die();
    }

    public function get_permissions()
    {
        
        if (isset($_SESSION['modules_permissions']))
            return $_SESSION['modules_permissions'];
        else
            return array();
    }

    public static function islogged_admin()
    {

        $session_logged = System::get_session('user_logged');
        return (isset($session_logged) && $session_logged == 1);
    }

    public static function islogged()
    {
        $session_logged = \System::get_session('user_logged');

        return (isset($session_logged) && $session_logged == 1);
    }

    public function is_allowed($module, $action)
    {
        $permissions = $this->get_permissions();

        if (array_key_exists($module, $permissions))
        {

            if (isset($permissions[$module][$action]))
                return ($permissions[$module][$action] == 1);
            elseif (isset($permissions[$module]["*"]))
                return ($permissions[$module]["*"] == 1);
            else
                return false;
        }
        else
            return false;
    }

    public static function load_menu($parent = "")
    {
        $database = MySQL::singleton();

        if($parent == "")
            $sql_criteria = " NOT LIKE '%.%'";
        else
            $sql_criteria = " LIKE '" . $parent . "._'";

        $sql_menu_struct = "SELECT * FROM sys_modules LEFT JOIN sys_perms
                                ON m_name = module
                             WHERE MENU_TREE $sql_criteria
                             AND luid_id = " . System::get_session("user_luid") . "
                             AND (`action` = '*' OR `action` = 'list_page' OR `action` = 'list_all')
                             AND authorized = 1
                             ORDER BY MENU_TREE, MENU_LABEL";
                             

        $array_menu_struct = $database->execute($sql_menu_struct);

        if(!System::is_filled($array_menu_struct))
            return;
     
        echo " <ul ";
        if ($parent == "") echo "class=\"nav\"";
        echo ">\r\n";

        foreach($array_menu_struct as $menu_struct)
        {
            if($menu_struct['m_name'] != '')
                echo "<li><a href=\"?" . $menu_struct['m_name'] . "/" . $menu_struct['menu_link'] . "\">" . $menu_struct['menu_label'] . "</a>\r\n";
            else
                echo "<li><a href=\"#\">" . $menu_struct['menu_label'] . "</a>\r\n";

            self::load_menu($menu_struct['menu_tree']);
            
            echo "</li>\r\n";
            
        }

        echo " </ul>\r\n";

        return ;
    }
}

?>
