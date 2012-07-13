<?php

/**
 * Tabs - create HTML tabs, default is jQueryTools Tabs with full screen option
 * 
 */
class Tabs {
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
     * @param $modx
     * @param $config - array() of config options like array('corePath'=>'Path.../core/)
     */
    function __construct(modX &$modx, $scriptProperties) {
        $this->modx =& $modx;
        $this->scriptProperties = $scriptProperties;
	    
	}
    
    /**
     * [[tabs?
     *   &tab1=`Tab Name`
     *   &tab1Content=``
     *   &tabLimit=`10`
     *   &options=`{tab}` ??
     *   &showEmpty=`false`
     *   &toPlaceholer=``
     *   &toArray=``
     * ]]
     */
    public function build() {
        $containerID = $this->modx->getOption('containerID', $this->scriptProperties, 'tabContainer');
        
        $tabLimit = $this->modx->getOption('tabLimit', $this->scriptProperties, 10);
        $tabContainerChunk = $this->modx->getOption('tplTabContainer', $this->scriptProperties, 'RadUiTabContainer');
        $tabNameChunk = $this->modx->getOption('tplTabName', $this->scriptProperties, 'RadUiTabName');
        $tabContentChunk = $this->modx->getOption('tplTabContent', $this->scriptProperties, 'RadUiTabContent');
        
        $tabHeadings = $tabBody = NULL;
        
        for( $x=1; $x<=$tabLimit; $x++) {
            $tabName = $this->modx->getOption('tab'.$x, $this->scriptProperties, NULL);
            if ( !empty($tabName) && $this->canView() ) {
                // get the tabName Chunk:
                $placeholders = array(
                    'tabName' => $tabName,
                    'attr' => '' // html element attributes?
                );
                $tabHeadings .= $this->modx->getChunk($tabNameChunk, $placeholders);
                
                // get the tabContent Chunk:
                $tabContent = $this->modx->getOption('tab'.$x.'Content', $this->scriptProperties, NULL);
                $placeholders = array(
                    'tabContent' => $tabContent,
                    'attr' => '' // html element attributes?
                );
                $tabBody .= $this->modx->getChunk($tabContentChunk, $placeholders);
                
            }
        }
        // tab container:
        $placeholders = array(
            'containerID' => $containerID,
            'tabHeadings' => $tabHeadings,
            'tabBody' => $tabBody,
            'attr' => '' // html element attributes?
        );
        $output = $this->modx->getChunk($tabContainerChunk, $placeholders);
        
        if ( !empty($output) ) {
            // tab container:
            $placeholders = array(
                'loadJQuery' => $this->modx->getOption('loadJQuery', $this->scriptProperties, 'true'),
                'history' => $this->modx->getOption('history', $this->scriptProperties, 'false'),
                'containerID' => $containerID,
                'attr' => '' // html element attributes?
            );
            // &toArray - list of availabe properties and placeholders?
            //$cssChunk = $this->modx->getOption('tplCSS', $this->scriptProperties, 'RadUiCss');
            $jsChunk = $this->modx->getOption('tplJS', $this->scriptProperties, 'RadUiTabJs');
            //$jsChunk = $this->modx->getOption('js', $scriptProperties, 'SlickGrid_JS');
            $this->modx->regClientStartupHTMLBlock(
                $this->modx->getChunk($jsChunk, $placeholders)
            );
        }
        
        return $output;
        
        
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
     * set custom defaults for fields
     * @param (object) $item
     * @return (Object) $item
     */
    protected function setCreateDefaults($item) {
        // @TODO make default?
        return $item;
    }
    
}
