<?php
class ep_Sejm_Komisja extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'nazwa' => ep_Object::TYPE_STRING,
			'dopelniacz' => ep_Object::TYPE_STRING,
			'kod' => ep_Object::TYPE_STRING,
			'kontakt' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('sejm_komisje');
	public $_field_init_lookup = 'nazwa';

	private $_stanowiska = false;

	public function stanowiska(){
		if( !$this->_stanowiska ) {
			$this->_stanowiska = new ep_Dataset('poslowie_komisje_stanowiska');
			$this->_stanowiska->init_where('poslowie_komisje_stanowiska.komisja_id', '=', $this->data['id']);
			$this->_stanowiska->init_where('poslowie_komisje_stanowiska.aktywny', '=', '1');
			// $this->_stanowiska->init_where('poslowie_komisje_stanowiska.podkomisja_id', '!=', '0');
		}
		return $this->_stanowiska;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}