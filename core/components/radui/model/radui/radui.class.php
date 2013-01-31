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
     * 
     */
    protected $config = array();
    
    /**
     * Current easyForm DB object 
     */
    public $currentForm = null;
    
    
    /**
     * @param (Boolean) $hasBuiltChart
     */
    protected $hasBuiltChart = FALSE;
    
    
    /**
     * Constructs the Eletters object
     *
     * @param modX &$modx A reference to the modX object
     * @param array $config An array of configuration options
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $basePath = $this->modx->getOption('radui.core_path',$config,$this->modx->getOption('core_path').'components/radui/');
        $assetsUrl = $this->modx->getOption('radui.assets_url',$config,$this->modx->getOption('assets_url').'components/radui/');

        $this->config = array_merge(array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'raduiPath' => $basePath.'model/radui/',
            'processorsPath' => $basePath.'processors/',
            'templatesPath' => $basePath.'templates/',
            'snippetsPath' => $basePath.'elements/snippets/',
            'chunksPath' => $basePath.'elements/chunks/',
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php',
            'debug' => $this->modx->getOption('radui.debug',NULL, 0)
        ),$config);

        $this->modx->addPackage('radui',$this->config['modelPath']);

        $this->modx->lexicon->load('radui:default');
    }
    
    /**
     * Initializes the class into the proper context
     *
     * @access public
     * @param string $ctx
     */
    public function initialize($ctx = 'web') {
        switch ($ctx) {
            case 'mgr':
                //require_once $this->config['modelPath'].'radui/request/raduicontrollerrequest.class.php';
                if (!$this->modx->loadClass('RaduiControllerRequest',$this->config['modelPath'].'radui/request/',true,true)) {
                    return 'Could not load controller request handler: '.__FILE__;
                    // ElettersControllerRequest
                }
                $this->request = new RaduiControllerRequest($this);
                return $this->request->handleRequest();
            break;
        }
        return true;
    }
    /**
     * @param (String) NULL or String(Key)- Null returns array, string returns value
     * @return (Mixed) Array/String if key is empty then the config array, if key is set then the value 
     */
    public function getConfig($key=NULL) {
        if ( empty($key) ) {
            return $this->config;
        }
        if ( isset($this->config[$key]) ) {
            return $this->config[$key];
        }
        return NULL;
    }
    
    /**
     * Set the debug value
     * @param (Boolean) $debug
     */
    public function setDebug($debug=TRUE) {
        $this->config['debug'] = (boolean) $debug;
    }
    
    
    
    /**
     * @param (Array) $scriptProperties
     */
    public function loadGrid($scriptProperties) {
        $gridName = $this->modx->getOption('grid', $scriptProperties, 'SlickGrid');
        
        if ($this->modx->loadClass($gridName,$this->config['raduiPath'],true,true)) {
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
        // $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Load '.$crudName.' class in '.$folder.' folder');
        if ($this->modx->loadClass($crudName,$this->config['raduiPath'].$folder,true,true)) {
            $this->crud = new $crudName($this->modx, $scriptProperties);
            return $this->crud;
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$crudName.' class.');
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
        if ($this->modx->loadClass($tabName,$this->config['raduiPath'].$folder,true,true)) {
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
        if ($this->modx->loadClass($chartName,$this->config['corePath'].$folder,true,true)) {
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
        
        if ($this->modx->loadClass($formsName,$this->config['raduiPath'],true,true)) {
            $this->form = new $formsName($this->modx, $scriptProperties, $theme);
            
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI] Could not load the '.$formsName.' class.');
            return false;
        }
        
        return $this->form;
    }
    
    /**
     * Load DB form object 
     * @param (Array) $scriptProperties
     */
    public function loadForm($scriptProperties) {
        $form_id = $this->modx->getOption('formID', $scriptProperties, 0);
        $criteria = $form_id;
        if ( $form_id == 0 ){
            $form_name = $this->modx->getOption('formName', $scriptProperties, '');
            $criteria = array('name' => $form_name);
        }
        // get form db object
        $get_form = true;
        if ( is_object($this->currentForm) ) {
            if ( $this->currentForm->get('id') == $form_id || $this->currentForm->get('name') == $form_name ) {
                //$radForm = &$this->currentForm;
                $get_form = false;
            } 
        }
        if ( $get_form ){
            $this->currentForm = $this->modx->getObject('RadForm', $criteria );
        }
        if ( !is_object($this->currentForm) ) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->loadForm] Could not find the form '.print_r($criteria,TRUE) );
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @param (Array) $scriptProperties
     * @return (String) the generated form
     */
    public function getRadForm($scriptProperties) {
        // $radForm->get('id');
        if ( !$this->loadForm($scriptProperties) ) {
            return 'The form was not found';
        }
        
        // load the form engine
        $formEngine = $this->currentForm->get('classkey');
        if ( $this->modx->loadClass($formEngine, $this->config['raduiPath'], true, true) ) {
            $options = json_decode($this->currentForm->get('options'), TRUE);
            $this->form = new $formEngine($this->modx, $scriptProperties, $this->currentForm->get('theme'));
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->getRadForm] Could not load the '.$formEngine.' class. Path: '.$this->config['modelPath']);
            return 'The form engine '.$formEngine.' was not found';
        }
        $output = $this->form->loadEngine($this->currentForm, $scriptProperties);
        
        // load pages to placeholder:
        $this->form->loadPages($this->currentForm->get('id'), $scriptProperties, true );
        return $output;
    }
}
