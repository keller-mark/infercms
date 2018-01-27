<?php

class Image extends InferModel {

	protected $table_name = 'images';


	public function __construct() {

		$num_args = func_num_args();

		if($num_args == 1) {
			//id was passed as parameter
			$id = func_get_arg(0);
			$this->id = $id;
			$this->update();

			$this->active = true;
		} else {
			//id was NOT passed, assume it is new image
			$this->link = 'undefined.png';
			$this->active = false;
		}
	}

	public function upload($fileName, $contentLength) {

		$dir_dest = dirname(__FILE__) . '/../assets/uploads/';

		if(isset($fileName) && !empty($contentLength)) {
			$handle = new Upload('php:'.$fileName);
			$handle->forbidden = array('application/*');
			$handle->allowed = array('image/*');
			$handle->file_max_size = '100000000';
		} elseif(isset($fileName)&& empty($contentLength)) {
			$handle = new Upload($fileName);
			$handle->forbidden = array('application/*');
			$handle->allowed = array('image/*');
			$handle->file_max_size = '100000000';
		}else {
			return adminNotification(false, 'Error: File sent to server.');
		}

		if ($handle->uploaded) {

    	  $handle->file_new_name_body   = date("Y-m-d");
		  	$handle->file_auto_rename 	  = true;
		  	$handle->image_resize         = true;
		  	$handle->image_x              = 1500;
		  	$handle->image_ratio_y        = true;

        	// yes, the file is on the server
        	// now, we start the upload 'process'. That is, to copy the uploaded file
        	// from its temporary location to the wanted location
        	// It could be something like $handle->Process('/home/www/my_uploads/');

        	$handle->Process($dir_dest);
			$imgDSTName = $handle->file_dst_name;
        	// we check if everything went OK
        	if ($handle->processed) {

        	  $handle->file_new_name_body	= "thumb_" . $handle->file_dst_name_body;
		  	  	$handle->file_auto_rename 	= true;
		  	  	$handle->image_resize       = true;
		  	  	$handle->image_x			= 150;
		  	  	$handle->image_ratio_y      = true;
		  	  	$handle->Process($dir_dest);

			  	$this->set('link', $imgDSTName);

			  	return adminNotification(true, 'Image successfully uploaded.');
			} else {
            	// one error occured
            	return adminNotification(false, 'File not uploaded. Error: ' . $handle->error );
        	}

        	// we delete the temporary files
        	$handle->Clean();

        } else {
        	// if we're here, the upload file failed for some reasons
        	// i.e. the server didn't receive the file
        	return adminNotification(false, 'File not uploaded. Error: ' . $handle->error );
    	}
	}


	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return stripslashes($this->vars['title']);
	}

	public function toString() {
		global $settings;
		return '<img src="' . $settings['upload_root'] . $this->get('link') . '" />';
	}


}
