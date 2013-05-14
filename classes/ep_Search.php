<?php

/**
 * @file
 * Ten plik jest częścią biblioteki ePF_API.
 */

/**
 * Klasa ep_Search - podstawowy składnik biblioteki.
 *
 * Odpowiada za konstrukcję zapytań bazodanowych.
 *
 * @category   API
 * @package    ePF_API
 * @subpackage Core
 * @version    0.x.x-dev
 * @since      version 0.1.0
 */
 
 
 
class ep_Search extends ep_Api {  
  
  private $datasets = array();
  private $filters = array();
  private $raw_filters = array();
  private $orders = array();
  private $objects = array();
  private $pagination = array();
  private $q = false;
  private $order_index = 0;
  public $QTime = false;
  public $solr_params = array();
  

  function __construct( $params = null ) {
	  
	  // parent::__construct();
	  if( is_string($params) )
	    return $this->setDataset( $params );
	  
  }
  
  
  public function setDataset( $dataset ){
	  
	  $this->datasets = array(
	    $dataset,
	  );
	  return $this;
	  
  }
  
  public function setQ( $q ){
	  
	  if( $q )
	    $this->q = $q;
	    
	  return $this;
	  
  }
  
  public function getQ(){
	  
	  return $this->q;
	  
  }
  
  public function addRawFilter( $q ) {
	  
	  $this->raw_filters[] = $q;
	  
  }
  
  public function addFilter( $field, $value ){
	  
	  return $this->setFilter(array(
	  	'field' => $field,
	  	'value' => $value,
	  ));
	  
  }
  
  public function addOrder($field, $direction = 'desc'){
	  
	  return $this->setOrder(array(
	  	'field' => $field,
	  	'selected_index' => $this->order_index,
	  	'selected_direction' => $direction,
	  ));
	  
	  $this->order_index++;
	  
  }
  
  private function setFilter($filter){
	  
	  if( !$filter['field'] )
	    return false;
	  
	  for( $i=0; $i<count($this->filters); $i++ ) {
		  
		  if( $this->filters[$i]['field']==$filter['field'] ) {
		    $this->filters[$i] = array_merge($this->filters[$i], $filter);
		    return $this;
		  }
		  
	  }
	  
	  $this->filters[] = $filter;
	  return $this;
	  
  }
  
  private function setOrder($order){
	  	  
	  if( !$order['field'] )
	    return false;
	  
	  for( $i=0; $i<count($this->orders); $i++ ) {
		  
		  if( $this->orders[$i]['field']==$order['field'] ) {
		    $this->orders[$i] = array_merge($this->orders[$i], $order);
		    return $this;
		  }
		  
	  }
	  
	  $this->orders	[] = $order;
	  return $this;
	  
  }
  
  public function load($limit = null, $page = null, $_params = array()){
	  
	  $params = $this->getParams();
	  $params = array_merge($params, array(
	    'page' => $page,
	    'limit' => $limit,
	  ));
	  $params = array_merge($params, $_params);
	  
	  $response = $this->call( 'ep_Search/load', $params );
	  return $this->process_load_response( $response );	  
	  
  }
  
  public function loadObjects($limit = null, $page = null){
	  
	  $params = $this->getParams();
	  $params = array_merge($params, array(
	    'page' => $page,
	    'limit' => $limit,
	  ));
	  
	  // var_export( $params ); die();
	  
	  $response = $this->call( 'ep_Search/loadObjects', $params );
	  return $this->process_load_response( $response );
		  
  }
  
  public function loadFilters( $filters = array() ){
	 	
	 	$params = $this->getParams();
	  $params = array_merge($params, array(
	    'filters' => $filters,
	  ));
	  
	  $response = $this->call('ep_Search/loadFilters', $params);	  
	  return $this->process_load_response( $response );

  }
  
  private function process_load_response( $response ){
	  
	  if( isset($response['items']) && is_array($response['items']) ) {
	  	
	  	$this->QTime = isset($response['QTime']) ? $response['QTime'] : 0;
	  	$this->solr_params = isset($response['solr_params']) ? $response['solr_params'] : array();
	  	$this->objects = array();

	  	foreach( $response['items'] as $item ) {		  	
		  	
				$class = $item['class'];
			  $object_id = $item['object_id'];
			  $object = new $class( $object_id, false );
			  $object->data = $item['data'];
			  $object->layers['score'] = $item['score'];
			  
			  if( isset($item['hl']) )
				  $object->layers['hl'] = $item['hl'];
			  
			  $this->objects[] = $object;
		  	
	  	}
	  	
	  }
	  
	  
	  
	  if( isset( $response['pagination'] ) )
		  $this->pagination = $response['pagination'];
	  
	  
	  if( isset( $response['filters'] ) )
	  	foreach( $response['filters'] as $filter )
		  	$this->setFilter( $filter );
		
		 	
		if( isset( $response['orders'] ) )
	  	foreach( $response['orders'] as $order )
		  	$this->setOrder( $order );
		  	
	  
	  return $this;
	    
  }
  
  public function getObjects(){
	  
	  return $this->objects;
	  
  }
  
  public function getPagination(){
	  
	  return $this->pagination;
	  
  }
  
  public function getFilters(){
	  
	  return $this->filters;
	  
  }
  
  public function getOrders(){
	  
	  return $this->orders;
	  
  }
  
  private function getParams(){
	  
	  $result = array(
	    'datasets' => $this->datasets,
	    'q' => $this->q,
	    'filters' => $this->filters,
	    'raw_filters' => $this->raw_filters,
	    'orders' => $this->orders,
	  );
	  return $result;
	  
  }
  
  public function getQueryString(){
	  
	  $params = array();
	  
	  if( !empty($this->filters) )
	  	foreach( $this->filters as $f )
	  		if( isset($f['value']) && $f['value'] )
		  		$params[ 'f_' . $f['field'] ] = $f['value'];
		  		
		if( !empty($this->orders) )
	  	foreach( $this->orders as $o )
	  		if( isset($o['selected_index']) )
		  		$params[ 'o' ] = $o['field'];
	  
	  
	  
	  $server = (count($this->datasets)===1) ? $this->datasets[0] : 'dane';
 	  return $server . '?' . http_build_query( $params );
	  
  }

}