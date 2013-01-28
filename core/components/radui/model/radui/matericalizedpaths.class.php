<?php

/**
 * 
 * Manage Materialized Paths: http://en.wikipedia.org/wiki/Materialized_path
 *  - https://communities.bmc.com/communities/docs/DOC-9902
 *  - http://www.cloudconnected.fr/2009/05/26/trees-in-sql-an-approach-based-on-materialized-paths-and-normalization-for-mysql/
 * 
 * DB table needs columns:
 *  - parent_id INT - this will still allow for the Adjacency List
 *  - sequence INT - how the children will be ordered
 *  - depth INT - at what depth/level is the current node at
 *  - path VARCHAR or TEXT - ex: topparent/parent/current or in ids: 1/2/3
 * 
 */
class MaterializedPaths {
	
	/**
     * the form data config
     *  array( 
     *     parent => parent_id,
     *     rank => rank
     *     depth => depth
     *     path => path
     *     path_column => id
     *     
     *     )
     */
    protected $config = array();
    
    /**
     * the processed branch data
     * @param (array) $branch_data
     */
    protected $branch_data = array();
    
    /**
     * 
     */
    public $debug = false;
    
    /**
     * the modx object
     */
    public $modx;
    
    /**
     * @param $modx
     * @param (array) $config - the for
     */
    function __construct(modX &$modx,array $config = array() ) {
        $this->modx =& $modx;
        $this->config = array(
                'object' => 'RadFormElements',
                'primary' => 'id',
                'parent' => 'parent_id',
                'rank' => 'rank',
                'depth' => 'depth',
                'path' => 'path',
                'path_column' => 'rank', // 001.001.001 or 002.001.002 
                'path_type' => 'INT',
                'order_by' => 'path',
                'separator' => '.',
            );
        $this->config = array_merge($this->config, $config);
    }
    /**
     * Methods should only get IDs and Paths, (re)build tree and get node(branch)
     */
    
    
    /**
     * Will get the entire tree and all child nodes
     * 
     * @param (Array) $cretria - xPDO criteria
     * @param (INT) $depth - how deep to go, default is 0 meaning infinite
     * @param (String) $return_type - (PDO) fetch, (PDO) query, (xPDO) getCollection, (xPDO) getIterator
     * @return (Array) $data | (Object) $data
     */
    public function getTree($criteria=array(), $depth=0, $return_type='query') {
        return $this->getBranch(0, $criteria, $depth, $return_type);
    }
    
