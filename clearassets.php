<?php
	if(file_exists('index.html') && is_file('index.html')) {
	    unlink('index.html');
	}

	function deleteAll($dir, $mainfolder) {
	    foreach(glob($dir . '/*') as $file) {
	        if(is_dir($file))
	            deleteAll($file, false);
	        elseif($mainfolder==false)
	            unlink($file);
	    }
	    if($mainfolder==false) rmdir($dir);
	}

	deleteAll('assets', true);
	deleteAll('admin/assets', true);
	deleteAll('runtime/cache', false);

	header("Location: /");
?>