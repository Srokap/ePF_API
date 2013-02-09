<?php
class ep_Senat_Oswiadczenie_Majatkowe extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'dokument_id' => ep_Object::TYPE_INT,
			'senator_id' => ep_Object::TYPE_INT,
			'nazwa' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	public $_aliases = array('senatorowie_oswiadczenia_majatkowe');

	private $_senator = false;

	public function set_ep_senatorowie($data){
		$this->_senator = new ep_Senator($data);
	}

	public function senator(){
		return $this->_senator;
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
	public function get_senator_id(){
		return (int) $this->data['senator_id'];
	}

	/**
	 * @return string
	 */
	public function get_nazwa(){
		return (string) $this->data['nazwa'];
	}

	public function __toString(){
		return $this->get_nazwa();
	}
}