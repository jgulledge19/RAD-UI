<?php
	//////////////////////////////////////
	//	Date: 12/11/09
	//	Author: Joshua Gulledge
	//	name: data_grid.php
/**
 * 
 * purpose: to create a sortable data grid
 * 
 * Aspect 1: Create the HTML table
 * 
 * Aspect 2: Create the JS/CSS
 *
 * 
 * 
 */
class DataGrid{ 
	private $db = NULL;
	private $table = NULL;
	private $base_sql = NULL;
	private $pri = 'id';
	private $column_names=array();
	private $column_filters=array();
	private $where=NULL; 
	private $get_vars = array();
	private $order = 'ASC';
	private $order_by = NULL;
	private $offset=0;
	private $recordoffset = 0;
	private $per_page = 50;
	//private $get_vars_data = array();
	private $up_img=NULL;
	private $down_img = NULL;
	private $inactive_img = NULL;
	private $edit = false;
	private $delete = false;
	private $sql_con;
	
    /**
     * The config options
     * @param (Array) $config
     */
    protected $config;
    
    
    public function __construct($columns, $config=array(),$where=NULL){
        
        $this->config = array_merge(array(
            'tableClass' => '',
            'packageName' => '',
            
            // for images
            'image_up' => MODX_ASSETS_PATH.'components/radui/images/up_arrow.png',
            'image_down' => MODX_ASSETS_PATH.'components/radui/images/down_arrow.png',
            'image_inactive' => MODX_ASSETS_PATH.'components/radui/images/inactive_arrow.png',
            
        ),$config);
                
        
		
		$this->columns = $columns;// display name -> field
		// ??
		$this->where = $where;
		// Set the default images:
		$this->set_img();
		// get the query string data
		$this->set_var_data();
		if( isset($_REQUEST['offset']) && is_numeric($_REQUEST['offset']) ){
			$this->offset = $_REQUEST['offset'];
		}
	}
    
    
    
	/////////////
	// set filters
	/////////////
	public function set_column_filters($filters){
		/* example: 
		array(
			'field' => array(
						'filter' => 'Type',// array, custom, money
						'array' => array(),
						'custom' => 'str_replace DATA_FIELD')
			);*/
		$this->column_filters = $filters;
	}
	/////////////
	// Allow edit
	/////////////
	public function allow_edit($r=true){
		$this->edit = $r;
	}
	/////////////
	// Allow delete
	/////////////
	public function allow_delete($r=true){
		$this->delete = $r;
	}
	
	public function default_order($order='DESC',$field=NULL){
		if( !isset($_REQUEST['order']) ){
			$_REQUEST['order'] = $order;
		}
		if( !isset($_REQUEST['order_by']) && !empty($field) ){
			$_REQUEST['order_by'] = $field;
		}
		$this->set_var_data();
	}
	/////////////
	//	Get the URI query string and set the vars
	/////////////
	private function set_var_data(){
		$order='ASC';
		$tmp_link = 'DESC';
		$tmp_img = $this->up_img;
		if( isset($_REQUEST['order']) && $_REQUEST['order'] == 'DESC'){
			$order = 'DESC';
			$tmp_link = 'ASC';
			$tmp_img = $this->down_img;
		}
		$this->order = $order;
		foreach( $this->column_names as $name => $field ){
			$tmp = NULL;
			if( empty($this->order_by) ){
				$this->order_by = $field;
			}
			if( isset($_REQUEST['order_by']) && $field == $_REQUEST['order_by'] ){
				// this is the active one:
				$this->order_by = addslashes($field);
				$this->get_vars[$field] = array(
						'order' => $tmp_link,
						'img' => $tmp_img );
			} else { 
				// inactive:
				$this->get_vars[$field] = array(
						'order' => 'DESC',
						'img' => $this->inactive_img );
			}
		}
	}
	/////////////
	//	The base parms to send out to other URLs
	/////////////
	public function base_parms(){
		return '&amp;order_by='.$this->order_by.'&amp;order='.$this->order.'&amp;offset='.$this->offset;
	}
	/////////////
	//	The base sql, select * from tb where No order/limit
	/////////////
	public function base_sql($sql){
		$this->base_sql = $sql;
	}
	public function make_sql($order=true, $limit=true ) {
		
		if( !empty($this->base_sql) ){ 
			$sql = $this->base_sql."
				".(!empty($this->where) ? " WHERE ".$this->where : '' );
		} else {
			$sql = "SELECT * FROM ".$this->table."
				".(!empty($this->where) ? " WHERE ".$this->where : '' );
		}
		if ( $order ) {
			$sql .= "
				ORDER BY ".$this->order_by." ".$this->order;
		}
		if ( $limit ) {
			$sql .= "
				LIMIT ".$this->recordoffset.", ".$this->per_page." ";
		}
		$sql .= ";";
		return $sql;
	}
    
    
    /**
     * Column headers
     */
     
