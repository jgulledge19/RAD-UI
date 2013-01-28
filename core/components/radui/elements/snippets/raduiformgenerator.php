<?php
/**
 * 
 * This Snippet will generate a RAD Form 
 *
 * [[!RadUiFormGenerator?
 *   &formID=`1`
 * -- TODO:
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


return $RadUi->getRadForm($scriptProperties); 