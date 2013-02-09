<?php
class ep_Posel_Rejestr_Korzysci extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'data' => ep_Object::TYPE_STRING,
			'dokument_id' => ep_Object::TYPE_INT,
			'posel_id' => ep_Object::TYPE_INT,
			'label' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('poslowie_rejestr_korzysci');
	public $_field_init_lookup = 'label';

	private $_posel = false;

	public function set_ep_poslowie($data){
		$this->_posel = new ep_Posel($data);
	}

	public function posel(){
		return $this->_posel;
	}

	/**
	 * @return string
	 */
	public function get_data(){
		return (string) $this->data['data'];
	}

	/**
	 * @return integer
	 */
	public function get_dokument_id(){
		return (int) $this->data['dokument_id'];
	}

	/**
	 * @return integer
	 */
	public function get_posel_id(){
		return (int) $this->data['posel_id'];
	}

	/**
	 * @return string
	 */
	public function get_label(){
		return (string) $this->data['label'];
	}

	public function __toString(){
		return $this->get_nazwa();
	}
}