    /**
     * Will get all children of current node
     * 
     * @param (INT) $id - the $id of the object to get its children or branch
     * @param (Array) $cretria - xPDO criteria
     * @param (INT) $depth - how deep to go, default is 0 meaning infinite
     * @param (String) $return_type - (PDO) fetch, (PDO) query, (xPDO) getCollection, (xPDO) getIterator
     * @return (Array) $data | (Object) $data
     */
    public function getBranch($id, $criteria, $depth=0, $return_type='query') {
        /**
         * SELECT * FROM modx_rad_form_elements 
            WHERE
                path LIKE 'PARENT_PATH%' AND
                depth > PARENT_DEPTH
            ORDER BY path ASC
         * 
         * In onequery:
         * SELECT * FROM modx_rad_form_elements e
            WHERE
                id IN(
                    SELECT id FROM modx_rad_form_elements 
                    WHERE
                        path LIKE CONCAT(e.path,'%') AND
                        depth > e.depth
                    ORDER BY path ASC
                )
            ORDER BY path ASC
         */
        // @TODO: build sql with xPDO::criteria
        $form_id = $criteria['form_id'];
        if ( isset($criteria['getBranchAnwers']) ) {
            $sql = 'SELECT `a`.*,  `e`.`name`, `e`.`html_id` FROM modx_rad_form_elements AS e 
            JOIN modx_rad_form_answers AS a ON a.element_id = e.id
            WHERE 
                a.instance_id = '.( (int) $criteria['instance_id']).' AND
                e.group_element_id = 0 AND 
                ';
        } else {
            $sql = 'SELECT * FROM modx_rad_form_elements 
            WHERE 
                ';
        }
        if ( $id == 0 ) {
            $sql .= '
                    form_id = '.( (int) $form_id).'
                     ';
            if ( $depth > 0 ) {
                $sql .= 'AND
                    depth <= '.( (int) $depth).' ';
            }
        } else {
            $sql .= '
                    form_id = '.( (int) $form_id).' AND
                    path LIKE CONCAT((SELECT path FROM modx_rad_form_elements e WHERE e.id = '.( (int) $id).' AND e.form_id = '.( (int) $form_id).'),\'%\') AND
                    depth > (SELECT e2.depth FROM modx_rad_form_elements e2 WHERE e2.id = '.( (int) $id).' AND e2.form_id = '.( (int) $form_id).') ';
            if ( $depth > 0 ) {
                $sql .= 'AND
                    depth <= (SELECT e3.depth + '.( (int) $depth).' FROM modx_rad_form_elements e3 WHERE e3.id = '.( (int) $id).' AND e3.form_id = '.( (int) $form_id).') ';
            }
        }
        $sql .= '
            ORDER BY path ASC';
        
        // $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->MaterializedPaths] SQL: '.$sql);
        switch ($return_type) {
            case 'getCollection':
                $data = $this->modx->getCollection($this->config['object'], $criteria);
                break;
            case 'getIterator':
                $data = $this->modx->getIterator($this->config['object'], $criteria);
                break;
            case 'sql':
                $sql;
                break;
            case 'fetch':
                $stmt = $this->modx->query($sql);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                break;
            case 'query':
                // no break
            default:
                $data = $this->modx->query($sql);
                break;
        }
        return $data;
    }
    
    /**
     * Move branch and all child nodes
     */
    public function moveBranch($id, $new_parent, $depth=0)
    {
        // 1. update single record parent & path
        
        // 2. update remaining branches in 1 query: - need old path and replace with new
    }
    
    /**
     * Will get all children of current node
     * 
     * @param (INT) $id - the $id of the object to get its children or branch
     * @param (Array) $cretria - xPDO criteria
     * @param (INT) $depth - how deep to go, default is 0 meaning infinite
     * @param (String) $return_type - (PDO) fetch, (PDO) query, (xPDO) getCollection, (xPDO) getIterator
     * @return (Array) $data | (Object) $data
     */
    public function getBranchAnswers($id, $criteria, $depth=0, $return_type='query') {
        /**
         * SELECT a.*  FROM modx_rad_form_elements AS e 
            JOIN modx_rad_form_answers AS a
            WHERE
                path LIKE CONCAT((SELECT e.id FROM modx_rad_form_elements e WHERE e.id = 1),'%') AND
                depth > (SELECT e2.depth FROM modx_rad_form_elements e2 WHERE e2.id = 1) AND
                depth <= (SELECT e2.depth + 5 FROM modx_rad_form_elements e2 WHERE e2.id = 1) 
            ORDER BY path ASC, a.rank ASC
         * 
         */
        $criteria['getBranchAnwers'] = TRUE;
        if (!isset($criteria['instance_id'])) {
            $criteria['instance_id'] = 0;
        }
        return $this->getBranch($id, $criteria, $depth, $return_type);
    }
    
    /**
     * 
     */
    
    /**
     * Will get the entire tree and all child nodes
     * 
     * @param (Array) $cretria - xPDO criteria
     * @param (INT) $depth - how deep to go, default is 0 meaning infinite
     * @param (String) $parent_path
     * @return (Array) $data | (Object) $data
     */
    public function buildTree($criteria=array(), $depth=0, $parent_path=NULL)
    {
        $this->buildBranch(0, $criteria, 0);
    }
    /**
     * 
     */
    public function buildBranch($parent_id, $criteria=array(), $parent_depth=0, $parent_path=NULL)
    {
        
        $form_id = $criteria['form_id'];
        // get all children:
        $c = $this->modx->newQuery('RadFormElements', array('form_id' => $form_id, 'parent_id' => $parent_id));
        $c->sortby('rank', 'ASC');
        $elements = $this->modx->getIterator('RadFormElements', $c); // $radForm->getMany('Elements', $c );
        $c->prepare();
        if ( $this->debug ) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->MaterializedPaths->buildBranch] SQL: '.$c->toSql());
        }
        if ( is_object($elements) ) {
            if ( $this->debug ){ 
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->MaterializedPaths->buildBranch] Object loaded start loop for: '.$parent_id );
            }
            $rank = 1;// always start with 1
            foreach ( $elements as $element ) {
                // build path:
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->MaterializedPaths] E ID: '.$element->get('id') );
                $my_depth = $parent_depth + 1;
                $my_path = '';
                if ( !empty($parent_path) ) {
                    $my_path = $parent_path.'.';
                }
                $my_path .= str_pad($rank, 3, "0", STR_PAD_LEFT);// makes 001
                $element->set('rank', $rank++);
                $element->set('path', $my_path);
                $element->set('depth', $my_depth);
                $element->save();
                $this->buildBranch($element->get('id'), array('form_id' => $form_id), $my_depth, $my_path);
            }
        }
        
    }
    
    /**
     * Get vine - or all parent nodes to root
     * @param (Int) $id - the primary id of the node to get the vine for
     * @return 
     */
    public function getVine($id)
    {
        
    }
    
    
    /**
     * 
     */
    public function getPath($id)
    {
        
    }
    /**
     * 
     */
    public function getParentPath(){
        
    }
    
    /**
     * 
     */
    public function buildPath($id)
    {
        
    }
    
    
}

