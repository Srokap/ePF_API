<?php
class ep_Glosowanie_Sejmowe_Glos extends ep_Object{
	/*INSERT_STUB*/

	public $_aliases = array('sejm_glosowania_glosy_wyniki');
	private $_posel = false;

	public function set_ep_poslowie($data){
		$this->_posel = new ep_Posel($data);
	}

	public function posel(){
		return $this->_posel;
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