<?php
class ep_Sejm_Wniesiony_Projekt extends ep_Object{
	/*INSERT_STUB*/

	public $_aliases = array('sejm_wniesione_projekty');

	public function projekt(){
		if( $this->data['projekt_id'] ){
			return ep_Legislacja_Projekt( $this->data['projekt_id'] );
		}
		else{
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}