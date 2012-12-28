<?php
/*
  usage: 	
    include_once(CKEDITOR_PATH . 'j_ckedit.class.php');
	echo CKEDITOR::ckHeader();
	echo CKEDITOR::ckReplaceEditor('description');   //description is a textarea id
*/
	
/**
  *this class is a helper class for ckeditor 3.1
 * editor dimension is defined in config.js like config.width="300px"
*/

class CKEDITOR{

  /**
   * for dynamically modify paths
   */
  public static function set_path()
  {
    
  }
	/**
	  * load and configure ckeditor library
	  * in page <head> section
      *	  echo CKEDITOR::ckHeader();  
	  * @return a HTML code string including javascript code
	*/
	public static function ckHeader(){
		$timestamp = time(); 
		$src = HTML_CKEDITOR_PATH . 'ckeditor.js?t=' . $timestamp;
		$str = "<script type='text/javascript'>
				/*<!CDATA[*/
				window.CKEDITOR_BASEPATH = '" . HTML_CKEDITOR_PATH . 
				"';\n".
				"/*]]>*/
				</script> . 
		        <script type='text/javascript' src='$src'></script>
				<script type='text/javascript'>
				/*<![CDATA[*/
				CKEDITOR.timestamp = $timestamp; 
				/*]]>*/
				</script>";
		return $str;
	}

	/**
	  * main function
	  * in a form just
		echo CKEDITOR::ckHtmlEditor('content3', 15,70, 'this is default 
		content bababa!');
	  * @param $element_name form element name, e.g. product_long_description
	  * @param $rows, $cols textarea size
	  * @param $default_text default content of textarea
	  * @return html code with script code
	  * ckfileuploader.php is for file uploading
	*/
	public static function ckHtmlEditor($element_name, $rows  = 10, $cols = 50,
	                                $default_text = ''){	
            //filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php', before language: 'zh-cn'
		$str = "<textarea name='$element_name' rows='$rows' cols='$cols'>
             $default_text
			</textarea>
			<script type='text/javascript'>
				//<![CDATA[
				CKEDITOR.replace('$element_name',
								{filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php',
                                                                    toolbar:'Basic'
                                                                    }
								);
				//]]>
			</script>";
		return $str;	
	}
	public static function ckHtmlEditor_no_image($element_name, $rows  = 10, $cols = 50,
	                                $default_text = ''){	
            //filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php', before language: 'zh-cn'
		$str = "<textarea name='$element_name' rows='$rows' cols='$cols'>
             $default_text
			</textarea>
			<script type='text/javascript'>
				//<![CDATA[
				CKEDITOR.replace('$element_name',
								{filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php',
                                                                    toolbar:'Noimage'
                                                                    }
								);
				//]]>
			</script>";
		return $str;	
	}
	/**
	  * main function
	  * in a form just
		echo CKEDITOR::ckHtmlEditor('content3', 15,70, 'this is default 
		content bababa!');
	  * @param $element_name form element name, e.g. product_long_description
	  * @param $rows, $cols textarea size
	  * @param $default_text default content of textarea
	  * @return html code with script code
	  * ckfileuploader.php is for file uploading
	*/
	public static function ckHtmlEditor_Full($element_name, $rows  = 10, $cols = 50,
	                                $default_text = ''){	
            //filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php', before language: 'zh-cn'
		$str = "<textarea name='$element_name' rows='$rows' cols='$cols'>
             $default_text
			</textarea>
			<script type='text/javascript'>
				//<![CDATA[
				CKEDITOR.replace('$element_name',
								{filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php',
                                                                    toolbar:'Full'
                                                                    }
								);
				//]]>
			</script>";
		return $str;	
	}

	/**
	  * for file uploading, it's called by ckfileuploader.php
	    echo CKEDITOR::ckFile();
	  * 'upload' in $_FILES['upload'] is default file element name in PHP
	  * @return a javascript code for ckeditor api.
	  * work flow of uploading file in ckeditor:
	    1. ckHeader()           //load and configure ckeditor
		2. ckHtmlEditor()       //dispaly html editor area
		3. ckfileuploader.php   //when sending file after browsing and selecting file
		4. ckFile()            //called by ckfileuploader.php
     * make sure $upload_dir correct (exist and writable)
	*/
	public static function ckFile($upload_dir){
                $file_name = uniqid(). basename($_FILES['upload']['name']);
		$php_upload_file = PHP_PUBLIC_PATH . $upload_dir . $file_name;

		if (move_uploaded_file($_FILES['upload']['tmp_name'], $php_upload_file)) {
		  
			$funcNum = $_GET['CKEditorFuncNum'] ;
			/* optional
			$CKEditor = $_GET['CKEditor'] ;		
			$langCode = $_GET['langCode'] ;
			*/
			// Check the $_FILES array and save the file. Assign the correct path to some variable ($url).
			$url =  HTML_ROOT . $upload_dir . $file_name;

			// Usually you will assign here something only if file could not be uploaded.
			$message = '';
		}
		else {
			$url = '';
			$message = 'fail to upload';
		}
			 
		$str =  "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message'); </script>";
		return $str;
	}
	
	public static function ckReplaceEditor($element_name){
		return "<script type='text/javascript'>
				//<![CDATA[
				CKEDITOR.replace('$element_name',
								{filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php',
                                                                     toolbar:'Basic'
                                                                    }
								);
				//]]>
			</script>";
	}
	public static function ckReplaceEditor_Full($element_name){
		return "<script type='text/javascript'>
				//<![CDATA[
				CKEDITOR.replace('$element_name',
								{filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php',
                                                                     toolbar:'Full'
                                                                    }
								);
				//]]>
			</script>";
	}
	public static function ckReplaceEditor_no_image($element_name){
		return "<script type='text/javascript'>
				//<![CDATA[
				CKEDITOR.replace('$element_name',
								{filebrowserUploadUrl: '" .  HTML_CKEDITOR_PATH . "j_ckfileuploader.php',
                                                                     toolbar:'Noimage'
                                                                    }
								);
				//]]>
			</script>";
	}
}	