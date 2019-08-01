<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */


class Upload
{

    private $config = array();
    public $error_status = 0;
    public $error_msg = "";
    private $file = "";
    private $method = "default";

    public function __construct($config, $allowed_types = array("gif", "png", "jpg", "jpeg"))
    {
        $this->config = $config;
        $this->allowed_types = $allowed_types;
        $this->check_settings();
    }

    public function set_method($method)
    {
        $this->method = $method;
    }

    private function make_filename($extension)
    {

        $temp = substr(md5(uniqid(time())), 0, 10);
        $file_name = $temp . "." . $extension;

        if (file_exists($this->config["path"] . $file_name))
            $file_name = make_filename($extension);

        return $file_name;
    }

    public function upload_image_with_thumb($field_name, $image_W, $image_H, $thumb_W, $thumb_H, $crop = false)
    {        
        
        $upload_result = $this->handle_upload($field_name);
        if (isset($upload_result['_filename_']))
        {
            $_files_resized = $this->resize_image_thumb($upload_result['_filename_'], $image_W, $image_H, $thumb_W, $thumb_H, $crop);
            $upload_result[$field_name]['web_path'] = (isset($_files_resized['image'])) ? $_files_resized['image'] : "";
            $upload_result[$field_name . '_thumb']['web_path'] = (isset($_files_resized['thumb'])) ? $_files_resized['thumb'] : "";
            $upload_result[$field_name]['path'] = (isset($_files_resized['path'])) ? $_files_resized['path'] : "";
            $upload_result[$field_name . '_thumb']['path'] = (isset($_files_resized['thumb_path'])) ? $_files_resized['thumb_path'] : "";
        }

        return $upload_result;
    }

    public function upload_file($field_name, $filename = "")
    {
        $upload_result = $this->handle_upload($field_name, $filename);
        @chmod($upload_result['_file_'], 0777);
        if (isset($upload_result['_file_']))
            Log::verbose("File successfully uploaded : " . $upload_result['_file_']);

        return $upload_result;
    }

    public function upload_image($field_name, $image_W, $image_H, $crop = false, $filename = "")
    {

        require_once PATH_ROOT . "/lib/components/phpthumb/ThumbLib.inc.php";
        
        Log::verbose("Uploading image: " . $field_name);

        $upload_result = $this->handle_upload($field_name, $filename);
        if (isset($upload_result['_filename_']))
        {
            Log::verbose("File successfully uploaded : " . $upload_result['_filename_']);

            $file_path = $this->config["path"] . DIR_SEPARATOR . $upload_result['_filename_'];
            $file_webpath = $this->config["webpath"] . '/' . $upload_result['_filename_'];

            if (is_writable($file_path))
            {
                $image_dimensions = getimagesize($file_path);

                if (isset($this->config['compare_min_size']))
                {
                    if ($image_dimensions['0'] < $image_W || $image_dimensions['1'] < $image_H)
                        return $this->set_error(System::get_i18n_term("upload.error.mindimension", $image_W, $image_H));
                }

                if (!$crop)
                {
                    if ($image_dimensions['0'] > $image_W || $image_dimensions['1'] > $image_H)
                    {

                        $image = PhpThumbFactory::create($file_path);
                        $image->adaptiveResize($image_W, $image_H);
                        $image->save($file_path);
                        @chmod($file_path, 0777);
                    }
                }

                $upload_result[$field_name]['web_path'] = $file_webpath;
                $upload_result[$field_name]['path']     = $file_path;
            }
        }

        return $upload_result;
    }

    public function resize_image_thumb($file_name, $image_W, $image_H, $thumb_W, $thumb_H, $crop = false)
    {

        require_once PATH_ROOT . "/lib/components/phpthumb/ThumbLib.inc.php";

        $file_path = $this->config["path"] . DIR_SEPARATOR . $file_name;

        $file_thumb = "thumb_" . $file_name;
        $file_thumb_path = $this->config["path"] . DIR_SEPARATOR . $file_thumb;

        $file_webdir = $this->config["webpath"] . '/' . $file_name;
        $file_thumb_webdir = $this->config["webpath"] . '/' . $file_thumb;

        if (is_writable($file_path))
        {
            $image_dimensions = getimagesize($file_path);

            if (isset($this->config['compare_min_size']))
            {
                if (isset($this->config['compare_min_size']) && $image_dimensions['0'] < $image_W || $image_dimensions['1'] < $image_H)
                    return $this->set_error(System::get_i18n_term("upload.error.mindimension", $image_W, $image_H));
            }

            if (!$crop)
            {
                if ($image_dimensions['0'] > $image_W || $image_dimensions['1'] > $image_H)
                {
                    $image = PhpThumbFactory::create($file_path);
                    $image->adaptiveResize($image_W, $image_H);
                    $image->save($file_path);
                }
            }

            $thumb = PhpThumbFactory::create($file_path);
            $thumb->adaptiveResize($thumb_W, $thumb_H);
            $thumb->save($file_thumb_path);

            @chmod($file_thumb_path, 0777);
            @chmod($file_path, 0777);

            $result = array();
            $result['image'] = $file_webdir;
            $result['thumb'] = $file_thumb_webdir;
            $result['filename'] = $file_name;
            $result['path']  = $file_path;
            $result['thumb_path'] = $file_thumb_path;

            return $result;
        }
        else
        {
            return $this->set_error(System::get_i18n_term("upload.error.filedenied", $file_path) . "\n");
        }
    }

