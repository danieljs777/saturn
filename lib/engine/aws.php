<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

require_once LIB_ROOT . "/components/aws/aws-autoloader.php";

use Aws\S3\S3Client;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

class AwsWrapper
{

	private $sdk;
	private $s3;

	private static $bucket = "freakmarket-images";
	private static $endpoint = "https://freakmarket-images.s3-website-sa-east-1.amazonaws.com";

	public function __construct()
	{

		$sharedConfig = [
		    'credentials' => [
		        'key' => "",
		        'secret' => "",
		    ],
		    'region'  => 'sa-east-1',
		    'version' => 'latest'
		];

		// Create an SDK class used to share configuration across clients.
		$this->sdk = new Aws\Sdk($sharedConfig);

		// Create an Amazon S3 client using the shared configuration data.
		$this->s3 = $this->sdk->createS3();

	}

	public function upload( $file )
	{

		$uploader = new MultipartUploader($this->s3, $file, [
		    'bucket' => self::$bucket,
		    'key'    => System::get_last_split($file, "/"),
		    'ACL'    => 'public-read'
		]);

		try
		{
		    $result = $uploader->upload();
		    echo "Upload complete: {$result['ObjectURL']}\n";
		}
		catch (MultipartUploadException $e)
		{
		    echo $e->getMessage() . "\n";
		}
	}

	public function list_buckets()
	{
		$result = $this->s3->listBuckets();

		foreach ($result['Buckets'] as $bucket) {
		    echo $bucket['Name'] . "\n";
		}

	}

	public function send( $file )
	{

        $keyname     = str_replace(PATH_ROOT, "", $file);
        $contenttype = mime_content_type($file);
        $filepath    = $file;
                                
        $result = $this->s3->putObject(array(
            'Bucket'       => self::$bucket,
            'Key'          => $keyname,
            'SourceFile'   => $filepath,
            'ContentType'  => $contenttype,
            'ACL'          => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            // 'Metadata'     => array(    
            //     'param1' => 'value 1',
            //     'param2' => 'value 2'
            // )
        ));

        return $result['ObjectURL'];        		
	}	

	public function delete ( $file )
	{
		$result = $this->s3->deleteObject(array(
		    'Bucket' => self::$bucket,
		    'Key'    => str_replace(self::$endpoint, "", $file)
		));   	
			
	}

	public static function s3_to_local($filepath)
	{
		return str_replace(self::$endpoint . '/' . self::$bucket . '/', STORAGE_ROOT, $filepath);
	}

	public static function local_to_s3($filepath)
	{
		return str_replace(STORAGE_ROOT, self::$endpoint . '/' . self::$bucket . '/', $filepath);
	}


}

