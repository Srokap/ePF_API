<?php
class ep_SP_Teza extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'data' => ep_Object::TYPE_STRING,
			'sad_id' => ep_Object::TYPE_STRING,
			'teza' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('sp_tezy','sp_orzeczenia','sp_sady');

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get_nazwa();
	}
}