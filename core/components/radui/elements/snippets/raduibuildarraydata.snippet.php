<?php

/**
 * Build the array data
 * The array data can then be updated and ran via the build to update all
 * 
 */
$update = (boolean) $modx->getOption('Update', $scriptProperties, true);
// $update = false;
 
if (!isset($modx->RadUi)) {
    //$modx->addPackage('groupeletters', $modx->getOption('core_path').'components/groupeletters/model/');
    $modx->RadUi = $modx->getService('radui', 'RadUi', $modx->getOption('core_path').'components/radui/model/radui/');
}
// $RadUi =& $modx->RadUi;
 
$form_id = 1;
$branch_id = 0;
 
$form_id = $modx->getOption('formID', $scriptProperties, 1);
$criteria = $form_id;
if ( $form_id == 0 ){
    $form_name = $this->modx->getOption('formName', $scriptProperties, '');
    $criteria = array('name' => $form_name);
}
// $modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->getRadForm] Build Criteria ' );
// get form db object
$radForm = $modx->getObject('RadForm', $criteria );
// $radForm->get('id');
if ( !is_object($radForm) ) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->getRadForm] Could not find the form '.print_r($criteria,TRUE) );
    return 'The form was not found';
}

// get all existing form elements:
$elements = '';
// put the form elements into an array

require_once $modx->RadUi->getConfig('raduiPath').'matericalizedpaths.class.php';
$paths = new MaterializedPaths($modx, $config=array() );

//$paths->buildTree(array('form_id' => $item->get('form_id') ) );
        
// $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] Branch: '.$branch_id);
if ( $branch_id == 0 ) {
    $elements = $paths->getTree(array('form_id' => $radForm->get('id') ) );
} else {
    $elements = $paths->getBranch($branch_id, array('form_id' => $radForm->get('id') ) );
    
}

$output = '';
$x = 0;
function arrayString($str) {
    // from JSON string to array:
    if ( is_string($str) ) {
        $array = json_decode($str);
    } else {
        $array = $str;
    }
    // array(),
    $string = '';
    foreach($array as $n => $v ) {
        if ( is_string($v) ) {
            $string .= '
            '."'".$n."' => '".$v."',";
        } else {
            $string .= '
            '."'".$n."' => array(".myLineSpacing(arrayString($v), 2)." ),";
        }
        
    }
    return $string;
}
function myLineSpacing($string, $depth) {
    $spaces = ''; 
    $tab = '    ';
    for( $x = 1; $x<$depth; $x++ ) {
        $spaces .= $tab;
    }
    
    // \r\n, \n\r, \n and \r
    $padded_string = '';
    $string = str_replace(array("\\r\\n", "\\n\\r", "\\n", "\\r"), "\\r\\n", $string);
    $lines = explode("\r\n", $string);
    foreach( $lines as $line ) {
        // remove empty lines:
        $tmp = trim($line);
        if ( $tmp == '' ) {
            continue;
        }
        $padded_string .= $spaces.$line."\r\n";
        //$padded_string .= '|'.$spaces.'|'.$line."\r\n";
    }
    return $padded_string;
}
$id_to_element = array();

// load elements
while ( $element = $elements->fetch(PDO::FETCH_ASSOC) ) {
    $id_to_element[$element['id']] = $element['html_id'];
    if ( !$update ) {
        $element['config'] = json_decode($element['config'], true);
        if ( isset($element['parent_id']) && isset($id_to_element[$element['parent_id']]) ) {
            $element['config']['parent'] = $id_to_element[$element['parent_id']];
        }
    }
    $tmp = "
'".$element['html_id']."' => array(
    'form_id' => '".$element['form_id']."', ";
    if ( $update ) {
        $tmp .= "
    'id' => ".$element['id'].",
    'parent_id' => ".$element['parent_id'].",
    'depth' => ".$element['depth'].",
    'path' => '".$element['path']."', ";
    }
    $tmp .= "
    'rank' => ".$element['rank'].",
    'type' => '".$element['type']."',
    'text' => '".$element['text']."',
    'description' => '".$element['description']."',
    'name' => '".$element['name']."',
    'group_element_id' => '".$element['group_element_id']."',";
    if ( $update ) {
        $tmp .= "
    'html_id' => '".$element['html_id']."',";
    }
    $tmp .= "
    'default_value' => '".$element['default_value']."',
    'config' => array(".arrayString($element['config'])."),
    'classkey' => '".$element['classkey']."',
    'table' => '".$element['table']."',
    'table_field' => '".$element['table_field']."',
    'validation_rules' => array(".arrayString($element['validation_rules'])."),";
    if ( $update ) {
        $tmp .= "
    'active' => '".$element['active']."'";
    }
    $tmp .= "
),";
    $output .= myLineSpacing($tmp, $element['depth']);
}
return '<textarea style="width: 600px; height: 700px;">'.$output.'</textarea>'; 




