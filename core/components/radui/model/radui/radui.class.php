<?php
/**
 * RADUI
 * Radui is 
 */
class RadUi {
    
    /**
     * @param modx $modx 
     * 
     */
    public $modx;
    /**
     * @param (Object) $grid
     */
    public $grid;
    /**
     * @param (Object) $form - form EasyForms
     */
    public $form;
    /**
     * @param (Object) $tabs
     */
    public $tabs;
    /**
     * @param (Object) $chart
     */
    public $chart;
    
    /**
     * @param (Boolean) $hasBuiltChart
     */
    protected $hasBuiltChart = FALSE;
    
    
    /**
     * @param $modx
     * //@ param $config - array() of config options like array('corePath'=>'Path.../core/)
     */
	function __construct(modX &$modx) {
        $this->modx =& $modx;
        
	}
    
    /**
     * @param (Array) $scriptProperties
     */
    public function loadGrid($scriptProperties) {
        $gridName = $this->modx->getOption('grid', $scriptProperties, 'SlickGrid');
        
        if ($this->modx->loadClass($gridName,MODX_CORE_PATH.'/components/radui/model/radui/',true,true)) {
            $this->grid = new $gridName($this->modx, $scriptProperties);
            
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$gridName.' class.');
        }
    }
    
    /**
     * @param
     * 
     * @return (String) $javaScript for the grid
     */
    public function buildGrid() {
        
        return $this->grid->build();
    }
    
    /**
     * @param (Array) $scriptProperties
     */
    public function loadCrud($scriptProperties) {
        // crud can be: folder=>name or just name
        $crudName = $this->modx->getOption('crud', $scriptProperties, 'Crud');
        $folder = NULL;
        if ( strpos($crudName, '=>') > 0 ) {
            list($folder,$crudName) = explode('=>', $crudName);
        }
        if ( !empty($folder) ) {
            $folder .= '/';
        }
        if ($this->modx->loadClass($crudName,MODX_CORE_PATH.'/components/radui/model/radui/'.$folder,true,true)) {
            $this->crud = new $crudName($this->modx, $scriptProperties);
            return $this->crud;
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$gridName.' class.');
        }
        return false;
    }
    /**
     * Forms? Add, Edit and Delete?  this is part of the Grid but should there be 
     * individual parts allowed/created ?
     */
    
    /**
     * Reports
     */
    public function loadReports() {
        
    }
    /**
     * Build Reports
     */
    public function buildReports() {
        
    }
    /**
     * @param
     * 
     * @return (String) $javaScript for the grid
     */
    public function buildTabs() {
        
        return $this->tabs->build();
    }
    
    /**
     * @param (Array) $scriptProperties
     */
    public function loadTabs($scriptProperties) {
        // crud can be: folder=>name or just name
        $tabName = $this->modx->getOption('tabs', $scriptProperties, 'Tabs');
        $folder = NULL;
        if ( strpos($tabName, '=>') > 0 ) {
            list($folder,$tabName) = explode('=>', $tabName);
        }
        if ( !empty($folder) ) {
            $folder .= '/';
        }
        if ($this->modx->loadClass($tabName,MODX_CORE_PATH.'/components/radui/model/radui/'.$folder,true,true)) {
            $this->tabs = new $tabName($this->modx, $scriptProperties);
            return $this->tabs;
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$tabName.' class.');
        }
        return false;
    }
    
    /**
     * @param
     * 
     * @return (String) $javaScript for the grid
     */
    public function buildCharts() {
        $this->chart->setBuilt($this->hasBuiltChart);
        $output = $this->chart->build();
        $this->hasBuiltChart = TRUE;
        return $output;
    }
    
    /**
     * @param (Array) $scriptProperties
     */
    public function loadCharts($scriptProperties) {
        // crud can be: folder=>name or just name
        $chartName = $this->modx->getOption('charts', $scriptProperties, 'Charts');
        $folder = NULL;
        if ( strpos($chartName, '=>') > 0 ) {
            list($folder,$chartName) = explode('=>', $chartName);
        }
        if ( !empty($folder) ) {
            $folder .= '/';
        }
        if ($this->modx->loadClass($chartName,MODX_CORE_PATH.'/components/radui/model/radui/'.$folder,true,true)) {
            $this->chart = new $chartName($this->modx, $scriptProperties);
            return $this->chart;
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$chartName.' class.');
        }
        return false;
    }
    
    /**
     * @param (Array) $scriptProperties
     * @param (String) $theme
     * @return (Object) $form
     */
    public function newForm($scriptProperties,$theme='radui') {
        $formsName = $this->modx->getOption('forms', $scriptProperties, 'EasyForms');
        
        if ($this->modx->loadClass($formsName,MODX_CORE_PATH.'/components/radui/model/radui/',true,true)) {
            $this->form = new $formsName($this->modx, $scriptProperties, $theme);
            
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$formsName.' class.');
            return false;
        }
        
        return $this->form;
    }
}
