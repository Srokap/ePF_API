<?php
class ep_ISAP_Plik extends ep_Object{

/*INSERT_STUB*/

	public $_aliases = array('isap_pliki');

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}