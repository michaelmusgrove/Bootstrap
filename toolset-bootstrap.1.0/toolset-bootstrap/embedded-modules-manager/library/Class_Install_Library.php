<?php
class Class_Install_Library {

	function __construct()
	{

		//Retrieve module URL path if set
		if (isset($_GET['module_path'])) {

			$module_download_path = filter_var($_GET['module_path'], FILTER_SANITIZE_URL);	
			$key='import-file';

			$parse_url_download_path=parse_url($module_download_path);
			$module_originating_host=$parse_url_download_path['host'];
			
			//Fix bug that will not return the originating host if used in embedded
			if (!(defined('MODMAN_ORIGINATING_HOST'))) {
				//Define modules library XML download path
				define('MODMAN_ORIGINATING_HOST','ref.wp-types.com');
			}
			
			if (defined('MODMAN_ORIGINATING_HOST')) {
				if ($module_originating_host==MODMAN_ORIGINATING_HOST) {
					//Add to $_FILES array
					$success=$this->addToFiles($key, $module_download_path);
				}
			}
			
			if (($success==TRUE)) {
				//Transfer $_FILES array to $_GET 
				$put_files_in_get=http_build_query($_FILES);				
	
				if ((isset($_GET['mode_install'])) && (isset($_GET['mm_install_name']))) {
					
					$module_installation_mode=trim($_GET['mode_install']);
					$module_installation_name_for_import=trim($_GET['mm_install_name']);
					$module_name_url_encoded=str_replace(' ', '+', $module_installation_name_for_import);
					//Define module manager import URL
					$module_manager_import_url=admin_url('admin.php?page=ModuleManager_Modules&tab=import&step=1&mode_install_import='.$module_installation_mode.'&mm_install_name_import='.$module_name_url_encoded.'&'.$put_files_in_get);	
					$this->move_to_modman_importhook($module_manager_import_url);
				
				}
			} 

		}
	 
	}
	
	function addToFiles($key, $module_download_path)
	{
		$tempName = tempnam('/tmp', 'php_files');
		$originalName = basename(parse_url($module_download_path, PHP_URL_PATH));
	
		$modulesRawData = file_get_contents($module_download_path);
		file_put_contents($tempName, $modulesRawData);
		$_FILES[$key] = array(
				'name' => $originalName,
				'type' => $this->mime_content_type_compatible($tempName),
				'tmp_name' => $tempName,
				'error' => 0,
				'size' => strlen($modulesRawData),
		);
		
		if ((is_array($_FILES)) && (!(empty($_FILES)))) {
			
			return TRUE;
			
		} else {
			
			return FALSE;
		}
	}
	
	function move_to_modman_importhook($module_manager_import_url) {

		wp_redirect($module_manager_import_url);
		exit;
	}
	function mime_content_type_compatible($filename) {
	
		$mime_types = array(
	
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',
	
				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',
	
				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',
	
				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',
	
				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',
	
				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
	
				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
	
		$ext = strtolower(array_pop(explode('.',$filename)));
		if (array_key_exists($ext, $mime_types)) {
			return $mime_types[$ext];
		}
		elseif (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME);
			$mimetype = finfo_file($finfo, $filename);
			finfo_close($finfo);
			return $mimetype;
		}
		else {
			return 'application/octet-stream';
		}
	}
}