<?php
class ep_Bip_Instytucja extends ep_Object{
	/*INSERT_STUB*/

	public $_aliases = array( 'bip_instytucje' );

	/**
	 * @return string
	 */
	public function get_nazwa(){
		return (string) $this->data['nazwa'];
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return (string) $this->get_nazwa();
	}
}