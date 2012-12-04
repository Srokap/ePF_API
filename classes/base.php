<?php

  
  // ep_Api
  
	class ep_Api {
	
		private $_version = '0.1';
		private $_protocol = 'http';
		private $_hostname = 'epanstwo.net';
		private $_api_server = '/api';
		private $_secret = 'WILDCARD';
		private $_key = 'WILDCARD';
		private $http_code = '';
	  
	
		/**
		 * @return ep_Api
		 */
		static public function init(){
			return new ep_Api();
		}
		
		public function call( $api, $params ){
		  return $_SERVER['M']->ep_api_call($api, $params);
		}
		
		/**
		 * @param array $params
		 * @return string
		 */
		private function generate_sig( $params ){	
			$str = json_encode( $params );
			$str .= $this->_secret;
		
			return md5( $str );		 
		}
	}


  
  
  
  
  
  
  
  
  
  // ep_Dataset
  
	class ep_Dataset extends ep_Api {
		
		public $name;
		public $mode;
		public $items_class;
		public $items_found_rows = 0;
		public $items = array();
		public $fields = array();
		public $data = array();
		public $limit = 0;
		public $offset = 0;
		public $performance = array();
		public $q;
		public $respect_limit = true;
		
		protected $_init_wheres = array();
		protected $_init_layers = array();
		protected $_runtime_wheres = array();
		protected $_init_orders = array();
		protected $_runtime_orders = array();
		protected $_init_keywords = array();
		protected $_runtime_keywords = array();
		protected $_runtime_layers = array();
		
	  
	  public function __construct( $name ) {
		  
		  $this->name = $name;
		  
	  }
	  
	  public function keyword($keyword){
		  
		  $this->add_runtime_keywords( $keyword );
			return $this;		
	  }
	  
		public function where( $key, $operator, $value ){
			$this->add_runtime_wheres( array( $this->name.'.'. $key, $operator, $value ) );
			return $this;		
		}
		
		public function init_where( $key, $operator, $value ){
			$this->add_init_wheres( array( $this->name.'.'. $key, $operator, $value ) );
			return $this;		
		}
		
		public function init_layer( $layer ) {
			if( $layer )
			  $this->_init_layers[] = $layer;
			return $this;
		}
		
		public function order_by( $field, $direction = 'ASC' ){

		  if( !$field )
		    return $this;
		  
		  $parts = explode('.', $field);
		  $parts_count = count($parts);
		  

		  if( $parts_count===1 )
		    $d_field = $this->name.'.'. $field;
		  else
			  $d_field = $parts[ $parts_count-2 ].'.'.$parts[ $parts_count-1 ];
		  
	  
		  $this->add_runtime_orders( array( $d_field, $direction ) );
		  
		    
			return $this;
		}
		
		
		
		public function get_info(){
			
			$data = $this->call( 'dataset-info', $this->name );			
			$this->data = $data['dataset'];
			$this->fields = $data['fields'];
						
		}
		
		public function set_limit( $l ){
			$this->limit = (int) $l;
			return $this;
		}
		
		
		public function find_all( $limit = null, $offset = null, $return_objects=true ){
			
			
			if( $limit )
			  $this->limit = $limit;
			
			if( $offset )
			  $this->offset = $offset;
						
			$params = $this->get_params();			
			$data = $this->call( 'dataset-search', $params );
			
			
		  $this->performance = $data['performance'];
		  $this->q = $data['query'];
			$this->items_class = $data['items_class'];
			$this->items = $data['items'];
			$this->items_found_rows = $data['items_found_rows'];
			$this->limit = $data['limit'];
			$this->mode = $data['mode'];
			
		
			
			$order = $data['order'];
			
			// var_export( $order );
			

			
			for( $i=0; $i<count($this->fields); $i++ ) {
		    if( $this->fields[$i]['field']==$order[0] ) {
		      $this->fields[$i]['selected'] = true;
		      $this->fields[$i]['direction'] = $order[1];
		    }
			}
			// var_export( $this->items ); die();
			
			$this->clear();
			
			if( $this->mode=='DBF' ) {
			  
			  
			  $class = $this->items_class;
				$result = array();
							
				if( $class && is_array($this->items) && !empty($this->items) ) {
					foreach( $this->items as $item ) {
						
						$data = json_decode( $item, true );
						
						if( $return_objects ) {
							
							$id = $data['id'];
							
							if( $id ) {
								$obj = new $class( $id, false );
								$obj->data = $data;
								$result[] = $obj;
						  }
							
						} else {
						
							$result[] = $data;
					  
					  }
				  
				  }
				
				return $result;
			  
			  }
			  			
			} else {
			
			
			
				if( $return_objects )
				  return $this->return_objects();
				else 
				  return $this->items;
				
			
			}
			
		}
		
		
		public function count(){
			$params = $this->get_params();
			unset( $params['l'] );
			unset( $params['of'] );
			unset( $params['o'] );
			
			$data = $this->call( 'dataset-count', $params );
				
			return $data['count'];
		}
		
		
		public function find_one($return_objects=true){
			$data = $this->find_all( 1, 0, $return_objects );
			return $data[0];
		}
	
		public function find($return_objects=true){
			return $this->find_one( $return_objects );
		}
		
			
		public function get_params(){
		  
		
			return array(
			  'name' => $this->name,
				'l'  => $this->limit,
				'of' => $this->offset,
				'j' => $this->_joins,
				'w' => $this->get_wheres(),
				'ls' => $this->get_layers(),
				'o'  => array_merge( $this->_init_orders, $this->_runtime_orders ),
				'k'  => array_merge( $this->_init_keywords, $this->_runtime_keywords ),
				'rl' => $this->respect_limit,
			);
		}
		
		
		
		protected function get_wheres(){
		  
		  
		  $result = array();
		  
		  for( $i=0; $i<count($this->_init_wheres); $i++ )
		    $result[] = $this->_init_wheres[$i];
		  
		  for( $i=0; $i<count($this->_runtime_wheres); $i++ )
		    $result[] = $this->_runtime_wheres[$i];
		  
		  
			return $result;
		}
		
		protected function get_layers(){
		  
		  
		  $result = array();
		  
		  for( $i=0; $i<count($this->_init_layers); $i++ )
		    $result[] = $this->_init_layers[$i];
		  
		  for( $i=0; $i<count($this->_runtime_layers); $i++ )
		    $result[] = $this->_runtime_layers[$i];
		  
		  
			return $result;
		}
		
		
		protected function return_objects(){
			
			$class = $this->items_class;
			$result = array();
						
			if( $class && is_array($this->items) && !empty($this->items) )
				foreach( $this->items as $item ) {
				  
				  /*
				  if( $item && $this->mode=='DBF' && $_SERVER['REMOTE_ADDR']=='80.72.34.251' )
					  $item = @json_decode( $item, true );
					*/
					
					if( $item )
						$result[] = new $class( $item, false );
			  
			  }
			
			return $result;
		}
		
		
		protected function clear(){
			$this->_runtime_wheres = array();
			$this->_runtime_orders = array();
		}
		
		public function get_runtime_wheres(){
			return $this->_runtime_wheres;
		}
	
		public function set_runtime_wheres( $val ){
			$this->_runtime_wheres = $val;
			return $this;
		}
	
		public function add_runtime_wheres( $val ){
			$this->_runtime_wheres[] = $val;
			return $this;
		}
		
		public function add_runtime_keywords( $keyword ){
			if( $keyword )
			  $this->_runtime_keywords[] = $keyword;
			return $this;
		}
	
		public function get_init_wheres(){
			return $this->_init_wheres;
		}
	
		public function set_init_wheres( $val ){
			$this->_init_wheres = $val;
			return $this;
		}
	
		public function add_init_where( $val ){
			$this->_init_wheres[] = $val;
			return $this;
		}
		
		public function add_init_wheres( $val ){
			$this->_init_wheres[] = $val;
			return $this;
		}
		
		public function get_runtime_orders(){
			return $this->_runtime_orders;
		}
	
		public function set_runtime_orders( $val ){
			$this->_runtime_orders = $val;
			return $this;
		}
	
		public function add_runtime_orders( $val ){
			$this->_runtime_orders[] = $val;
			return $this;
		}
	
		public function get_init_orders(){
			return $this->_init_orders;
		}
	
		public function set_init_orders( $val ){
			$this->_init_orders = $val;
			return $this;
		}
	
		public function add_init_orders( $val ){
			$this->_init_orders[] = $val;
			return $this;
		}
	
		
		
		
		/*
		protected $_projekty_dataset = null;
		public function projekty(){
			
			if( !$this->_projekty_dataset ) {
				
				
				// echo "\nDATASET - ".$this->name."\n";
				// var_export( $this->get_wheres() );
				
			  $this->_projekty_dataset = new ep_Dataset('projekty');
			  $this->_projekty_dataset->set_init_wheres( $this->get_wheres() );
			  $this->_projekty_dataset->set_joins( $this->_joins );
			  $this->_projekty_dataset->add_join( $this->name );
			}
			
			return $this->_projekty_dataset;
			
		}
		*/
		
		protected $_joins = array();
		public function add_join( $alias ){
		  $this->_joins[] = $alias;
		}
		
		public function set_joins( $joins ){
		  $this->_joins = $joins;
		}
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	// ep_Object
	
	abstract class ep_Object extends ep_Api {
	 
		public $id;
		public $data;
		private $loaded;
		public $layers = array();
			 
		public function __construct( $data, $complex = true ){
			//echo get_class( $this ) . " create\n";
			//var_dump( $data );
			$id = false;
		  
		  		  
		  if( $_SERVER['REMOTE_ADDR']=='80.72.34.251' ) {
			  // echo "<br/><br/>";
			  // var_export( $data );
			}
		  
		  
		  		  
			if( is_array( $data ) ) {
				
				// echo '-1<br/>';
				
				$id = false;
				foreach( $this->_aliases as $alias )
				  if( $id = $data[ $alias.'.id'] )
				    break;
			
			} elseif( $this->_field_init_lookup && is_string( $data ) && !is_numeric( $data ) ) {
	    
				// echo '-2<br/>';

		    $dataset = new ep_Dataset( $this->_aliases[0] );
	      $data = $dataset->where($this->_field_init_lookup, '=', $data)->find_one( false );
	      unset( $dataset );
	      $id = (int) $data[ $this->_aliases[0].'.id' ];

			} elseif( $data ) {

				// echo '-3<br/>';

				$id = $data;
				unset( $data );

			}
			
								  
			if( !$id ){
				return false;
			}
			
			$this->id = $id;
	    
	    
	    
			if( isset( $data ) ) {
				$this->loaded = $this->parse_data( $data );
			} else {
				if( $complex ){
					$this->load();
				} else {
					$this->loaded = true;
				}
			}
		}
		
		function load(){
			$this->load_from_db();
		} 
		 
		function load_from_db(){
		
		  $dataset = new ep_Dataset( $this->_aliases[0] );
      $data = $dataset->where('id', '=', $this->id)->find_one( false );

      
      if( $dataset->mode=='DBF' ) {
	      
	      $this->data = $data;
	      
      } else {
            	
				if( $data ) {
					$this->parse_data( $data );
				} else {
					$this->loaded = false;	
				}
			
			}

      unset( $dataset );

		}
		 
		function parse_data( $data ){
		  		  
			$children = array();
			if( is_array($data) && !empty($data) ) {
			  
			  $layers = array();
			  
				foreach( $data as $k => $v ) {
					
					$parts = explode('.', $k);					
				  if( $parts[0]=='layer' ) {
					  $layers[ $parts[1] ][$parts[2] ] = $v;
				  }
					
					$parts_count = count($parts);
					if( $parts_count<2 )
					  break;
					
					$alias = $parts[ $parts_count-2 ];
					$key = $parts[ $parts_count-1 ];
					
					if( in_array($alias, $this->_aliases) )
						$this->data[ $key ] = $v;
					else
						$children[ $alias ][ $alias.'.'.$key ] =  $v ;
					
				}
				
				
							
				foreach( $children as $k => $v ){
					$method = 'set_ep_' . $k;
					
					// echo "\n".$method;
	
					if( method_exists( $this, $method ) ) {
					  // var_export( $v );
						call_user_func_array(array($this, $method), array($data));
				  }
	
				}
				
				if( !empty($layers) )
				  $this->layers = array_merge( $this->layers, $layers );
				
				
			}
			
			
			return true;
		}
		 
		function isloaded(){
			return (Boolean) $this->loaded;
		}
		
		function load_layer($layer, $params = false) {
			$data = $this->call('load-layer', array(
			  'object' => get_class( $this ),
			  'id' => $this->id,
			  'layer' => $layer,
			  'params' => $params,
			));
				
						  		
			if( $data )
			  $this->layers[ $layer ] = $data['data'];
		}
		
		public function getTitle(){
			
			foreach( array('tytul', 'nazwa', 'label', 'sygnatura', 'kod') as $key )
			  if( $this->data[ $key ] )
			    return $this->data[ $key ];
			    
			return '';
					   
		}
		
		public function getDescription(){
			
			return false;
			
		}
		 
	}
	
	
	
	
	
	
	
	class ep_Search extends ep_Api {
	
	  public $q;
	  public $items;
	  public $tabs;
	  public $limit;
	  public $items_found_rows;
	  public $dataset;
	  
	  public function set_q( $q ) {
		  
		  $this->q = trim( $q );
		  return $this;
		  
	  }
	  
	  public function set_dataset( $dataset ) {
		  
		  $this->dataset = trim( $dataset );
		  return $this;
		  
	  }
	  
	  public function find_all($limit, $offset){
		  
		  $this->limit = 20;
		  if( $offset )
			  $this->offset = $offset;
		  
		  $params = array(
		    'q' => $this->q,
		    'l' => $this->limit,
				'of' => $this->offset,
		  );
		  
		  if( $this->dataset )
		    $params['d'] = $this->dataset;
		  
		  $data = $this->call( 'search', $params );
		  $this->items = array();
		  $this->tabs = $data['tabs'];
		  $this->limit = $data['limit'];
		  $this->items_found_rows = $data['items_found_rows'];
		  
		  foreach( $data['items'] as $i ) {
			  
			  $class = $i['class'];
			  $object_id = $i['object_id'];
			  $o = new $class( $object_id, false );
			  $o->data = json_decode( $i['data'], true );
			  
			  $this->items[] = $o;
			  
		  }
		  		  		  
		  return $this->items;
		  
	  }
	
	}
	
	
	
	
	
	
	
	@include_once('objects/ep_Gmina.php');
	@include_once('objects/ep_Powiat.php');
	@include_once('objects/ep_Wojewodztwo.php');
	@include_once('objects/ep_Posel.php');
	@include_once('objects/ep_Legislacja_Projekt.php');
	@include_once('objects/ep_Czlowiek.php');
	@include_once('objects/ep_Sejm_Druk.php');
	@include_once('objects/ep_Senat_Druk.php');
	@include_once('objects/ep_Sejm_Interpelacja.php');
	@include_once('objects/ep_Sejm_Klub.php');
	@include_once('objects/ep_Prawo.php');
	@include_once('objects/ep_Ustawa.php');
	@include_once('objects/ep_Senator.php');
	@include_once('objects/ep_Sejm_Komisja.php');
	@include_once('objects/ep_Sejm_Posiedzenie.php');
	@include_once('objects/ep_Sejm_Wystapienie.php');
	@include_once('objects/ep_Sejm_Glosowanie.php');
	@include_once('objects/ep_Sejm_Sprawozdanie.php');
	@include_once('objects/ep_Sejm_Dezyderat.php');
	@include_once('objects/ep_ISIP_Plik.php');
	@include_once('objects/ep_Posel_Rejestr_Korzysci.php');
	@include_once('objects/ep_Posel_Oswiadczenie_Majatkowe.php');
	@include_once('objects/ep__Dataset.php');
	@include_once('objects/ep__Object.php');
	@include_once('objects/ep_Stanowisko.php');
	@include_once('objects/ep_Posel_Komisja_Stanowisko.php');
	@include_once('objects/ep_Wspolpracownik_Posla.php');
	@include_once('objects/ep_Sejm_Okreg_Wyborczy.php');
	@include_once('objects/ep_Posel_Glos.php');
	@include_once('objects/ep_KRS_Wpis.php');
	@include_once('objects/ep_Instytucja.php');
	@include_once('objects/ep_SA_Orzeczenie.php');
	@include_once('objects/ep_SA_Sad.php');
	@include_once('objects/ep_SA_Skarzony_Organ.php');
	@include_once('objects/ep_SA_Orzeczenie_Wynik.php');
	@include_once('objects/ep_Sejm_Posiedzenie_Punkt.php');
	@include_once('objects/ep_SA_Sedzia.php');
	@include_once('objects/ep_NIK_Raport.php');
	@include_once('objects/ep_RCL_Projekt.php');
	@include_once('objects/ep_RCL_Projekt_Etap.php');
	@include_once('objects/ep_RCL_Dokument.php');
	@include_once('objects/ep_Miejscowosc.php');
	@include_once('objects/ep_PNA.php');
    @include_once('objects/ep_Kod_Pocztowy.php');
    @include_once('objects/ep_Bip_Instytucja.php');
    @include_once('objects/ep_RCL_Projekt_Status.php');
    @include_once('objects/ep_Sejm_Glosowanie_Typ.php');
    @include_once('objects/ep_Sejm_Druk_Typ.php');
    @include_once('objects/ep_Sejm_Posiedzenie_Debata.php');
    @include_once('objects/ep_Glosowanie_Sejmowe_Glos.php');
    @include_once('objects/ep_Prawo_Typ.php');
    @include_once('objects/ep_Sejm_Pytanie_Biezace.php');
    @include_once('objects/ep_Sejm_Interpelacja_Pismo.php');
    @include_once('objects/ep_Legislacja_Projekt_Etap.php');
    @include_once('objects/ep_Legislacja_Projekt_Status.php');
    @include_once('objects/ep_Legislacja_Projekt_Podpis.php');
    @include_once('objects/ep_Posel_Aktywnosc.php');
    @include_once('objects/ep_Sejm_Dzien.php');
    @include_once('objects/ep_Sejm_Wniesiony_Projekt.php');
    @include_once('objects/ep_Posel_Wspolpracownik.php');  

  
  
  
 
 /* 
  @include_once('objects/ep_Orzeczenie_sn_typ.php');
  @include_once('objects/ep_SN_Izba.php');  
  @include_once('objects/ep_Orzeczenie_sn_stanowisko.php');
  @include_once('objects/ep_Orzeczenie_sn_blok.php');
  @include_once('objects/ep_Orzeczenie_sn_osoba_stanowisko.php');
  @include_once('objects/ep_SN_Osoba.php');
  @include_once('objects/ep_SN_Orzeczenie.php');
*/  
  @include_once('objects/ep_SN_Orzeczenie_Autor.php');
  @include_once('objects/ep_SN_Orzeczenie_Forma.php');
  @include_once('objects/ep_SN_Orzeczenie_Izba.php');
  @include_once('objects/ep_SN_Izba.php');
  @include_once('objects/ep_SN_Jednostka.php');
  @include_once('objects/ep_SN_Osoba.php');
  @include_once('objects/ep_SN_Sklad.php');
  @include_once('objects/ep_SN_Orzeczenie_Sedzia.php');
  @include_once('objects/sp_Orzeczenie_SN_Sprawozdawca.php');
  @include_once('objects/ep_SN_Wspolsprawozdawca.php');  
  @include_once('objects/ep_SN_Orzeczenie.php');
  
	
	
  @include_once('objects/ep_SP_Stanowisko.php');
  @include_once('objects/ep_SP_Orzeczenie_Czesc.php');
  @include_once('objects/ep_SP_Orzeczenie_Przepis.php');
  @include_once('objects/ep_SP_Haslo_Tematyczne.php');
  @include_once('objects/ep_SP_Haslo.php');
  @include_once('objects/ep_SP_Sad.php');
  @include_once('objects/ep_Orzeczenie_sp_osoba_stanowisko.php');
  @include_once('objects/ep_SP_Osoba.php');
  @include_once('objects/ep_SP_Orzeczenie.php');
  @include_once('objects/ep_SP_Teza.php');
	
  @include_once('objects/ep_BDL_Kategoria.php');
  @include_once('objects/ep_BDL_Podgrupa.php');
  @include_once('objects/ep_BDL_Grupa.php');
  @include_once('objects/ep_BDL_Wskaznik_Wariacja.php');
  
  @include_once('objects/ep_Twitt.php');
  @include_once('objects/ep_Twitt_Tag.php');
  
  @include_once('objects/ep_Sejm_Komunikat.php');

  @include_once('objects/ep_Senat_senator_komisja.php'); 
  @include_once('objects/ep_Senat_komisja.php');   
  @include_once('objects/ep_Senat_senator_zespol_parlamentarny.php');
  @include_once('objects/ep_Senat_zespol_parlamentarny.php');
  @include_once('objects/ep_Senat_senator_zespol_senacki.php');
  @include_once('objects/ep_Senat_Zespol.php');
  @include_once('objects/ep_Senat_Oswiadczenie_Majatkowe.php');  
  @include_once('objects/ep_Senat_rejestr_korzysci.php'); 
  
  
  
  
  
  @include_once('objects/ep_Senat_Wystapienie.php');
  @include_once('objects/ep_Sejm_Glosowanie_Glos.php'); 
  
  
  
  
  
     
    
  	
  