<?php
class ep_RCL_Projekt extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'autor_id' => ep_Object::TYPE_STRING,
			'data_ostatnie_zmiany' => ep_Object::TYPE_STRING,
			'data_start' => ep_Object::TYPE_STRING,
			'dokument_id' => ep_Object::TYPE_STRING,
			'label' => ep_Object::TYPE_STRING,
			'lista_id' => ep_Object::TYPE_STRING,
			'opis' => ep_Object::TYPE_STRING,
			'opis_skrocony' => ep_Object::TYPE_STRING,
			'rcl_id' => ep_Object::TYPE_STRING,
			'rm_wykaz_prac_numer' => ep_Object::TYPE_STRING,
			'status_id' => ep_Object::TYPE_STRING,
			'status_str' => ep_Object::TYPE_STRING,
			'tk_str' => ep_Object::TYPE_STRING,
			'tytul' => ep_Object::TYPE_STRING,
			'tytul_skrocony' => ep_Object::TYPE_STRING,
			'ue_dyrektywa_nr' => ep_Object::TYPE_STRING,
			'uwagi' => ep_Object::TYPE_STRING,
			'uwagi_ss' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('rcl_projekty');

	private $_tablica;
	private $_autor;

	public function tablica() {
		if( !$this->_tablica ) {
			$this->_tablica = new ep_Dataset('rcl_projekty_tablice');
			$this->_tablica->init_where('projekt_id', '=', $this->id);
		}

		return $this->_tablica;
	}

	public function set_ep_instytucje($data){
		$this->_autor = new ep_Instytucja($data);
	}

	public function autor(){
		return $this->_autor;
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