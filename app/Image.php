<?php

namespace Crachecode\Tajine;

use Intervention\Image\ImageManager;

class Image extends ImageManager {

	public $filename;
	public $basename;
	public $extension;
	public $width;
	public $height;
	public $method;
	public $quality;
	public $upsize;
	public $manual;

	public $originals_path;
	public $cache_path;

	protected $image;
	protected $file;

	protected $defaults = array(
		'method' => 'fit',
		'quality' => 85,
		'upsize' => true
	);

	public function __construct(array $config = array()){
		parent::__construct($config);
		$this->method = $this->defaults['method'];
		$this->quality = $this->defaults['quality'];
		$this->upsize = $this->defaults['upsize'];
	}

	protected function exists()
	{
		return file_exists($this->file);
	}

	protected function create()
	{
		// to finally create image instances
		//echo 'img/'.$this->basename.'/original.'.$this->extension;
		//echo $this->image_path.$this->basename.'/original.'.$this->extension;
		$this->image = $this->make($this->originals_path.'/'.$this->basename.'.'.$this->extension);

		// resize or fit
		switch ($this->method) {
			case 'fit':
				if ($this->width && $this->height){
					$this->image->fit($this->width, $this->height, function ($constraint) {
						$constraint->aspectRatio();
						if (!$this->upsize || $this->upsize == 'false') $constraint->upsize();
					});
				}
				elseif ($this->width){
					$this->image->resize($this->width, null, function ($constraint) {
						$constraint->aspectRatio();
						if (!$this->upsize || $this->upsize == 'false') $constraint->upsize();
					});
				}
				else {
					$this->image->resize(null, $this->height, function ($constraint) {
						$constraint->aspectRatio();
						if (!$this->upsize || $this->upsize == 'false') $constraint->upsize();
					});
				}
				break;
			case 'max':
				$this->image->resize($this->width, $this->height, function ($constraint) {
					$constraint->aspectRatio();
					if (!$this->upsize || $this->upsize == 'false') $constraint->upsize();
				});
				break;
			case 'basic':
			default:
				$this->image->resize($this->width, $this->height, function ($constraint) {
					if (!$this->upsize || $this->upsize == 'false') $constraint->upsize();
				});
		}
		if (!file_exists($this->cache_path)) {
			mkdir($this->cache_path, 0777, true);
		}
		$this->image->save($this->file, $this->quality);
	}

	public function show()
	{
		//$this->file = $this->cache_path.'/'.$this->basename.'.'.$this->width.'x'.$this->height.'.'.$this->method.'.'.$this->quality.'.'.$this->upsize.'.'.$this->extension;
		$this->file = $this->cache_path.'/'.$this->basename.'.';
		if (isset($this->manual['width'])) $this->file .= $this->width;
		$this->file .= 'x';
		if (isset($this->manual['height'])) $this->file .= $this->height;
		$this->file .= '.';
		if (isset($this->manual['method'])) $this->file .= $this->method.'.';
		if (isset($this->manual['quality'])) $this->file .= $this->quality.'.';
		if (isset($this->manual['upsize'])) $this->file .= $this->upsize.'.';
		$this->file .= $this->extension;
		if ($this->exists()){
			$this->image = $this->make($this->file);
		}
		else {
			$this->create();
		}
		echo $this->image->response();
	}
}