<?php
class ep__Object extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'class' => ep_Object::TYPE_STRING,
			'dataset' => ep_Object::TYPE_STRING,
			'enabled' => ep_Object::TYPE_STRING,
			'listing' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('_objects');
	public $_field_init_lookup = 'class';

	/**
	 * @return string
	 */
	public function get_nazwa(){
		return (string)$this->data['nazwa'];
	}

	/**
	 * @return string
	 */
	public function get_imie(){
		return (string)$this->data['imie'];
	}

	/**
	 * @return int
	 */
	public function get_nazwisko(){
		return (string)$this->data['nazwisko'];
	}

	/**
	 * @return int
	 */
	public function get_zawod(){
		return (string)$this->data['zawod'];
	}

	/**
	 * @return int
	 */
	public function get_plec(){
		return (string)$this->data['plec'];
	}

	/**
	 * @return int
	 */
	public function get_data_urodzenia(){
		return (string)$this->data['data_urodzenia'];
	}

	/**
	 * @return int
	 */
	public function get_miejsce_urodzenia(){
		return (string)$this->data['miejsce_urodzenia'];
	}
	/**
	 * @return int
	 */
	public function get_nr_okregu(){
		return (int)$this->data['nr_okregu'];
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}