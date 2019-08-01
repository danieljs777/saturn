<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

abstract class BaseModel
{

    public $table_name;
    public $id_column;
    public $database;
    public $error_message;
    public $model_entity;

    private $current_page;
    private $total_regs;
    private $max_regs_per_page = 20;
    protected $files_suffix = "_files";
    protected $tags_suffix = "_tags";
    protected $files_path = "files/";
    protected $module_config;
    protected $module_fields;

    protected $model_config = array(
        "max_regs_per_page" => 20,
        "tbl_files_suffix" => "_images",
        "tbl_tags_suffix" => "_tags",
        "files_path" => "files/",
        "current_page" => 1,
    );

    public function __construct($module)
    {
        $this->database = PDOHelper::singleton();
        $this->key_factory = KeyFactory::singleton();

        $this->module_config = $this->key_factory->get($module . '_config');
        $this->module_fields = $this->key_factory->get($module . '_fields');

        if(isset($this->module_config['table_name']))
            $this->table_name = $this->module_config['table_name'];

        if(isset($this->module_config['id_column']))
            $this->id_column = $this->module_config['id_column'];

        if (isset($this->module_config['table_file_suffix']))
            $this->set_files_suffix($this->module_config['table_file_suffix']);

        if (isset($this->module_config['table_tag_suffix']))
            $this->set_tags_suffix($this->module_config['table_tag_suffix']);

        if (isset($this->module_config['path_file']))
            $this->files_path = $this->module_config['path_file'];

        $this->entity = $this->create_entity();
    }

    public function load_model($module)
    {
        return Action::load_model($module);
    }

    ### ORM Methods ############################################       

    public function create_entity()
    {
        $this->model_entity = new SuperModel();

        foreach($this->module_fields as $key_name => $key_params)
        {

            if(isset($key_params[3]) && $key_params[3] == 'NUM')
                $this->model_entity->_magicProperties[$key_name] = 0;
            else
                $this->model_entity->_magicProperties[$key_name] = '';
        }

        return $this->model_entity;
    }    

    public function persist($entity)
    {
        $array_data = (Array) $entity->_magicProperties;

        foreach($array_data as $key => &$_data)
        {
            if($_data == "")
                unset($array_data[$key]);
        }

        if(isset($data[$this->id_column]) && $data[$this->id_column] != "")
            $this->update($data);
        else
            $this->add($data);
    }


    ### Auxiliar Methods ############################################		

    public function set_files_suffix($new_suffix)
    {
        $this->files_suffix = $new_suffix;
    }

    public function get_files_suffix()
    {
        return $this->files_suffix;
    }

    public function set_tags_suffix($new_suffix)
    {
        $this->tags_suffix = $new_suffix;
    }

    ### Paging Methods ############################################		

    public function get_max_regs_per_page()
    {
        return $this->max_regs_per_page;
    }

    public function set_max_regs_per_page($num_per_page)
    {
        $this->max_regs_per_page = $num_per_page;
    }

    public function get_current_page()
    {
        return $this->current_page;
    }

    public function set_current_page($page)
    {
        $this->current_page = $page;
    }


    public function get_total_regs()
    {
        return $this->total_regs;
    }

    public function set_total_regs($regs)
    {
        $this->total_regs = $regs;
    }    

    public function get_total_pages()
    {
        return ceil($this->total_regs / $this->max_regs_per_page);
    }

    public function get_object($data)
    {
        //TODO Passar cada row para o get_fkauto

        return $data;
    }

    ### Database Operations Methods ############################################		

    public function execute($sql)
    {
        return $this->database->execute($sql);
    }

    public function list_all($criteria = array(), $order = null)
    {
        $_all_ = $this->database->select($this->table_name, $criteria, "*", $order);
        //foreach($_all_ as &$_this_)
        //	$_this_ = $this->get_object($_this_);

        $this->total_regs = sizeof($_all_);
        $this->current_page = 1;

        return $_all_;
    }

    public function list_page($criteria = array(), $page, $num = "20", $order = "2 desc")
    {
        $this->set_max_regs_per_page($num);

        $count = $this->database->select_count($this->table_name, $criteria);
        $this->total_regs = $count['__TOTAL_RECORDS'];
        $this->current_page = $page;

        $_all_ = $this->database->select_paged($this->table_name, $criteria, "*", $page, $this->max_regs_per_page, $order);
        //foreach($_all_ as &$_this_)
        //	$_this_ = $this->get_object($_this_);

        return $_all_;
    }

    public function list_custom_page($sql, $page, $num = "20", $order = "2 desc")
    {
        $this->set_max_regs_per_page($num);

        $count = $this->database->select_custom_count($sql);
        $this->total_regs = $count['__TOTAL_RECORDS'];
        $this->current_page = $page;

        $_all_ = $this->database->select_custom_paged($sql, $page, $this->max_regs_per_page, $order);
        //foreach($_all_ as &$_this_)
        //  $_this_ = $this->get_object($_this_);

        return $_all_;
    }


    public function list_distinct($field)
    {
        $_all_ = $this->database->select($this->table_name, array(), "DISTINCT $field", $field);

        return $_all_;
    }

    public function get_info($id, $criteria = array())
    {
        $criteria[$this->id_column] = $id;
        $_this_ = $this->database->select_first($this->table_name, $criteria);
        //$this->get_object($_this_);
        return $_this_;
    }

    public function get_fkey($table_name, $pk, $fk, $label)
    {
        $_result = $this->database->select_first($table_name, array($pk => $fk));
        return $_result[$label];
    }

