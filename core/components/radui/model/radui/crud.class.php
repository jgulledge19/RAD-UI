<?php

/**
 * Crud - C.R.U.D - Create, Read, Update and Delete - Single Table
 * 
 */
class Crud {
	/**
     * @param (String) classKey (s)
     * language
     * default sort field and dir?
     * 
     * @access protected
     */
    protected $classKey = '';
    /**
     * @param (String) $primaryKey
     * @access protected
     */
    protected $primaryKey = 'id';
    /**
     * @param (Array) $inputData
     * @access protected
     */
    protected $inputData = array();
    /**
     * 
     */
    protected $config = array();
    /**
     * @param modx $modx 
     * 
     */
    public $modx;
    
    /**
     * @param (String) $error_message
     * 
     */
    protected $error_message;
    /**
     * @param $modx
     * @param $config - array() of config options like array('corePath'=>'Path.../core/)
     */
    function __construct(modX &$modx, $scriptProperties) {
        $this->modx =& $modx;
        $this->scriptProperties = $scriptProperties;
	    
    	/* load data packages */
		$this->config['package'] = $this->modx->getOption('package',$scriptProperties,'');
        $this->config['packagePath'] = MODX_CORE_PATH.$this->modx->getOption('packagePath',$scriptProperties,'');
        $this->config['lexicon'] = $this->modx->getOption('lexicon',$scriptProperties, $this->config['package'].':default');
        
        $this->modx->addPackage($this->config['package'], $this->config['packagePath']);
        
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load($this->config['lexicon']);
        }
        
		$this->classKey = $this->modx->getOption('classKey',$scriptProperties, '');
        $this->primaryKey = $this->modx->getOption('primaryKey',$scriptProperties, 'id');
        
        
        // load package manually:
        $corePath = $this->modx->getOption('core_path').'components/'.$this->config['package'].'/model/';
                