    /**
     * Columns
     */
    
    
    
	/**
     * HTML Grid
     */
	public function grid($per_page, $action, $ex_params=NULL, $where=NULL, $table_attributes=' class="table_list"'){
		$this->per_page = $per_page;
		if( !empty($where) ){
			$this->where = $where;
		}
		if( $this->offset > 0 ){
			$this->recordoffset = $this->offset*$per_page;
		}
		$text = ' 
		<table '.$table_attributes.'>
			<tr>
				';
		if( $this->edit && $this->delete ) {
			$text .= '
			<th style="width:80px;">&nbsp;</th>';
		} elseif( $this->edit || $this->delete ) {
			$text .= '
			<th style="width:25px;">&nbsp;</th>';
		}
		foreach( $this->column_names as $name => $field ) {
			$text .= '
			<th>'.$name.' <a href="?order_by='.$field.'&amp;order='.$this->get_vars[$field]['order'].
					'&amp;offset='.$this->offset.'&amp;action='.$action.$ex_params.'"><img src="'.$this->get_vars[$field]['img'].'" alt="-Order-" border="none"></a></th>';
		}
		$text .= '
			</tr>';
		$sql = $this->make_sql();
		/*if( !empty($this->base_sql) ){ 
			$sql = $this->base_sql."
				".(!empty($this->where) ? " WHERE ".$this->where : '' )."
				ORDER BY ".$this->order_by." ".$this->order."
				LIMIT ".$this->recordoffset.", ".$per_page."; ";
		} else {
			$sql = "SELECT * FROM ".$this->table."
				".(!empty($this->where) ? " WHERE ".$this->where : '' )."
				ORDER BY ".$this->order_by." ".$this->order."
				LIMIT ".$this->recordoffset.", ".$per_page."; ";
		}*/
		//echo '<pre>'.$sql.'</pre>';
		//get recordset
		$rs = $this->sql_con->createResultSet($sql, $this->db);// returns mysql_fetch_array 
		
		foreach($rs as $row ) {
			$text .= '
			<tr>';
			if($this->edit || $this->delete){
				$text .= '<td>';
				if( $this->edit ){ //|| ($edit_person && $user->person_id() == $row['person_id']) ){
					$text .= '<a href="?action='.$action.'&amp;command=3&amp;'.$this->pri.'='.$row[$this->pri].'&amp;order_by='.$this->order_by.
					'&amp;order='.$this->order.$ex_params.'" title="Edit record"><img src="'.ADMIN_IMAGES_DIR.'edit.png" alt="Edit" border="0"></a> ';
				}
				if($this->edit && $this->delete){
					$text .= ' | ';
				}
				if($this->delete){
					$text .= '<a href="?action='.$action.'&amp;command=4&amp;'.$this->pri.'='.$row[$this->pri].'&amp;order_by='.$this->order_by.
					'&amp;order='.$this->order.$ex_params.'" title="Delete record"><img src="'.ADMIN_IMAGES_DIR.'delete.png" alt="Delete Record" border="0"></a> ';
				}
				$text .= '</td>';
			}
			foreach( $this->column_names as $name => $field ){
				if( $field == 'email' ){
					$text .= '
					<td><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></td>';
				} elseif( !empty($this->column_filters[$field]) ){
					// set filters
					switch ($this->column_filters[$field]['filter']){
						case 'array':
							$tmp = '';
							if ( isset($this->column_filters[$field]['array'][$row[$field]])){
								$tmp = $this->column_filters[$field]['array'][$row[$field]];
							}
							$text .= '
							<td>'.$tmp.'</td>';
							break;
						case 'custom':
							$replace = array();
							foreach( $this->column_filters[$field]['replace'] as $r ){
								$replace[] = $row[$r]; 
							} 
							$empty = false;
							if ( isset($this->column_filters[$field]['empty_row']) ) {
								foreach( $this->column_filters[$field]['empty_row'] as $k => $v ){
									//echo ' K: '.$k.' => '.$v.' CID: '.$row[$k];
									if( $row[$k] == $v ){
										$empty = true;
									}
								}
							}
							if ( $empty ) {
								$text .= '
								<td></td>';
							} else {
								$text .= '
								<td>'.str_replace( 
									$this->column_filters[$field]['search'], 
									$replace, 
									$this->column_filters[$field]['str']).'</td>';
							}
							break;
						case 'date':
							$text .= '
							<td>'.date($this->column_filters[$field]['format'], strtotime($row[$field]) ).'</td>';
							break;
						case 'money':
							$text .= '
							<td>$'.number_format($row[$field],2).'</td>';
							break;
					}
				} else {
					$text .= '
					<td>'.( isset($row[$field]) ? $row[$field] : '' ).'</td>';
				}
			}
			$text .= '
			</tr>';
		}
	