    public function get_fkey_auto($table_name, $pk, $fk, $label, &$result_set)
    {
        $_result = $this->database->select_first($table_name, array($pk => $fk));
        $result_set[str_replace("_id", "_lbl", $pk)] = $_result[$label];
    }

    public function get_combo($label, $criteria)
    {
        return $this->database->select($this->table_name, $criteria, "*", array($this->id_column, $label));
    }

    public function get_by_field($field, $value)
    {
        return $this->select_first(array($field => $value));
    }   

    public function select($criteria, $order)
    {
        return $this->database->select($this->table_name, $criteria, "*", $order);
    }

    public function select_first($criteria, $order = "1", $fields = "*")
    {
        return $this->database->select_first($this->table_name, $criteria, $fields, $order);
    }

    public function update($data)
    {
        return $this->database->update($this->table_name, $data, array($this->id_column => $data[$this->id_column]));
    }

    public function add($data)
    {
        return $this->database->insert($this->table_name, $data);
    }

    public function delete($id)
    {
        return $this->database->delete($this->table_name, array($this->id_column => $id));
    }

    public function delete_many($id)
    {
        return $this->database->delete_many($this->table_name, array($this->id_column => $id));
    }

    public function select_table($table, $criteria = array(), $order = "")
    {
        return $this->database->select($table, $criteria, "*", $order);
    }

    public function affected_rows()
    {
        return $this->database->affected_rows();
    }

    public function show_error($msg = "")
    {
        return $this->database->show_error($msg);
    }

    public function print_sql_stack()
    {
        echo "<code><pre>";
        print_r($this->database->last_sql);
        echo "</pre></code>";
    }

    public function query($sql)
    {
        return $this->database->query($sql);
    }

    ### Relationships ############################################	
    ### Sys Parameters ############################################	

    public function set_reg_value(&$data, $field, $new_field = "")
    {
        $new_field = ($new_field == "") ? $field : $new_field;

        if (is_array($data))
        {
            foreach ($data as &$value)
            {
                $value[$new_field] = $this->get_reg_value($value[$field]);
            }
        }
    }

    public function set_value(&$data, $field, $table, $val, $desc, $label = false)
    {
        $field_label = ($label) ? $field . "_lbl" : $field;

        if (is_array($data))
        {
            foreach ($data as &$value)
            {
                $value[$field_label] = $this->get_reg_value($value[$field], $table, $val, $desc);
            }
        }
    }

    public function set_simple_value(&$data, $field, $val)
    {

        if (is_array($data))
        {
            foreach ($data as &$value)
            {
                $value[$field_label] = $val;
            }
        }
    }    

    public function get_reg_value($val_id, $table = 'sys_parameters', $val = 'p_id', $desc = 'p_name')
    {
        $data = $this->database->select_first($table, $criteria = array($val => $val_id));
        if (is_array($data))
            return $data[$desc];
        else
            return System::get_i18n_term("options.none");
    }

    ### Locale ############################################		

    public function get_country($country_id, $table = 'countries', $val = 'c_id')
    {
        return $this->database->select_first($table, $criteria = array($val => $country_id));
    }

    ### Files ############################################		

    public function list_files($id = "", $field_path_to_check = "image", $parent_key = "", $criteria = array())
    {
        $criteria = [];
        
        if($id != "")
        {
            $id_column = ($parent_key != "") ? $parent_key : $this->id_column;
            $criteria[$id_column] = $id;

        }

        $images_list = $this->database->select($this->table_name . $this->files_suffix, $criteria);

        $images = array();

        foreach ($images_list as $image)
        {
            if ($image[$field_path_to_check] != "")
            {
                if (strpos($image[$field_path_to_check], '/') > 0)
                {
                    $_path = explode('/', $image[$field_path_to_check]);
                    $file_name = $_path[count($_path) - 1];
                    $folder_name = $_path[count($_path) - 2];
                    $webpath = $image[$field_path_to_check];
                    // var_dump(STORAGE_ROOT . '/' . $folder_name . '/' . $file_name);

                    $fullpath = STORAGE_ROOT . '/' . $folder_name . '/' . $file_name;
                }
                else
                {
                    $file_name = $image[$field_path_to_check];
                    $fullpath = STORAGE_ROOT . '/' . $this->files_path . '/' . $file_name;
                }

                $image['_size_'] = (file_exists($fullpath)) ? intval(filesize($fullpath) / 1024) : "?";
                $image['_dimensions_'] = (file_exists($fullpath)) ? getimagesize($fullpath) : array("mime" => "?", 0 => "?", 1 => "?");
                $image['_file_name_'] = $file_name;
                $images[] = $image;

            }
        }

        return $images;
    }

    public function get_file($criteria, $order = "")
    {
        return $this->database->select_first($this->table_name . $this->files_suffix, $criteria, "*", $order);
    }

    public function get_files($criteria, $order = "is_cover desc")
    {
        return $this->database->select($this->table_name . $this->files_suffix, $criteria, "*", $order);
    }

    public function save_file($data)
    {
        return $this->database->insert($this->table_name . $this->files_suffix, $data);
    }

    public function delete_files($criteria)
    {
        return $this->database->delete_many($this->table_name . $this->files_suffix, $criteria);
    }

    ### Tags ############################################		

    public function get_tags($criteria)
    {
        return $this->database->select($this->table_name . $this->tags_suffix, $criteria);
    }

    public function save_tag($data)
    {
        return $this->database->insert($this->table_name . $this->tags_suffix, $data);
    }

    public function delete_tags($criteria)
    {
        return $this->database->delete_many($this->table_name . $this->tags_suffix, $criteria);
    }

    public function validate()
    {
        
    }

}
