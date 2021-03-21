<?php

namespace App\Custom;

use Illuminate\Http\UploadedFile;

class FileUpload {
	private $destination = 'images';
	/**
	 * @var UploadedFile
	 */
	private $file;
	private $finalFileName;
	private $prefix;

	/**
	 * Returns image name if upload is successful else null
	 *
	 * @return null|string
	 */
	public function handle() {
		if(!$this->file) {
			return null;
		}

		$this->finalFileName = $this->getFinalName();

		return $this->upload() ? $this->finalFileName : null;
	}

	public function getExtension() {
		return $this->file->getClientOriginalExtension();
	}

	public function getName() {
		return basename($this->file->getClientOriginalName(), '.' . $this->getExtension());
	}

	public function getFinalName() {
		return $this->prefix
		       . '-' . $this->brandName()
		       . '-' . date('YmdHis')
		       . '-' . str_random(6)
		       . '-' . str_slug($this->getName())
		       . '.' . $this->getExtension();
	}

	private function upload() {
		return $this->file->storeAs($this->destination, $this->finalFileName);
	}

	private function brandName() {
		return 'emall';
	}

	/**
	 * @param string $destination
	 *
	 * @return FileUpload
	 */
	public function setDestination(string $destination): FileUpload {
		$this->destination = $destination;

		return $this;
	}

	/**
	 * @param UploadedFile $file
	 *
	 * @return FileUpload
	 */
	public function setFile(UploadedFile $file = null): FileUpload {
		$this->file = $file;

		return $this;
	}

	/**
	 * @param string $prefix
	 *
	 * @return FileUpload
	 */
	public function setPrefix(string $prefix): FileUpload {
		$this->prefix = $prefix;

		return $this;
	}
}