<?php
 /**
  *
  * beforeUploaderHandler
  *
  * @package: ${NAMESPACE}
  * @author: Dennis Stolmeijer <zakelijk@dennisstolemeijer.nl>
  * 
  * Created at: 22-9-2015 at 00:26
  *
  */

namespace Dstollie\Oypo\Handler;

use Dstollie\Oypo\Bootstrap;

class BeforeUploadHandler {

	private $file;

	public function setFile($file) {
		$this->file = $file;

		return $this;
	}

	public function copyToRestrictedAreaFolder() {

		if($this->file && $this->file['name'] && $tempFileLocation = $this->file['tmp_name']) {

			if(file_exists($tempFileLocation)) {
				$this->createRestrictedAreaFolder();
				copy($tempFileLocation, Bootstrap::$restricted_photo_folder . DIRECTORY_SEPARATOR . $this->file['name']);
			}

		}

		return $this;
	}

	private function createRestrictedAreaFolder() {
		if(!is_dir(Bootstrap::$restricted_photo_folder))
			mkdir(Bootstrap::$restricted_photo_folder);
	}

}