<?php
class ep_Legislacja_Projekt_Status extends ep_Object{
	/*INSERT_STUB*/

	public $_aliases = array('legislacja_projekty_statusy');

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}