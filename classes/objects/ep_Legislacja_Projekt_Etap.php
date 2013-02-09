<?php
class ep_Legislacja_Projekt_Etap extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'czas' => ep_Object::TYPE_STRING,
			'c_id' => ep_Object::TYPE_STRING,
			'data' => ep_Object::TYPE_STRING,
			'data_json' => ep_Object::TYPE_STRING,
			'liczba_dokumentow' => ep_Object::TYPE_STRING,
			'projekt_id' => ep_Object::TYPE_STRING,
			'typ_id' => ep_Object::TYPE_STRING,
			'tytul' => ep_Object::TYPE_STRING,
			'_data' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('legislacja_projekty-etapy');

	public function parse_data( $data ){
		parent::parse_data($data);
		// echo "\n";
		// echo $this->data['data_json'];
		$this->data['_data'] = json_decode( $this->data['data_json'], true );
	}

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