        $this->modx->addPackage($this->config['package'],$corePath);
        
		
	}
    
    
    
    /**
     * (Read)
     */
    public function getList(){
        
        $isLimit = !empty($_REQUEST['limit']);
        $start = $this->modx->getOption('start',$_REQUEST,0);
        $limit = $this->modx->getOption('limit',$_REQUEST,999999);
        $sort = $this->modx->getOption('sort',$_REQUEST,'id');
        $dir = $this->modx->getOption('dir',$_REQUEST,'DESC');
        $query = $this->modx->getOption('query',$_REQUEST,'');// search
        
        //print_r($this->config);
        //echo '<br>K: '.$this->classKey;
        // $groupfilter = $modx->getOption('groupfilter',$_REQUEST,'');
        
        /* query for subscribers */
        $c = $this->modx->newQuery($this->classKey);
        // @TODO allow for joins via the snippet call
        
        $c = $this->searchList($c);
        
        $c = $this->excludeList($c);
        
        $c = $this->sortList($c);
        
        $totalRecords = $this->modx->getCount($this->classKey,$c);
        
        $c->sortby($sort,$dir);
        if ($isLimit) {
            $c->limit($limit,$start);
        }
        $count = $this->modx->getCount($this->classKey,$c);
        
        $items = $this->modx->getCollection($this->classKey,$c);
        
        /* iterate through subscribers */
        $data = array();
        if ($count > 0) {
            $item_count = 1;
            foreach ($items as $item) {
                $item = $item->toArray();
                if ( $this->canUpdate() ) {
                    $item['editLink'] = 'Edit';
                }
                if ( $this->canDelete() ) {
                    $item['deleteLink'] = 'Delete';
                }
                $item['item_kount'] = $item_count++;
                // what about custom filters?
                $data[] = $item;
            }
        }
        if ( 1 == 1 ) {
            $status = 'success';
        }
        return $this->makeJson($status, $count.' records were returned, with a total of '. $totalRecords.' records', $totalRecords, $count, $data); //$list,$count);
    }
    /**
     * (Create)
     * Create a record/row
     * @param (Array) $data - name=>value
     * @param (Boolean) $return_json - returns json on true or the created item on false
     * @return (MIXED)  - (JSON String) $json or $item
     */
    public function create($data=NULL, $return_json=TRUE) {
        if (empty($data) ) {
            $data = $_POST;
        }
        $this->inputData = $data;
        
        if ( !$this->canCreate() ) {
            $status = 'failed';
            $message = 'You do not have permissions to add the item.';
        } else if ( $this->isDuplicate() ) {
            $status = 'failed';
            $message = 'This item appears to be a duplicate';
        } else {
            // this is a new object
            $item = $this->modx->newObject($this->classKey);
            $this->filterCreate();
            if ( $this->validate() && is_object($item) ) {
                
                $item->fromArray($this->inputData);
                /// $item->set('date_created', date('Y-m-d H:i:s'));
                $item = $this->setCreateDefaults($item);
                
                if ($item->save()) {
                    if ( $this->createDependants($item)) { 
                        $status = 'success';
                        $message = 'New record created';
                    } else {
                        $status = 'failed';
                        $message = 'Dependants where not created';
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI/Crud/create()] Error saving item in: '.$this->classKey.' on: '.$_SERVER['REQUEST_URI'].' Q: '. $_SERVER['QUERY_STRING'] );
                    $status = 'failed';
                    $message = 'Error creating new record, did not save';
                }
            } else {
                $status = 'failed';
                $message = $this->error_message;
            }
        }
        $data = array();
        if ( is_object($item) ){
            $item = $item->toArray();
        } else {
            $item = array();
        }
        if ( $return_json ){
            $data[] = $item;
            return $this->makeJson($status, $message, 1, 1, $data);
        } else {
            return $item;
        }
    }
    /**
     * (Update)
     * Update a record/row
     * @param (Array) $data - name=>value
     * @return (JSON String) $json
     */
    public function update($data=NULL) {
        if (empty($data) ) {
            $data = $_POST;
        }
        $this->inputData = $data;
        
        if ( !$this->canUpdate() ) {
            $status = 'failed';
            $message = 'You do not have permissions to update the item.';
        } else if ( !$this->isUnique() ) {
            $status = 'failed';
            $message = 'The field X needs to be unique, the value X is already used.';
        } else {
            // this is a new object
            $pri = (isset($this->inputData[$this->primaryKey]) ? $this->inputData[$this->primaryKey] : 0 );
            $this->filterUpdate();
            $item = $this->modx->getObject($this->classKey, array($this->primaryKey => $pri));
            if ( empty($item) ) {
                $status = 'failed';
                $message = 'Item not found';
            } else if ( $this->validate() ) {
                
                $item->fromArray($this->inputData);
                /// $item->set('date_created', date('Y-m-d H:i:s'));
                $item = $this->setUpdateDefaults($item);
                
                if ($item->save()) {
                    if ( $this->updateDependants($item) ) {
                        $status = 'success';
                        $message = 'Updated record';
                    } else {
                        $status = 'success';
                        $message = 'Dependants where not updated';
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI/Crud/create()] Error updating item in: '.$this->classKey.' on: '.$_SERVER['REQUEST_URI'].' Q: '. $_SERVER['QUERY_STRING'] );
                    $status = 'failed';
                    $message = 'Error updating record, did not save';
                }
            } else {
                $status = 'failed';
                $message = $this->error_message;
            }
        }
        $data = array();
        if (!empty($item) ) {
            $item = $item->toArray();
            $data[] = $item;
        } else {
            $data[]=array();
        }
        
        //echo 'M: '.$message;
        return $this->makeJson($status, $message, 1, 1, $data); 
    }
    
    /**
     * 
     */
    public function saveMany($value='') {
        
    }
    /**
     * Delete a record/row
     * @param (Array) $data - name=>value
     * @return (JSON String) $json
     */
    public function delete ($data=NULL) {
        if (empty($data) ) {
            $data = $_POST;
        }
        $this->inputData = $data;
        if ( !$this->canDelete() ) {
            $status = 'failed';
            $message = 'You do not have permissions to delete the item.';
        } else {
            // this is a new object
            $pri = (isset($this->inputData[$this->primaryKey]) ? $this->inputData[$this->primaryKey] : 0 );
            $item = $this->modx->getObject($this->classKey, array($this->primaryKey => $pri));
            if ( empty($item) ) {
                $status = 'failed';
                $message = 'Item not found';
            } else {
                // @TODO have a custom function here?
                
                if ($item->remove()) {
                    if ( $this->deleteDependants($item) ) {
                        $status = 'success';
                        $message = 'Deleted record';
                    } else {
                        $status = 'success';
                        $message = 'Dependants where not deleted';
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI/Crud/create()] Error deleting item in: '.$this->classKey.' on: '.$_SERVER['REQUEST_URI'].' Q: '. $_SERVER['QUERY_STRING'] );
                    $status = 'failed';
                    $message = 'Error deleting record';
                }
            }
        }
        $data = array();
        $item = $item->toArray();
        $data[] = $item;
        return $this->makeJson($status, $message, 1, 1, $data);
    }
    
    /**
     * Make JSON (jsonp?)
     */
    public function makeJson($status, $message, $totalRecords, $count, $data) {
        $output = array(
            'status' => $status, 
            'message' => $message,
            "sEcho" => ( isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 0 ),// what is this for?
            "total" => ($totalRecords),// total in DB
            "count" => ($count),
            "data" => $data
        );
        
        if ( isset($_REQUEST['callback'] ) ) {
            return $_REQUEST['callback'] . '('.json_encode($output).')';
        } else {
            return json_encode( $output );
        }
    }
    
    
    /*******************************
     * Permissions
     ******************************/
     
    /**
     * canView - permission to view record(s)
     * @return Boolean
     */
    public function canView($value='') {
        return true;
    }
    /**
     * canCreate - permission to create record(s)
     * @return Boolean
     */
    public function canCreate($value='') {
        return true;
    }
    /**
     * canUpdate - permission to update record(s)
     * @return Boolean
     */
    public function canUpdate($value='') {
        return true;
        
    }
    /**
     * canDelete - permission to delete record(s)
     * @return Boolean
     */
    public function canDelete($value='') {
        return true;
        
    }
    
    
    
    /*********************************
     * 
     * Parent Classes should/can override these methods below - easy extending for development:
     * 
     *********************************/
    
    /**
     * Create Dependants, children, parents, ect..  
     * @Override this method is meant to be overridden 
     * @param (object) $item
     * @return Boolean
     */
    public function createDependants($item) {
        return TRUE;
    }
    /**
     * Update Dependants, children, parents, ect..  
     * @Override this method is meant to be overridden 
     * @param (object) $item
     * @return Boolean
     */
    public function updateDependants($item) {
        return TRUE;
    }
    /**
     * Delete Dependants, children, parents, ect..
     * Note if you did your XPDO schema correctly all childrened should be deleted/removed when the parent is.  
     * @Override this method is meant to be overridden 
     * @param (object) $item
     * @return Boolean
     */
    public function deleteDependants($item) {
        return TRUE;
    }
    
    /**
     * set a custom sort option, can set these basied on user/group
     * @param (object) $query
     * @return (Object) $query
     */
    protected function sortList($query) {
        // @TODO make default sort 
        return $query;
    }
    /**
     * set a custom search option, can set these basied on user/group
     * @param (object) $query
     * @return (Object) $query
     */
    protected function searchList($query) {
        // default search
        // search fields: (must be prefixed with search_ )
        $search = array();
        foreach ( $_REQUEST as $name => $value ) {
            if ( strpos($name,'search_') === 0 && !empty($value) ) {
                // 'start_date:LIKE' => $start_where.'%'
                $search[str_replace('search_', '', $name).':LIKE'] = $value.'%';
            }
        }
        if ( count($search) > 0 ) {
            // @TODO verify that they are valid column fields - see: xPDOManager::getColumnDef()
            $query->where($search);
        }
         
        return $query;
    }
    /**
     * set a custom search exclude option, can set these basied on user/group
     * @param (object) $query
     * @return (Object) $query
     */
    protected function excludeList($query) {
        // default search
        // search fields: (must be prefixed with search_ )
        $exclude = array();
        foreach ( $_REQUEST as $name => $value ) {
            if ( strpos($name,'exclude_') === 0 && !empty($value) ) {
                // 'start_date:LIKE' => $start_where.'%'
                $exclude[str_replace('exclude_', '', $name).':NOT LIKE'] = $value.'%';
            }
        }
        if ( count($exclude) > 0 ) {
            // @TODO verify that they are valid column fields - see: xPDOManager::getColumnDef()
            $query->where($exclude);
        }
         
        return $query;
    }
    /**
     * Check for a duplicate on create and on update
     * @param () 
     * 
     * @return Boolean - true if there is a duplicate, false otherwise 
     */
    protected function isDuplicate() {
        // @TODO create a generic check for dupilcate function
        return false;
    }
    /**
     * validate the data
     * @return Boolean
     */
    protected function validate() {
        // @TODO filter/validate the POST data
        $this->error_message = 'Validation failed';
        return TRUE;
    }
    /**
     * set custom defaults for fields
     * @param (object) $item
     * @return (Object) $item
     */
    protected function setCreateDefaults($item) {
        // @TODO make default?
        return $item;
    }
    
    /**
     * set custom defaults for fields
     * @param (object) $item
     * @return (Object) $item
     */
    protected function setUpdateDefaults($item) {
        // @TODO make default?
        return $item;
    }
    /**
     * Check if the updated value is unique.  Example you allow user to change the username for their login
     *      but this must be unique(one of a kind), check that in this method
     * @return boolean
     */
    protected function isUnique() {
        return TRUE;
    }
    
    /**
     * Filter input data - this is not validataion.  Example you allow the user to set a column with readable values
     *      but these need to corrispond to a table ID.  Filter that here
     * @return VOID
     */
    protected function filterCreate() {
        // filter $this->inputData
        return;
    }
    /**
     * Filter input data - this is not validataion.  Example you allow the user to set a column with readable values
     *      but these need to corrispond to a table ID.  Filter that here
     * @return VOID
     */
    protected function filterUpdate() {
        // filter the $this->inputData
        return;
    }
    
    /**
     * @TODO make a generic import from CSV
     */
        /**
         * Import file must have the proper field name in row one for each column
         * 
         * 1. create an array for the imported data: import[#][name]=column value
         * 2. iterate through each row and save the row in a db as JSON - columns: id/status/classKey/insertid(the id of the inserted record)/data/insert_date/user_id
         * 3. now loop thourgh and insert one record at a time useing the $this->create() method
         * 4. send message back to user of success/failer/duplicates
         * 
         */
    /**
     * Import data
     * @TODO write to tmp table for better record tracking
     */
    public function import() {
        $newData = array();
        $total = 0;
        if (!empty($_FILES['csvFile']['name']) && !empty($_FILES['csvFile']['tmp_name'])) {
            require_once 'csv.class.php';
            $csv = new CSV();
            $data = $csv->import($_FILES['csvFile']['tmp_name']);
            
            foreach ( $data as $item ) {
                $newData[] = $this->create($item, false);
                $total++;
            }
            $status = 'success';
            $message = 'Data was imported';
        } else {
            $status = 'failed';
            $message = 'File did not upload';
            $newData[] = array();
        }
        //$item = $item->toArray();
        //$data[] = $item;
        return $this->makeJson($status, $message, $total, $total, $newData);
        
    }
    /**
     * @TODO make a generic export to CSV
     */
    public function export($type='CSV') {
        
    }
    
    /**
     * create a chart data array line
     * 
     */
    public function makeChartData() {
        $isLimit = !empty($_REQUEST['limit']);
        $start = $this->modx->getOption('start',$_REQUEST,0);
        $limit = $this->modx->getOption('limit',$_REQUEST,999999);
        $sort = $this->modx->getOption('sort',$_REQUEST,'id');
        $dir = $this->modx->getOption('dir',$_REQUEST,'DESC');
        $search = $this->modx->getOption('query',$_REQUEST,'');// search
        $groupby = $this->modx->getOption('groupby',$_REQUEST,'');
        $label = $this->modx->getOption('label',$_REQUEST,'Data');
        
        /* query for */
        $query = $this->modx->newQuery($this->classKey);
        // @TODO allow for joins via the snippet call
        
        $xaxis = $this->modx->getOption('xAxis',$_REQUEST,'');
        $yaxis = $this->modx->getOption('yAxis',$_REQUEST,'');
        $xQuote = $this->modx->getOption('xQuote',$_REQUEST, TRUE);
        $yQuote = $this->modx->getOption('yQuote',$_REQUEST, FALSE);
        $series = $this->modx->getOption('series',$_REQUEST, FALSE );
        // $series = false;
        // echo "X: ".$xaxis.' - Y: '.$yaxis;
        
        if ( !empty($groupby) ) {
            // $this->primaryKey
            $query->select('`'.$this->classKey.'`.`'.$this->primaryKey.'` AS `'.$this->classKey.'_'.$this->primaryKey.'`, 
                COUNT(*) AS `'.$this->classKey.'_Kount` 
                '.( $xaxis != 'Kount' ? ',`'.$this->classKey.'`.`'.$xaxis.'` AS `'.$this->classKey.'_'.$xaxis.'`' : '' ).'
                '.( $yaxis != 'Kount' ? ',`'.$this->classKey.'`.`'.$yaxis.'` AS `'.$this->classKey.'_'.$yaxis.'` ' : '' )
            ); // http://rtfm.modx.com/display/xPDO20/xPDOQuery.select
 
        }
        /*
        $query->sortby($sort,$dir);
        if ($isLimit) {
            $query->limit($limit,$start);
        }*/
        
        if ( !empty($groupby) ) {
            $query->groupby($groupby);
        }
        $query->prepare();
        // echo 'SQL:'.$query->toSQL().'';
        
        /*
         * data points have to be int/float unless use the category plugin
         * {
            "label": "USA",
            "data": [[1999, 4.4], [2000, 3.7], [2001, 0.8], [2002, 1.6], [2003, 2.5], [2004, 3.6], [2005, 2.9], [2006, 2.8], [2007, 2.0], [2008, 1.1]]
        }, {
            "label": "USA2",
            "data": [[1999, 4.4], [2000, 3.7], [2001, 0.8], [2002, 1.6], [2003, 2.5], [2004, 3.6], [2005, 2.9], [2006, 2.8], [2007, 2.0], [2008, 1.1]]
        }
         * OR
          [ [[1999, 4.4], [2000, 3.7], [2001, 0.8], [2002, 1.6], [2003, 2.5], [2004, 3.6], [2005, 2.9], [2006, 2.8], [2007, 2.0], [2008, 1.1]] ],
          [ [[1999, 4.4], [2000, 3.7], [2001, 0.8], [2002, 1.6], [2003, 2.5], [2004, 3.6], [2005, 2.9], [2006, 2.8], [2007, 2.0], [2008, 1.1]] ]
         **/
        
        $dataStr = NULL;
        
        if ($query->prepare() && $query->stmt->execute()) {
            $x = $this->classKey.'_'.$xaxis;
            $y = $this->classKey.'_'.$yaxis;
            
            while ($item = $query->stmt->fetch(PDO::FETCH_OBJ)) {
                if ( $series ) {
                    if ( !empty($dataStr) ) {
                        $dataStr .= ',';
                    }
                    // print_r($item);
                    $dataStr .= '{
                        "label": "'.$item->$x.'",
                        "data": [ ['.( $xQuote ? '"'.$item->$x.'"' : $item->$x ).', '.( $yQuote ? '"'.$item->$y.'"' : $item->$y ).'] ]
                    }';
                } else {
                    if ( !empty($dataStr) ) {
                        $dataStr .= ',';
                    }
                    // print_r($item);
                    $dataStr .= '['.( $xQuote ? '"'.$item->$x.'"' : $item->$x ).', '.( $yQuote ? '"'.$item->$y.'"' : $item->$y ).']';
                }
            }
        }
        if ( $series ) {
            return $dataStr;
        } else {
            return '{
                "label": "'.$label.'",
                "data": [ '.$dataStr.' ]
            }';
        }
    }
}
