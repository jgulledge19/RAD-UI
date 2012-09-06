<?php
/**
 * 
 * This Snippet will just test EasyForms (RadUiEasyForms) will build visual HTML charts
 *
 */

if (!isset($modx->RadUi)) {
    //$modx->addPackage('groupeletters', $modx->getOption('core_path').'components/groupeletters/model/');
    $modx->RadUi = $modx->getService('radui', 'RadUi', $modx->getOption('core_path').'components/radui/model/radui/');
}
$RadUi =& $modx->RadUi;
 
//$groups = $modx->getCollection('EletterGroups', array('allow_signup' => 'Y' ) );
$output = '';


$form = $RadUi->newForm($scriptProperties);
//$action = $modx->getOption('action', $_REQUEST, 'getList');
// just for my IDE to get the docs, comment out on run:
// $form = new EasyForms();
// load default data:
$form->loadData(array());
/**
 * Create the fields:
 */
$form->setForm('method', 'post');
$form->setForm('action', '[[~[[*id]]]]');
$form->addContainer('ul', 'first_list');
/** type, ID, array of options */
$form->addField('first_name', 'txt_first_name', 
    array(
        // 'chunk' => '' // you can set a custom chunk for you element here otherwise the default theme is used
        // the field element:
        'element' => 'input', // existing types: - input, textarea, select, radio, checkbox, hidden (these have corrasponding Chunks )
        'type' => 'text', // input -> button, text, file, reset, submit,password,
            // checkbox, radio, hidden, image, ect.. (combobox, autosuggest, )
        
        // ,'require' => '', // HTML5 attribute
        
        'value' => '', // a default value
        'parent' => 'first_list', // 0(null) or the parent element ID
            //'location' => childElement(default), startElement, endElement, or midElement
        'class' => '', // user input
        'attr' => '', // user input str of attribute="value"
        'title' => '',
        
        // the label 
        'label' => 'First Name', // $formName (name) by default
        'labelClass' => '',
        'labelAttr' => '',
        // the default Chunks have the field and label in a container, <li> 
        'containterID' => '',
        'containerClass' => 'medium spaceRight',
        'containerAttr' => '',
        // you can also add any custom properties here if you create a custom Chunk
    )
);
$form->addField('last_name', 'txt_last_name', 
    array(
        'element' => 'input', // existing types: - input, textarea, select, radio, checkbox, hidden (these have corrasponding Chunks )
        'type' => 'text', // input -> button, text, file, reset, submit,password,
            // checkbox, radio, hidden, image, ect.. (combobox, autosuggest, )
        'parent' => 'first_list', // 0(null) or the parent element ID
        'title' => '',
        'label' => 'Last Name', // $formName (name) by default
        'containerClass' => 'medium spaceRight',
    )
);
// a Select box example:
$form->addField('fav_food', 'sel_fav_food', 
    array(
        'element' => 'select', // existing types: - input, textarea, select, radio, checkbox, hidden (these have corrasponding Chunks )
        'parent' => 'first_list', // 0(null) or the parent element ID
        'title' => 'Select your favorite food',
        'attr' => 'style="width: 100px"', // you can add inline CSS or other attributes to the form element
        'options' => array(
                // name(disply) => value
                'Pizza' => 'pizzaCode',
                'Steak' => 'steakCode',
                // create an option group:
                'Vegetarion' => array(
                    // again name(display) => value
                    'Tofu' => 'tofu code',// the code needs to be a valid HTML attribute value
                    'Linguine Florentine' => 'linguine florentine code'
                ) 
            ),
        'label' => 'Select your favorite food', // $formName (name) by default
        'containerClass' => 'medium',
    )
);
// radio buttons:
$form->addContainer('li', 'radio_buttons', array(
        'class' => 'full',
        'parent' => 'first_list',
        'startElement' => '<p>Choose your favorite team</p>'
        
    ) 
);
    $form->addContainer('ul', 'nested_list', array(
            'class' => '',
            'parent' => 'radio_buttons'
            
        ) 
    );
        $form->addField('fav_team', 'rd_fav_team', 
            array(
                'element' => 'radio', // existing types: - input, textarea, select, radio, checkbox, hidden (these have corrasponding Chunks )
                'type' => 'radio',// is this needed?
                'parent' => 'nested_list', // 0(null) or the parent element ID
                'title' => 'Bulls',
                'attr' => '', // you can add inline CSS or other attributes to the form element
                'value' => 'Bulls',
                'label' => 'Chicago Bulls', // $formName (name) by default
                'containerClass' => 'medium spaceRight',
            )
        );
        $form->addField('fav_team', 'rd_fav_team2', 
            array(
                'element' => 'radio', // existing types: - input, textarea, select, radio, checkbox, hidden (these have corrasponding Chunks )
                'type' => 'radio',// is this needed?
                'parent' => 'nested_list', // 0(null) or the parent element ID
                'title' => 'Heat',
                'attr' => '', // you can add inline CSS or other attributes to the form element
                'value' => 'Heat',
                'label' => 'Miami Heat', // $formName (name) by default
                'containerClass' => 'medium spaceRight',
            )
        );
 
// custom HTML with in a li:
$form->addContainer('li', 'custom_html', array(
        'class' => 'full',
        'parent' => 'first_list',
    ) 
);
    // radio buttons:
    $form->addHtml(
        '<label for="[[+elementID]]" class="[[+labelClass]]" [[+labelAttr]]>[[+label]]</label>
        <input name="mycustomHtmlField" type="password" value="[[+value]]" id="[[+elementID]]" class="[[+class]]" title="[[+title]]" [[+attr]] />
        <!-- allow child element to be attached via the API -->
        [[+childElement]] 
        ', 
            'myhtml_id', // this is the elementID
            // you can still use the config to process your html as a Chunk: 
            array(
                'class' => 'myclass',
                'parent' => 'custom_html',
                'label' => 'This is a custom HTML field that is using the API placeholders'
        ) 
    );
        // now attach a standard element to your custom element:
        $form->addContainer('div', 'nested_container', array(
                'class' => 'full',
                'parent' => 'myhtml_id',
                'startElement' => '<p>My container..</p>'
            ) 
        );
        // even attach more custom elements:
        $form->addHtml('<p>My nested info...</p>', 'mynested_id', array( 'parent' => 'myhtml_id') );
// finially add a submit button:
$form->addSubmit('submit_form', 'btn_submit_form', 
    array(
        'value' => 'Submit this form!', // a default value
        'parent' => 'first_list', // 0(null) or the parent element ID
        'class' => 'mysubmit_button', // user input
        'containerClass' => 'clear medium',
    )
);


/**
 * Render the form:
 */
$output = $form->render();

$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, '' );
if ( !empty($toPlaceholder) ) {
    $modx->setPlaceholder($toPlaceholder, $output);
    return '';
}
return $output;