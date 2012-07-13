<?php
/**
 * This Snippet (RadUiTabs) will build out as many tabs as one would like
 *  
 * [[tabs?
 *   &tab1=`Tab Name`
 *   &tab1Content=``
 *   &tabLimit=`10`
 * -- TODO:
 *   &options=`{tab}` ??
 *   &showEmpty=`false`
 *   &toPlaceholer=``
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


$Tabs = $RadUi->loadTabs($scriptProperties);
//$action = $modx->getOption('action', $_REQUEST, 'getList');
$output = $RadUi->buildTabs();

return $output;
