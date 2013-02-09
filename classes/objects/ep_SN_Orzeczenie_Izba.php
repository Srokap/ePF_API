<?php
class ep_SN_Orzeczenie_Izba extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'orzeczenie_sn_id' => ep_Object::TYPE_INT,
			'orzeczenie_sn_izba_id' => ep_Object::TYPE_INT,
		));
		return $result;
	}

	public $_aliases = array( 'sn_izby-orzeczenia' );

	/**
	 * @var ep_SN_Orzeczenie
	 */
	protected $_orzeczenie_sn = null;

	/**
	 * @var ep_SN_Izba
	 */
	protected $_orzeczenie_sn_izba = null;

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
	 * @return ep_SN_Izba
	 */
	public function orzeczenie_sn_izba(){
		if( !$this->_orzeczenie_sn_izba ) {
			$this->_orzeczenie_sn_izba = new ep_SN_Izba( $this->get_orzeczenie_sn_izba_id() );
		}
		return $this->_orzeczenie_sn_izba;
	}
}