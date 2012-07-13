<?php


if (!isset($modx->RadUi)) {
    //$modx->addPackage('groupeletters', $modx->getOption('core_path').'components/groupeletters/model/');
    $modx->RadUi = $modx->getService('radui', 'RadUi', $modx->getOption('core_path').'components/radui/model/radui/');
}
$RadUi =& $modx->RadUi;
 
//$groups = $modx->getCollection('EletterGroups', array('allow_signup' => 'Y' ) );
$output = '';


$Crud = $RadUi->loadCrud($scriptProperties);
$action = $modx->getOption('action', $_REQUEST, 'getList');

switch( $action ) {
    case 'chart':
        $output = $Crud->makeChartData();
        break;
    case 'add':
    case 'create':
    case 'newObject':
        $output = $Crud->create();
        break;
    case 'saveCell':
        // set the data to be saved correctly:
        if ( isset($_POST['field']) && isset($_POST['value']) && !isset($_POST[$_POST['field']]) ) {
            $_POST[$_POST['field']] = $_POST['value'];
        }
    case 'save':
    case 'update':
        $output = $Crud->update();
        break;
    case 'delete':
    case 'remove':
        $output = $Crud->delete();
        break;
    case 'import':
        $output = $Crud->import();
        break;
    case 'getCollection':
    case 'view':
    case 'getList':
    case 'read':
    case 'review':
    default:
        $output = $Crud->getList();
}

return $output;