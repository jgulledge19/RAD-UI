<?php
/**
 * 
 * This Snippet will build the page links for a RAD Form 
 *
 * [[!RadUiFormPages?
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

// load the form:
if ( !$RadUi->loadForm($scriptProperties) ){
    return 'Form not found';
}
// get the pages:
$RadUi->currentForm->getMany('');

// iterate through them:
$page_data = array();
        // get pages: array(rank=>id); 
        $page_rank = 1;
        $c = $this->modx->newQuery('RadFormElements', array('type' => 'Page'));
        $c->sortby('rank', 'ASC');
        $pages = $this->modx->getIterator('RadFormElements', $c); // $radForm->getMany('Elements', $c );
        $c->prepare();
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements sql] -'.$c->toSql());
        if ( is_object($pages) ) { 
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] pages2 -'.print_r($pages->toArray(), TRUE));
            foreach ( $pages as $page ) {
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Page: '.$page->get('id').' Rank: '. $page->get('rank') );
                $page_data[$page->get('rank')] = $page->get('id'); 
            }
        }
// set active page:

return $output; 