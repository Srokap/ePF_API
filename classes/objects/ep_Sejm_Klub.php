<?php
class ep_Sejm_Klub extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'nazwa' => ep_Object::TYPE_STRING,
			'liczba_kobiet' => ep_Object::TYPE_STRING,
			'liczba_mezczyzn' => ep_Object::TYPE_STRING,
			'liczba_poslow' => ep_Object::TYPE_STRING,
			'skrot' => ep_Object::TYPE_STRING,
			'srednia_frekfencja' => ep_Object::TYPE_STRING,
			'srednia_liczba_projektow_uchwal' => ep_Object::TYPE_STRING,
			'srednia_liczba_projektow_ustaw' => ep_Object::TYPE_STRING,
			'srednia_liczba_wnioskow' => ep_Object::TYPE_STRING,
			'srednia_liczba_wystapien' => ep_Object::TYPE_STRING,
			'srednia_poparcie_w_okregu' => ep_Object::TYPE_STRING,
			'srednia_udzial_w_obradach' => ep_Object::TYPE_STRING,
			'srednia_zbuntowanie' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('sejm_kluby');
	public $_field_init_lookup = 'nazwa';

	private $_poslowie = false;

	public function poslowie(){
		if( !$this->_poslowie ) {

			!$this->_poslowie = new ep_Dataset('poslowie');
			$this->_poslowie->init_where('klub_id', '=', $this->data['id']);
		}
		return $this->_poslowie;
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