		$text .= '
		</table>
		';
		$pagename = '';//basename($_SERVER['PHP_SELF']);
		//find total number of records
		$totalrecords = $rs->getUnlimitedNumberRows();
		$numpages = ceil($totalrecords/$per_page);
		//create category parameter
		$otherparameter = "&amp;action=".$action.'&amp;perpage='.$per_page.'&amp;order_by='.$this->order_by.'&amp;order='.$this->order.$ex_params;
		//create if needed  
		if($numpages > 1) { 
			//create navigator
			$nav = new PageNavigator($pagename, $totalrecords, $per_page, $this->recordoffset, 10, $otherparameter);
			$text .= $nav->getNavigator();
		}
		return $text;
	}
	/*
	public function set_table($table){
		$this->table = $table;
	}
	public function set_where(){
	
	}
	public function set_columns($cols){
		// field_name => array(display, )
		$this->columns = $cols;
	} */
	/*
	private function search?????(){
		
		$where = NULL;
		$lname = $fname = $comp = $e = $code = NULL;
		if( isset($_REQUEST['lname']) && !empty($_REQUEST['lname']) ){
			$lname = $_REQUEST['lname'];
			$where = " lastname LIKE '".addslashes($lname)."%'  ";
		}
		if( isset($_REQUEST['fname']) && !empty($_REQUEST['fname']) ){
			$fname = $_REQUEST['fname'];
			if( !empty($where) ) { 
				$where .= " AND ";
			}
			$where .= " firstname LIKE '".addslashes($fname)."%'  ";
		}
		if( isset($_REQUEST['comp']) && !empty($_REQUEST['comp']) ){
			$comp = $_REQUEST['comp'];
			if( !empty($where) ) { 
				$where .= " AND ";
			}
			$where .= " company LIKE '".addslashes($comp)."%'  ";
		}
		if( isset($_REQUEST['e']) && !empty($_REQUEST['e']) ){
			$e = $_REQUEST['e'];
			if( !empty($where) ) { 
				$where .= " AND ";
			}
			$where .= " email LIKE '".addslashes($e)."%'  ";
		}
		if( isset($_REQUEST['code']) && !empty($_REQUEST['code']) ){
			$code = $_REQUEST['code'];
			if( !empty($where) ) { 
				$where .= " AND ";
			}
			$where .= " customer_code LIKE '".addslashes($code)."%'  ";
		}
	}*/
}
?>