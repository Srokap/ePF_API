<?php
class ep_Sejm_Glosowanie_Glos extends ep_Object{
	/*INSERT_STUB*/

	public $_aliases = array('sejm_glosowania_glosy');

	/**
	 * @return string
	 */
	public function get_color(){
		return (string)$this->data['color'];
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
	public function get_opis(){
		return (string)$this->data['opis'];
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}