<?php
require_once MODX_CORE_PATH.'components/radui/model/radui/crud.class.php'; 

/**
 * Crud - C.R.U.D - Create, Read, Update and Delete - Single Table
 * 
 */
class RadFormElementsCrud extends Crud {
    
    
    /**
     * Filter input data - this is not validataion.  Example you allow the user to set a column with readable values
     *      but these need to corrispond to a table ID.  Filter that here
     * @return (Object) $item
     */
    protected function filterCreate($item) {
        // filter $this->inputData
        $this->inputData['create_time'] = date('Y-m-d H:g:i');
        // $this->inputData
        $this->postToJson($this->inputData);
        return $item;
    }
    /**
     * Filter input data - this is not validataion.  Example you allow the user to set a column with readable values
     *      but these need to corrispond to a table ID.  Filter that here
     * @return VOID
     */
    protected function filterUpdate($item) {
        // filter the $this->inputData
        $this->inputData['update_time'] = date('Y-m-d H:g:i');
        if ( isset($this->inputData['create_time']) ) {
            unset($this->inputData['create_time']);
        }
        $this->postToJson($this->inputData);
        return $item;
    }
    
    /**
     * 
     */
    protected function postToJson(array &$array) {
        $config = array();
        $validate = array();
        $config_str = '';
        $v_str = '';
        foreach ( $array as $name => $value ) {
            if ( strpos($name, 'config_') === 0 ) {
                //$config_str .= "'".str_replace('config_', '', $name)."' => '', "."\n\r";
                if ( empty($value) && $value !== 0 ) {
                    continue;
                }
                $config[str_replace('config_', '', $name)] = $value;
                continue;
            }
            if ( strpos($name, 'validate_') === 0 ) {
                //$v_str .= "'".str_replace('validate_', '', $name)."' => '', "."\n\r";
                if ( empty($value) && $value !== 0 ) {
                    continue;
                }
                $validate[str_replace('validate_', '', $name)] = $value;
                continue;
            }
        }
        //$this->modx->log(modX::LOG_LEVEL_ERROR,' CONFIG ARRAY: '. $config_str );
        //$this->modx->log(modX::LOG_LEVEL_ERROR, ' VALIDATE ARRAY: '. $v_str);
        $array['config'] = json_encode($config);
        $array['validation_rules'] = json_encode($validate);
        return $array;
    }
    
    /**
     * filter the item array output - getList
     * @param (Array) $item
     * @return (Array) $item
     */
    protected function filterList($item) {
        /**
         * Put the JSON into name => value 
         */
        $config = json_decode($item['config'], TRUE);
        foreach ( $config as $n => $v) {
            $item['config_'.$n] = $v;
        }
        $validate = json_decode($item['validation_rules'], TRUE);
        foreach ( $validate as $n => $v) {
            $item['validate_'.$n] = $v;
        }
        return $item;
    }
    /**
     * set a custom search option, can set these basied on user/group
     * @param (object) $query
     * @return (Object) $query
     * /
    protected function searchList($query) {
        // default search
        // search fields: (must be prefixed with search_ )
        $search = array();
        foreach ( $_REQUEST as $name => $value ) {
            if ( strpos($name,'search_') === 0 && !empty($value) ) {
                // 'start_date:LIKE' => $start_where.'%'
                $name = str_replace('search_', '', $name);
                switch ($name) {
                    case 'thursday_class':
                    case 'friday_class':
                    case 'friday_class_2':
                        $search[$name] = $value;
                        break;
                    default:
                        $search[$name.':LIKE'] = $value.'%';
                        break;
                }
                
                //echo 'SEARCH: '.$name;
            }
        }
        if ( count($search) > 0 ) {
            // @TODO verify that they are valid column fields - see: xPDOManager::getColumnDef()
            $query->where($search);
        }
         
        return $query;
    }*/
    /**
     * set custom defaults for fields
     * @param (object) $item
     * @return (Object) $item
     */
    protected function setCreateDefaults($item) {
        
        $item->set('form_id', 1);
        
        return $item;
    }
    
    /**
     * set custom defaults for fields
     * @param (object) $item
     * @return (Object) $item
     */
    protected function setUpdateDefaults($item) {
        
        return $item;
    }
    
    /**
     * Create Dependants, children, parents, ect..  
     * @Override this method is meant to be overridden 
     * @param (object) $item
     * @return Boolean
     */
    public function createDependants($item) {
        
        require_once $this->modx->radui->getConfig('raduiPath').'matericalizedpaths.class.php';
        $paths = new MaterializedPaths($this->modx, $config=array() );
        $paths->buildTree(array('form_id' => $item->get('form_id') ) );
        
        return TRUE;
    }
    /**
     * Update Dependants, children, parents, ect..  
     * @Override this method is meant to be overridden 
     * @param (object) $item
     * @return Boolean
     */
    public function updateDependants($item) {
        require_once $this->modx->radui->getConfig('raduiPath').'matericalizedpaths.class.php';
        $paths = new MaterializedPaths($this->modx, $config=array() );
        $paths->buildTree(array('form_id' => $item->get('form_id') ) );
        
        return TRUE;
    }
}
