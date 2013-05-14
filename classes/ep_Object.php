<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Klasa (A) ep_Object - podstawowy składnik biblioteki.
 *
 * Odpowiada za struktury danych obiektów.
 *
 * @category   API
 * @package    ePF_API
 * @subpackage Core
 * @version    0.x.x-dev
 * @since      version 0.1.0
 * @abstract
 */
abstract class ep_Object extends ep_Api {

	public $id;
	public $data;
	private $loaded;
	public $layers = array();
	
	const TYPE_INT = 'int';
	const TYPE_STRING = 'string';
	const TYPE_ARRAY = 'array';
	const TYPE_FLOAT = 'float';
	const TYPE_OBJECT = 'object';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_METHOD = 'method';
	
	public function __construct( $data, $complex = true ){

		$id = false;

		if( is_array( $data ) ) {

			$id = false;
			foreach( $this->_aliases as $alias )
				if( isset($data[ $alias.'.id']) && $id = $data[ $alias.'.id'] )
					break;
			
		} elseif( isset($this->_field_init_lookup) && $this->_field_init_lookup && is_string( $data ) && !is_numeric( $data ) ) {
			
			/*
			$dataset = new ep_Dataset( $this->_aliases[0] );
			$data = $dataset->where($this->_field_init_lookup, '=', $data)->find_one( false );
			unset( $dataset );
			$id = (int) $data[ $this->_aliases[0].'.id' ];
			*/
			
		} elseif( $data ) {

			$id = $data;
			unset( $data );
		}

		if( !$id ){
			return false;
		}

		$this->id = $id;
		if( isset( $data ) ) {
		
			$this->loaded = $this->parse_data( $data );
			
		} else {
		
			if( $complex )
				$this->load();
			else
				$this->loaded = true;
			
		}
		
	}
	
	/**
	 * @return array of definitions of fields in data field. Array keys are names and values are constants from ep_Object::TYPE_*. All defined fields are availible by get_{NAME} getter methods and should be read this way.  
	 */
	public function getDataStruct() {
		return array(
			'id' => ep_Object::TYPE_INT,
		);
	}

	/**
	 * Implements get_* getters for trivial cases. Complex cases should be handled by direct method implementation.
	 * @param string $name
	 * @param array $arguments
	 * @throws BadMethodCallException
	 * @throws UnexpectedValueException
	 */
	private function __call($name , $arguments) {
		//handle get_{NAME} methods for $this->data['{NAME}'] contents
		if (strpos($name, 'get_')===0) {
			$struct = $this->getDataStruct();
			$key = substr($name, 4);
			if (isset($struct[$key])) {
				$val = $this->data[$key];
				switch($struct[$key]) {
					case ep_Object::TYPE_ARRAY:
						return (array) $val;
						break;
					case ep_Object::TYPE_BOOLEAN:
						return (bool) $val;
						break;
					case ep_Object::TYPE_FLOAT:
						return (float) $val;
						break;
					case ep_Object::TYPE_INT:
						return (int) $val;
						break;
					case ep_Object::TYPE_OBJECT:
						return (object) $val;
						break;
					case ep_Object::TYPE_STRING:
						return (string) $val;
						break;
					case ep_Object::TYPE_METHOD:
						throw new BadMethodCallException("Missing method $name definition in class ".get_class($this));
						break;
					default:
						throw new UnexpectedValueException("Internal Error: Bad field definition in class ".get_class($this).' for data name '.$key);
						break;
				}
			}
		}
		throw new BadMethodCallException("Unable to call method ".get_class($this)."::$name");
	}

	function load(){
		
		
	  $response = $this->call('ep_Object/load', array(
	  	'dataset' => $this->_aliases[0],
	  	'object_id' => $this->id,
 	  ));
				
		$this->data = $response['data'];
		return $this;
		
	}
	
	function load_from_db(){
		return $this->load();
	}

	function parse_data( $data ){
		$children = array();
		if( is_array($data) && !empty($data) ) {

			$layers = array();

			foreach( $data as $k => $v ) {

				$parts = explode('.', $k);
				if( $parts[0]=='layer' ) {
					$layers[ $parts[1] ][$parts[2] ] = $v;
				}

				$parts_count = count($parts);
				if( $parts_count<2 ){
					break;
				}

				$alias = $parts[ $parts_count-2 ];
				$key = $parts[ $parts_count-1 ];

				if( in_array($alias, $this->_aliases) ) {
					$this->data[ $key ] = $v;
				} else {
					
				  $this->data[ $alias . '.' . $key ] = $v;
					$children[ $alias ][ $alias.'.'.$key ] =	$v ;
				
				}
			}

			foreach( $children as $k => $v ){
				$method = 'set_ep_' . $k;

				if( method_exists( $this, $method ) )
					call_user_func_array(array($this, $method), array($data));
				
			}

			if( !empty($layers) ) {
				$this->layers = array_merge( $this->layers, $layers );
			}
		}

		return true;
	}

	/**
	 * @return boolean
	 */
	function isloaded(){
		return (Boolean) $this->loaded;
	}

	function load_layer($layer, $params = false) {

		$data = $this->call('ep_Object/loadLayer', array(
				'object' => get_class( $this ),
				'id' => $this->id,
				'layer' => $layer,
				'params' => $params,
		));

		return $data ? $this->layers[ $layer ] = $data['data'] : false;
	}

	/**
	 * @return string
	 */
	public function getTitle(){
		foreach( array('tytul', 'nazwa', 'label', 'sygnatura', 'kod', 'skrot') as $key ) {
			if( isset( $this->data[ $key ] ) && $this->data[ $key ] ){
				return $this->data[ $key ];
			}
		}
		return '';
	}

	/**
	 * @return string
	 */
	public function getLabel(){
		return '';					   
	}

	/**
	 * @return string
	 */	
	public function getDescription(){
		return false;
	}
	
	public function getDate(){			
			return false;			
	}
	
	public function getDatetime(){			
		return false;			
	}
	
	public function search(){
		return false;
	}

	/**
	 * @return string
	 */	
	public function __toString(){
		return $this->getTitle();
	}

	/**
	 * @return int
	 */
	public function getId(){
		return $this->get_id();
	}
	
	/**
	 * @return int
	 */
	public function get_id(){
		return isset( $this->data['id'] )  ? $this->data['id'] : null;
	}
}
