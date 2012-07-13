<?php

/**
 * Charts - create HTML5 Charts, default is jQueryTools Tabs with full screen option
 * Default: https://github.com/flot/flot 
 * 
 */
class Charts {
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
     * @param (Boolean) $hasBuiltChart
     */
    protected $hasBuiltChart = FALSE;
    
    /**
     * @param $modx
     * @param $config - array() of config options like array('corePath'=>'Path.../core/)
     */
    function __construct(modX &$modx, $scriptProperties) {
        $this->modx =& $modx;
        $this->scriptProperties = $scriptProperties;
        
    }
    
    /**
     * [[RadUiCharts?
     *   &jsonUrl=`URL`
     *   &data=`[if not AJAX]`
     *   &options=`{json object}`, 
     *   &onLoadData=`false`
     * 
     *   &tplChart=`ChunkName`
     *   &tplHead=``
     *   &tplJs=``
     *   
     *   &toPlaceholer=``
     *   &toArray=``
     * ]]
     */
    public function build() {
        $containerID = $this->modx->getOption('containerID', $this->scriptProperties, 'chartContainer');
        
        $options = $this->modx->getOption('options', $this->scriptProperties, '{
        lines: { show: true },
        points: { show: true },
        xaxis: { tickDecimals: 0, tickSize: 1 }
    }');
        $data = $this->modx->getOption('data', $this->scriptProperties, '[]' );
        
        // use json data url here:
        $jsonUrl = $this->modx->getOption('jsonUrl', $this->scriptProperties, NULL );
        $onLoadData = $this->modx->getOption('onLoadData', $this->scriptProperties, 'false' );
        $xAxis = $this->modx->getOption('xAxis', $this->scriptProperties, 'default' );
        $yAxis = $this->modx->getOption('yAxis', $this->scriptProperties, 'default' );
        $label = $this->modx->getOption('label', $this->scriptProperties, 'Data Label' );
        $groupby = $this->modx->getOption('groupby', $this->scriptProperties, '' );
        $series = $this->modx->getOption('series', $this->scriptProperties, 0 );
        
        $chartChunk = $this->modx->getOption('tplChart', $this->scriptProperties, 'RadUiChart');
        
        // if ( $this->canView() ) { }
               
        // tab container:
        $placeholders = array(
            'containerID' => $containerID,
            'jsonUrl' => $jsonUrl,
            'options' => $options,
            'data' => $data, // html element attributes?
            'onLoadData' => $onLoadData,
            'xAxis' => $xAxis,
            'yAxis' => $yAxis,
            'label' => $label,
            'label' => $label,
            'groupby' => $groupby
        );
        $output = $this->modx->getChunk($chartChunk, $placeholders);
        // @TODO: &toArray - list of availabe properties and placeholders? 
        
        // tab container:
        $placeholders = array(
            'loadJQuery' => $this->modx->getOption('loadJQuery', $this->scriptProperties, 'true'),
            'containerID' => $containerID,
            'jsonUrl' => $jsonUrl,
            'options' => $options,
            'data' => $data, // html element attributes?
            'onLoadData' => $onLoadData,
            'xAxis' => $xAxis,
            'yAxis' => $yAxis,
            'label' => $label,
            'series' => $series,
            'groupby' => $groupby
        );
        // this is loading all of the JS files
        $headChunk = $this->modx->getOption('tplHead', $this->scriptProperties, 'RadUiChartHead');
        if ( !$this->hasBuiltChart ) {
            $this->modx->regClientStartupHTMLBlock(
                $this->modx->getChunk($headChunk, $placeholders)
            );
            $this->setBuilt(TRUE);
        }
        // create & load the js config for this instance:
        
        //$cssChunk = $this->modx->getOption('tplCSS', $this->scriptProperties, 'RadUiCss');
        $jsChunk = $this->modx->getOption('tplJS', $this->scriptProperties, 'RadUiChartHeadInstance');
        //$jsChunk = $this->modx->getOption('js', $scriptProperties, 'SlickGrid_JS');
        
        $this->modx->regClientStartupHTMLBlock(
            $this->modx->getChunk($jsChunk, $placeholders)
        );
        
        return $output;
        
        
    }
    /**
     * @param (Boolean) $bool
     */
    public function setBuilt($bool) {
        $this->hasBuiltChart = $bool;
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
