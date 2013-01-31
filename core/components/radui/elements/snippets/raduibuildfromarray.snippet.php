<?php

/**
 * Build/Update from array
 * 
 * 
 */
$form_id = 1;

 
// get all existing form elements:

// put the form elements into an array


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
        
// $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] Branch: '.$branch_id);
if ( $branch_id == 0 ) {
    $dbElements = $paths->getTree(array('form_id' => $radForm->get('id') ) );
} else {
    $dbElements = $paths->getBranch($branch_id, array('form_id' => $radForm->get('id') ) );
    
}

$output = '';
$x = 0;
$id_to_element = array();
$element_to_id = array();

// load elements
while ( $element = $dbElements->fetch(PDO::FETCH_ASSOC) ) {
    $element['html_id'];
    $element['id'];
    $id_to_element[$element['id']] = $element['html_id'];
    $element_to_id[$element['html_id']] = $element['id'];
}

// now loop through the build array and save to db
//require_once $modx->RadUi->getConfig('snippetsPath').'bc/admissionformUpdate.php';
//require_once $modx->RadUi->getConfig('snippetsPath').'bc/admissionform.php';
//require_once $modx->RadUi->getConfig('snippetsPath').'bc/admissionform2.php';
require_once $modx->RadUi->getConfig('snippetsPath').'bc/admissionform4.php';

// dbE
if ( is_array($elements) ) {
    foreach ( $elements as $eID => $data ) {
        // existing object:
        if ( isset($data['id']) && $data['id'] > 0 ) {
            $element = $modx->getObject('RadFormElements', (int) $data['id']);
            $data['update_time'] = date('Y-m-d H:g:i');
        } else {
            $element = $modx->newObject('RadFormElements');
            $data['create_time'] = date('Y-m-d H:g:i');
        }
        $data['form_id'] = $form_id;
        if ( isset($data['config']['parent']) && $element_to_id[$data['config']['parent']] ) {
            $data['parent_id'] = $element_to_id[$data['config']['parent']];
        }
        if ( isset($data['config']['group_element']) && $element_to_id[$data['config']['group_element']] ) {
            $data['group_element_id'] = $element_to_id[$data['config']['group_element']];
        }
        $data['html_id'] = $eID;
        $data['config'] = json_encode($data['config']);
        if (!isset($data['validation_rules']) ) {
            $data['validation_rules'] = array();
        }
        $data['validation_rules'] = json_encode($data['validation_rules']);
        $element->fromArray($data);
        if ( !$element->save() ) {
            $modx->log(modX::LOG_LEVEL_ERROR,'[RadUiBuildFromArray] Could not save the element: '.$eID.' data: '.print_r($data, true) );
        }
        $id_to_element[$element->get('id')] = $element->get('html_id');
        $element_to_id[$element->get('html_id')] = $element->get('id');
    }
}

// build the tree/paths:
$paths->buildTree(array('form_id' => $radForm->get('id') ) );

return 'Saved the elements';

  