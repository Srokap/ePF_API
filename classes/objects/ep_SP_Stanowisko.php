<?php
class ep_SP_Stanowisko extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'nazwa' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array( 'sp_stanowiska' );

	public $_field_init_lookup = 'nazwa';

	/**
	 * @var ep_Dataset
	 */
	protected $_orzeczenia_sp_osoby_stanowiska = null;

	/**
	 * @return string
	 */
	public function __toString(){
		return (string) $this->get_nazwa();
	}

	/**
	 * @return ep_Dataset
	 */
	public function orzeczenia_sp_osoby_stanowiska(){
		if( !$this->_orzeczenia_sp_osoby_stanowiska ) {
			$this->_orzeczenia_sp_osoby_stanowiska = new ep_Dataset( 'sp_ludzie_stanowiska' );
			$this->_orzeczenia_sp_osoby_stanowiska->init_where( 'orzeczenie_sp_stanowisko_id', '=', $this->id );
		}
		return $this->_orzeczenia_sp_osoby_stanowiska;
	}
}