<?php
/**
 * This Snippet (RadUiCharts) will build visual HTML charts
 *  
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
 *   &toPlaceholder=``
 *   &toArray=``
 * ]]
 */

if (!isset($modx->RadUi)) {
    //$modx->addPackage('groupeletters', $modx->getOption('core_path').'components/groupeletters/model/');
    $modx->RadUi = $modx->getService('radui', 'RadUi', $modx->getOption('core_path').'components/radui/model/radui/');
}
$RadUi =& $modx->RadUi;
 
//$groups = $modx->getCollection('EletterGroups', array('allow_signup' => 'Y' ) );
$output = '';


$Charts = $RadUi->loadCharts($scriptProperties);
//$action = $modx->getOption('action', $_REQUEST, 'getList');
$output = $RadUi->buildCharts();

$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, '' );
if ( !empty($toPlaceholder) ) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;