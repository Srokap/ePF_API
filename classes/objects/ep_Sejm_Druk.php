<?php
class ep_Sejm_Druk extends ep_Object{
	/*INSERT_STUB*/

	public $_aliases = array('sejm_druki', 'sejm_druki_typy');
	public $_field_init_lookup = 'numer';

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}