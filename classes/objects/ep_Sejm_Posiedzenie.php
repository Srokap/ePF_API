<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Obiekt ep_Sejm_Posiedzenie.
 *
 * Aliasy:
 *   sejm_posiedzenia
 *
 * Przykładowe zastosowanie:
 * <code>
 * 	 $searcher = new ep_Search();
 *	 $searcher->setDataset('sejm_posiedzenia')->load();
 *
 *   $objects = $searcher->getObjects();
 *   $pagination = $searcher->getPagination();
 * </code>
 *
 * @example objects/ep_Sejm_Posiedzenie
 *
 * @see ep_Sejm_Posiedzenie::$_aliases
 *
 * @category   System
 * @package    ePF_API
 * @subpackage Objects
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
class ep_Sejm_Posiedzenie extends ep_Object{

	/**
	 * @see ep_Object::getDataStruct()
	 */
	public function getDataStruct() {
		$result = parent::getDataStruct();
		$result = array_merge($result, array (
			'data_start' => ep_Object::TYPE_STRING,
			'data_stop' => ep_Object::TYPE_STRING,
			'data_str' => ep_Object::TYPE_STRING,
			'img' => ep_Object::TYPE_STRING,
			'informacja_marszalka_id' => ep_Object::TYPE_STRING,
			'komunikat_id' => ep_Object::TYPE_STRING,
			'liczba_glosowan' => ep_Object::TYPE_STRING,
			'liczba_punktow' => ep_Object::TYPE_STRING,
			'opis' => ep_Object::TYPE_STRING,
			'tytul' => ep_Object::TYPE_STRING,
			'zapowiedz' => ep_Object::TYPE_STRING,
			'stats' => ep_Object::TYPE_STRING,
			'numer' => ep_Object::TYPE_STRING,
		));
		return $result;
	}

	/**
	 * @var array
	 */
	public $_aliases = array('sejm_posiedzenia');
	
	/**
	 * @var string
	 */
	
	public function getTitle(){
		
		if( $this->data['numer'] )
			return 'Posiedzenie Sejmu #' . $this->data['numer'];
		else
			return $this->data['tytul'];
		
	}
	
	/**
	 * @var string
	 */
	
	public function getDate(){
		
		return $this->data['data_start'];
		
	}
	 
	/**
	 * @var ep_Search
	 */
	public function search(){
		
		$search = new ep_Search();
		$search->addRawFilter('( (dataset:sejm_wystapienia AND _data_posiedzenie_id:"' . $this->data['id'] . '") OR (dataset:sejm_glosowania AND _data_posiedzenie_id:"' . $this->data['id'] . '") OR (dataset:sejm_posiedzenia_punkty AND _data_posiedzenie_id:"' . $this->data['id'] . '") )');
		return $search;
		
	}
}
