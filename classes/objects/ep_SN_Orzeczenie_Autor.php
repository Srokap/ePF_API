<?php
class ep_SN_Orzeczenie_Autor extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'orzeczenie_sn_id' => ep_Object::TYPE_INT,
			'orzeczenie_sn_osoba_id' => ep_Object::TYPE_INT,
		));
		return $result;
	}

	public $_aliases = array( 'sn_orzeczenia_ludzie' );

	/**
	 * @var ep_SN_Orzeczenie
	 */
	protected $_orzeczenie_sn = null;

	/**
	 * @var ep_SN_Osoba
	 */
	protected $_orzeczenie_sn_osoba = null;

	/**
	 * @return ep_SN_Orzeczenie
	 */
	public function orzeczenie_sn(){
		if( !$this->_orzeczenie_sn ) {
			$this->_orzeczenie_sn = new ep_SN_Orzeczenie( $this->get_orzeczenie_sn_id() );
		}
		return $this->_orzeczenie_sn;
	}

	/**
	 * @return ep_SN_Osoba
	 */
	public function orzeczenie_sn_osoba(){
		if( !$this->_orzeczenie_sn_osoba ) {
			$this->_orzeczenie_sn_osoba = new ep_SN_Osoba( $this->get_orzeczenie_sn_osoba_id() );
		}
		return $this->_orzeczenie_sn_osoba;
	}
}