<?php

/**
 * Create a list of Groups for the subscribe/manage
 * 
 * &toArray - list of availabe properties and placeholders?
 * &tpl
 * &css - comma separated list of css files
 * &js - comma separated list of js files
 * &toPlaceholder
 * 
 * (&explainSnippet=true, currentValues) list all avaiable snippet parameters and placeholders in HTML table like this: (category |) name | description | default value
 */



if (!isset($modx->RadUi)) {
    //$modx->addPackage('groupeletters', $modx->getOption('core_path').'components/groupeletters/model/');
    $modx->RadUi = $modx->getService('radui', 'RadUi', $modx->getOption('core_path').'components/radui/model/radui/');
}
$RadUi =& $modx->RadUi;
 
//$groups = $modx->getCollection('EletterGroups', array('allow_signup' => 'Y' ) );
$output = '';

// 
$css = $modx->getOption('css', $scriptProperties, '');
if ( !empty($css) ) {
    $cssFiles = explode(',', $css);
    foreach ( $cssFiles as $css ) {
        $modx->regClientCSS($css);
    }
}
$js = $modx->getOption('js', $scriptProperties, '');
if ( !empty($js) ) {
    $cssFiles = explode(',', $css);
    foreach ( $cssFiles as $css ) {
        $modx->regClientCSS($css);
    }
}

$RadUi->loadGrid($scriptProperties);

$output = $RadUi->buildGrid();

$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, '' );
if ( !empty($toPlaceholder) ) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;