    public function handle_upload($field_name, $filename = "")
    {
        Log::verbose("Handling upload : " . $field_name);

        if (isset($_FILES[$field_name]))
        {
            Log::verbose("Received by FILE");

            $this->file = new FileForm($field_name);
            $error = $this->file->getError();
            if ($error != "")
                return $this->set_error($error);
        }

        elseif (isset($_GET[$field_name]))
        {
            Log::verbose("Received by GET");

            $this->file = new FileXHR($field_name);
        }
        else
            return $this->set_error('No file: ' . $field_name);

        $pathinfo = pathinfo($this->file->getName());

        $ext = strtolower($pathinfo['extension']);

        if($filename == "")
            $filename = $this->make_filename($ext);

        $size = $this->file->getSize();

        Log::verbose("File: " . $filename);

        if (!is_writable($this->config["path"]))
        {
            return $this->set_error(System::get_i18n_term("upload.error.folderdenied"));
        }

        if ($size == 0)
        {
            return $this->set_error(System::get_i18n_term("upload.error.emptyfile"));
        }

        if ($size > $this->config["max_size"])
        {
            return $this->set_error(System::get_i18n_term("upload.error.maxsize", ($this->config['max_size'] / 1024)));
        }

        if ($this->allowed_types && !in_array(($ext), $this->allowed_types))
        {
            $these = strtoupper(implode(', ', $this->allowed_types));

            return $this->set_error(System::get_i18n_term("upload.error.type", $these));
        }

        if ($this->file->save($this->config["path"] . DIRECTORY_SEPARATOR . $filename))
        {
            return array('success' => true, '_filename_' => $filename, '_extension_' => $ext, '_file_' => $this->config["path"] . DIRECTORY_SEPARATOR . $filename);
        }
        else
        {
            return $this->set_error(System::get_i18n_term("upload.error.generic"));
        }
    }

    private function set_error($error_msg)
    {
        $this->error_status = 1;
        $this->error_msg = $error_msg;
        return array('error' => $error_msg);
    }

    private function check_settings()
    {
        // $postSize = $this->to_bytes(ini_get('post_max_size'));
        // $uploadSize = $this->to_bytes(ini_get('upload_max_filesize'));

        // if ($postSize < $this->config['max_size'] || $uploadSize < $this->config['max_size'])
        // {
        //     $size = max(1, $this->config['max_size'] / 1024 / 1024) . ' M';
        //     die("{'error':'increase post_max_size and upload_max_filesize to $size (maxsize:" . $this->config['max_size'] . ", postsize:$postSize, uploadsize:$uploadSize'}");
        // }
    }

    private function to_bytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last)
        {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    public function sendToS3($file)
    {
        require_once LIB_ROOT . "/engine/aws.php";

        $AwsHelper = new AwsWrapper();
        $result = $AwsHelper->send($file);

        return $result;
    }

}

class FileForm
{

    private $field_name;

    public function __construct($field_name)
    {
        $this->field_name = $field_name;
    }

    public function save($path)
    {

        $file = pathinfo($path);
        move_uploaded_file($_FILES[$this->field_name]["tmp_name"], $path);
        @chmod($path, 0777);

        return $file['basename'];
    }

    function getName()
    {
        return $_FILES[$this->field_name]['name'];
    }

    function getSize()
    {
        return $_FILES[$this->field_name]['size'];
    }

    function getError()
    {
        switch ($_FILES[$this->field_name]['error'])
        {
            case 0:
                return ""; // comment this out if you don't want a message to appear on success.
                break;
            case 1:
                return "PHP : The file is bigger than this PHP installation allows";
                break;
            case 2:
                return "PHP : The file is bigger than this form allows";
                break;
            case 3:
                return "PHP : Only part of the file was uploaded";
                break;
            case 4:
                return "PHP : No file was uploaded";
                break;
            case 6:
                return "PHP : Missing a temporary folder";
                break;
            case 7:
                return "PHP : Failed to write file to disk";
                break;
            case 8:
                return "PHP : File upload stopped by extension";
                break;
        }
    }

}

class FileXHR
{

    private $field_name;

    public function __construct($field_name)
    {
        $this->field_name = $field_name;
    }

    function save($path)
    {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize())
        {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    function getName()
    {
        if (isset($_GET[$this->field_name]))
            return $_GET[$this->field_name];
        elseif (isset($_POST[$this->field_name]))
            return $_POST[$this->field_name];
    }

    function getSize()
    {
        if (isset($_SERVER["CONTENT_LENGTH"]))
        {
            return (int) $_SERVER["CONTENT_LENGTH"];
        }
        else
        {
            throw new Exception('Getting content length is not supported.');
        }
    }

}

?>