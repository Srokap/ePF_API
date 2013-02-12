<?php
/**
 * Autoloader of ep_API classes. Uses spl autoloader.
 */
class ep_Autoloader {
	
	/**
	 * @var array Autoloader class map with class names as keys and paths to the files as values 
	 */
	private $classMap = array();
	
	public function __construct() {
		$this->classMap = $this->getDefaultMap();
	}
	
	/**
	 * @return array Autoloader class map with class names as keys and paths to the files as values 
	 */
	public function getMap() {
		return $this->classMap;
	}
	
	/**
	 * Default classes expected in eP_API. We map them manually since some of the classes 
	 * have different name and file name.
	 * @return array Autoloader class map with class names as keys and paths to the files as values 
	 */
	protected function getDefaultMap() {
		return array (
			'ep_Api' => 'ep_Api.php',
			'ep_Autoloader' => 'ep_Autoloader.php',
			'ep_Dataset' => 'ep_Dataset.php',
			'ep_Object' => 'ep_Object.php',
			'ep_Search' => 'ep_Search.php',
		);
	}
	
	/**
	 * Generate and output (SPL'ish) classmap for autoloader
	 *
	 * @param bool $return If true, returns PHP code instead of outputting it. Default: false
	 * @return string
	 */
	public function regenerateClassMap($return = false) 
	{
		$dir = __DIR__;
		$result = array();

    //: Recursive SPL Directory Iterator
    $i = new RecursiveDirectoryIterator($dir
             , RecursiveDirectoryIterator::KEY_AS_FILENAME
             | RecursiveDirectoryIterator::SKIP_DOTS
             );
    $i = new RecursiveIteratorIterator($i
           , RecursiveIteratorIterator::LEAVES_ONLY
             );
    
    //: use only *.php files (eg. for filter .dot.files)
    $i = new RegexIterator($i, '/^.+\.php$/i', RegexIterator::MATCH);
    
    //: class name (without .php extension) as key
    $i = new RegexIterator($i, '/(\.php)$/i', RegexIterator::REPLACE, RegexIterator::USE_KEY);
    
    $i->rewind();
        
    //: map map
    $map = array();
    $map_all = array();
    $map_oks = array();
    $map_bad = array();
        
    //: maping
    foreach ($i as $k) {        
        //: all files map
        $map_all[$i->key()] = $i->getSubPathName();

        //: path
        $path = $i->getRealPath();
        
        //: file object
        $f = new SplFileObject($path);
        
        $regExp = '/class\s+([a-zA-Z0-9_]*)\s/i';
                
        //: match object
        $m = new RegexIterator($f, $regExp, RegexIterator::MATCH);
        
        //: trigger error for files without "class"
        $ma = iterator_to_array($m, false);
        if (!($ma)) trigger_error("No class found in file ". $path, E_USER_WARNING);
        
        //: (matches) maping
        foreach ($m as $k) {
            $map_oks[$i->key()] = $i->getSubPathName();
        }    
    }
    
    //: error's map
    $map_bad = array_diff($map_all, $map_oks);

    //: choose map
    $map = $map_oks;
    
		$result = 'return '.str_replace('  ', "\t", var_export($map, true)).';';
		
		if ($return) {
			return $result;
		} else {
			echo '<pre>';
			echo $result;
			echo '</pre>';
		}
	}
	
	/**
	 * @return array full paths to files containing object classes
	 */
	public function getAllObjectClassPaths() {
		$basePath = dirname(__FILE__).'/objects/';
		$files = scandir($basePath);
		foreach ($files as $key => $file) {
			if ($file[0]=='.' || !is_file($basePath.$file)) {
				unset($files[$key]);
			} else {
				$files[$key] = $basePath.$file;
			}
		}
		return $files;
	}
	
	/**
	 * @return array all object classes names
	 */
	public function getAllObjectClassNames() {
		$files = $this->getAllObjectClassPaths();
		foreach ($files as $key => $file) {
			$files[$key] = basename($file, '.php');
		}
		return $files;
	}
	
	/**
	 * Autoload callback to be registered with spl_autoload_register
	 * @param string $class
	 * @return boolean
	 */
	public function autoload($class) {
		if (isset($this->classMap[$class])) {
			if (!@include_once($this->classMap[$class])) {
				return false;
			}
		} else {
			$path = dirname(__FILE__).'/objects/'.$class.'.php';
			if (!file_exists($path)) {
				return false;
			}
			if (!@include_once($path)) {
				return false;
			}
		}
	}
	
	/**
	 * Registers this instance of ep_Autoloader in spl autoloader
	 */
	public function register() {
		spl_autoload_register(array($this, 'autoload'));
	}
}
