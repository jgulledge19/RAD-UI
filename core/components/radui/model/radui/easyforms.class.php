<?php

/**
 * Create HTML Forms using MVC the MODX way
 * Form types:
 *     
 */
require_once 'matericalizedpaths.class.php';
class EasyForms {
	/**
     * all elements that will be on the form
     * Current:
     * array(
     *      'elementID' => array( // elmentID -> user input or auto generate
     *                      'elementType' => field, fieldset, tab, container, ect..
     *                      'parent' => '' 0(null) or the parent element ID
     *                          'location' => childElement(default), startElement, endElement, or midElement
     *                      'class' => user input
     *                      'attr' => '' user input str of attribute="value"
     *                      'name' => ''
     *                      'title' => '' 
     *                      'id' => '' 
     *                  // feild sets:
     *                      
     *                  // tabs:
     *                      
     *                       
     *                  // form fields: 
     *                      'label' => ''
     *                      'element' => '' - button, checkbox, hidden, input, radio, select, textarea (these have corrasponding Chunks )
     *                      'type' => '' input -> button, text, file, reset, submit,password,
     *                                     checkbox, radio, hidden, image, ect.. (combobox, autosuggest, )
     *                      'require' => '' HTML5 attribute
     *                      'labelClass' => '' userINput
     *                      
     *                      'startElement' =>
     *                      'midElement' => 
     *                      'childElement' =>
     *                      'endElement' =>
     *                      
     *                 // for all but only for plugins 
     *                      'sequence' => (int) the order in which the form element will appear
     *                      'placeAfter' => elementID
     *                      'placeBefore' => elementID
     *                  )
     *          )
     * )
     */
    protected $elements = array();
    
    
    /**
     * the associated list
     */
    protected $associated = array();
    /**
     * the element properties that will be added before on render - array('element_id' => array('property'=>value))
     */
    protected $add_properties = array();
    /**
     * the remove CSS classes list array('element_id' => array('CSSPlace'=>value))
     */
    protected $remove_properties = array();
    /**
     * pad the HTML with spaces 
     */
    protected $pad_lines = true;
    /**
     * removed elements
     */
    protected $remove_elements = array();
    /**
     * the form data config
     *  array( 
     *     action=> the uri/url to process the form,
     *     method=> { get | post },
     *     id=>'',
     *     enctype=> { application/x-www-form-urlencoded | multipart/form-data | text/plain } 
     *     
     *     name=>'',
     *     accept-charset=>''
     *     attr=> any other attributes you want to add as property="value"
     *     )
     */
    protected $config = array();
    
    /**
     * Theme - this is the theme or set of Chunks the will be used to build the form
     */
    protected $theme;
    
    /**
     * @access protected
     * @param (INT) $instance_id - the RadFormInstances id
     */
    protected $instance_id = 0;
    /**
     * default form data
     * @param (array) $default_data
     */
    protected $default_data = array();
    
    /**
     * @param (Array) form_options -> array('name' => 'value')
     */
    protected $form_options = array();
    
    /**
     * @param (array) $answers - array(element_id => array( rank => array(name=>value, data...) ) )
     * @access protected
     */
    protected $answers = array();
    /**
     * @param (array) $processed_data - array('element_id' => array( rank => array(name=>value, data...)) )
     * @access protected
     */
    protected $processed_data = array();    
    
    /**
     * @param (array) $errors - array(name => array( rank => array(error)) )
     * @access protected
     */
    protected $errors = array();
    
    /**
     * @param (Boolean) $has_errors
     * 
     */
    protected $has_errors = false;
    /**
     * @param (Object) the EasyFormsValidate class
     */
    public $validator = null;
    
    /**
     * @param (Int) $current_page
     */
    protected $current_page = 1;
    /**
     * @param (xPDO Object) $pages 
     */
    protected $pages = null;
    /**
     * @param (Array) $rank_pages - rank=>ID
     */
    protected $rank_pages = array();
    /**
     * @param (Object MaterializedPaths) $paths
     */
    public $paths = NULL;//  $this->paths = new MaterializedPaths
    /**
     * the modx object
     */
    public $modx;
    
    /**
     * @param $modx
     * @param (array) $config - the for
     * @param (string) $theme - the theme for the form elements
     */
    function __construct(modX &$modx,array $config = array(), $theme='radui') {
        $this->modx =& $modx;
        $this->config = $config;
		$this->theme = $theme;
	}
    
    /**
     * Set form data
     * @param (string) $attribute
     * @param (string) $value
     * @return void 
     */
    public function setForm($attribute, $value) {
        $this->config[$attribute] = $value;
    }
    
    /**
     * load default form values
     * @param (array) $data in a name=>value
     * @return void 
     */
    public function loadData($data) {
        $this->default_data = $data;
    }
    /**
     * Set value of a single form element
     * @param (string) $name - the name of the form field
     * @param (mixed) $value - the value you wish to set the field to 
     *               can be an array for a group of checkboxes like chkbox[]
     * @return void 
     */
    public function setFormValue($name, $value) {
        $this->default_data[$name] = $value;
    }
    
    /**
     * Create a field set - just adds config specific info
     * @param (string) $legend
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addFieldset($legend, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'fieldset';
        $config['legend'] = $legend;
        
        return $this->addElement($id, $config);
    }
    /**
     * Create a Generic Container
     * @param (string) $elment - the HTML element - ul, div, etc..
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addContainer($element='ul', $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'container';
        $config['element'] = $element;
        
        return $this->addElement($id, $config);
    }
    // @TODO need addTabs then you can add a tab to it
    /**
     * Add a tab 
     * @param (string) $tabTitle - the title of the tab
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addTab($tabTitle, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'tab';
        $config['tabTitle'] = $tabTitle;
        
        return $this->addElement($id, $config);
    }
    /**
     * Add form field - input, textarea, select, ect..
     * @param (string) $fieldName - the name of the form element
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addField($fieldName, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'field';
        $config['name'] = $fieldName;
        // set some defaults: element
        
        return $this->addElement($id, $config);
    }
    
    /**
     * Add custom HTML 
     * maybe the element? then the innerHtml?  so they can be turned on/off
     * @param (string) $html - the HTML string
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addHtml($html, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'html';
        $config['html'] = $html;
        
        return $this->addElement($id, $config);
    }
    
    /**
     * Add Submit input field 
     * @param (string) $fieldName - the name of the form element
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addSubmit($fieldName, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'submit';
        $config['name'] = $fieldName;
        // set some defaults: element
        if ( !isset($config['value']) ) {
            $config['value'] = $fieldName;
        }
        if ( !isset($config['element']) ) {
            $config['element'] = 'div';
        }
        return $this->addElement($id, $config);
    }
    /**
     * Add Button 
     * @param (string) $fieldName - the name of the form element
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addButton($fieldName, $id, $config=array()) {    // elementType;
        $config['elementType'] = 'button';
        $config['name'] = $fieldName;
        // set some defaults: element
        if ( !isset($config['value']) ) {
            $config['value'] = $fieldName;
        }
        return $this->addElement($id, $config);
    }
    
    /**
     * Create a Multi Container - a group of child elements that can be dynamically added
     * @param (string) $fieldName - the name of the hidden form element that will keep count
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config - array( 'multiLevel' => INT, 'requireLevel' => INT )
     *  
     * 
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addMultiContainer($fieldName, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'multiContainer';
        $config['name'] = $fieldName;
        // $config['element'] = $element;
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->addMultiContainer] - set multiLevels - '.@$config['multiLevel'] );
        if ( !isset($config['multiLevel']) || !is_int($config['multiLevel']) ) {
            $config['multiLevel'] = 1;
            $config['value'] = 1;
        } else {
            $config['value'] = $config['multiLevel'];
        }
        $defaults = array(
            'multiLimit' => 0,// 0 is no limit
            'requireLevel' => 0,  // if set than those are required: ex 2 so the first 2 are required
            'attrLimit' => '',
            'attrRequire' => '',
            'validation_rules' => array(),
        );
        $config = array_merge($defaults, $config);
        
        if ( !isset($config['validation_rules']['type']) ) {
            $config['validation_rules']['type'] = 'number';
        }
        
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->addMultiContainer] - default multiLevels - '.$config['multiLevel'] );
        return $this->addElement($id, $config);
    }
        
    
    // addChunk:
    // addSnippet?
    
    /**
     * Add an html element to the form
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id
     */
    protected function addElement($id, $config) {
        $this->elements[$id] = $config;
        
        // add any preexisting properties
        if ( isset($this->add_properties[$id]) ) {
            foreach($this->add_properties[$id] as $property => $value) {
                if ( isset($this->elements[$id][$property]) ) {
                    $this->elements[$id][$property] .= ' '.$value;
                } else {
                    $this->elements[$id][$property] = $value;
                }
            }
        }
        // remove preexisting removes:
        if ( isset($this->remove_properties[$id]) ) {
            foreach($this->remove_properties[$id] as $property => $value) {
                if ( isset($this->elements[$id][$property]) ) {
                    if ( is_array($value) ) {
                        foreach ($value as $v) {
                            $this->elements[$id][$property] = str_replace($v, '', $this->elements[$id][$property]);
                        }
                    } else {
                        $this->elements[$id][$property] = str_replace($value, '', $this->elements[$id][$property]);
                    }
                }
            }
        }
        
        // now the associated:
        $parent = 0;
        if ( isset($config['parent']) && !empty($config['parent']) ) {
            $parent = $config['parent'];
        } 
        // check for order:
        if ( isset($config['placeBefore']) && !empty($config['placeBefore']) ) {
            $this->associated[$parent] = $this->setOrder($this->associated[$parent], $id, $config['placeBefore'], 'before');
        } else if ( isset($config['placeAfter']) && !empty($config['placeAfter']) ) {
            $this->associated[$parent] = $this->setOrder($this->associated[$parent], $id, $config['placeAfter'], 'after');
        } else if ( isset($config['sequence']) && !empty($config['sequence']) ) {
            $this->associated[$parent] = $this->setOrder($this->associated[$parent], $id, $config['sequence'], 'sequence');
        } else {
            $this->associated[$parent][] = $id;
        }
        return $id;
    }
    
    /**
     * Set the order of the array
     */
    protected function setOrder($array, $insert_element, $follow_element, $command='after') {
        $ordered = array();
        $count = 0;
        foreach ( $array as $element ) {
            ++$count;
            if ( $command == 'before' && $follow_element == $element ) {
                $ordered[] = $insert_element;
            }
            if ( $command == 'sequence' && $follow_element == $count ) {
                $ordered[] = $insert_element;
            }
            $ordered[] = $element;
            if ( $command == 'after' && $follow_element == $element ) {
                $ordered[] = $insert_element;
            }
        }
        return $ordered;
    }
    
    /**
     * remove an element from the render
     * @param (String) $element_id - the id for the HTML 
     *    element that is to be removed from render 
     */
    public function removeElement($element_id) {
        $this->remove_elements[] = $element_id;
    }
    
    
    
    /**
     * Hide Field - set to hidden
     */
    public function hideField($name) {
        
    }
    
    
    
    
    /**
     * add a class to an existing HTML element
     * @param (string) $element_id - the id for the HTML 
     *    element that is to have a class added 
     * @param (string) $class - can be one or many, just separate with space as you would in HTML
     * @param (String) $place - default: element or which is the class property - other values: containerClass, labelClass or for short container, label
     */
    public function addClass($element_id, $class, $place='class') {
        switch ($place) {
            case 'container':
                $place = 'containerClass';
                break;
            case 'label':
                $place = 'labelClass';
                break;
        }
        // add immediatly if possible:
        if ( isset($this->elements[$element_id]) ) {
            if ( isset($this->elements[$element_id][$place]) ) {
                $this->elements[$id][$place] .= ' '.$class;
            } else {
                $this->elements[$id][$place] = $class;
            }
        }
        // add when the elment is created:
        $this->add_properties[$element_id] = array($place => $class );
    }
    /**
     * remove a class from an existing HTML element
     * @param (string) $element_id - the id for the HTML element that is to have a class removed 
     * @param (mixed) $class - can be one string or an array of many: array('class1', 'class2');
     * @param (String) $place - default: element or which is the class property - other values: containerClass, labelClass or for short container, label
     */
    public function removeClass($element_id, $class, $place='class') {
        switch ($place) {
            case 'container':
                $place = 'containerClass';
                break;
            case 'label':
                $place = 'labelClass';
                break;
        }
        // remove immediatly if possible
        if ( isset($this->elements[$element_id]) ) {
            if ( isset($this->elements[$element_id][$place]) ) {
                if ( is_array($class) ) {
                    foreach ($class as $v) {
                        $this->elements[$id][$place] = str_replace($v, '', $this->elements[$id][$place]);
                    }
                } else {
                    $this->elements[$id][$place] = str_replace($class, '', $this->elements[$id][$place]);
                }
            }
        }
        // remove when the element is created
        $this->remove_properties[$element_id] = array($place => $class );
    }
    
    /**
     * add a property to an existing HTML element
     * @param (string) $element_id - the id for the HTML 
     *    element that is to have a class added 
     * @param (string) $property - the element property name
     * @param (Mixed) $value
     */
    public function addProperty($element_id, $property, $value) {
        $this->add_properties[$id] = array($property => $value );
    }
    
    /**
     * 
     */
    public function printAssociated() {
        return print_r($this->associated, TRUE);
    }
    public function printElements() {
        return print_r($this->elements, TRUE);
    }
    /**
     * Render the form to HTML
     * @param (boolean) $loadJS
     * @param (boolean) $loadCSS
     * @param (boolean) $loadJQuery
     * @return (string) $html
     */
    public function render($loadJS=TRUE, $loadCSS=TRUE, $loadJQuery=TRUE) {
        // loop through the associated method:
        //echo $this->printAssociated();
        
        $form_parts = $this->renderElement($this->associated[0]);// start with the heighest level
        $properties = array_merge($this->config, $form_parts);
        
        if ( isset($properties['chunk']) ) {
            $chunk = $properties['chunk'];
        } else {
            $chunk = $this->theme.'form';
        }
        $html = $this->modx->getChunk($chunk, $properties);
        
        return $html;
        
        $elements = array();
        
        /**
         * the associated list
         */
        $associated = array();
        /**
         * removed elements
         */
        $remove_elements = array();
    }
    
    
    
    
    /**
     * Render the elements using recursion
     * @param (array) $parents
     * @param (Int) $multiLevel the count of the level in the 
     * @param (Sting) $spacing - the spaces to add to lines to create a well formed HTML
     * @return (array) $children
     * 
     * 
     * SQL to get the number of multi elements that have been saved
     * SELECT a.rank FROM modx_rad_form_elements e
        JOIN modx_rad_form_answers a ON a.element_id = e.id
        WHERE
            e.form_id = 1 AND 
            e.parent = 1
        ORDER BY a.rank DESC
        LIMIT 1
     *      
     * 
     */
    protected function renderElement($parents, $multiLevel=0, $requireLevel=0, $spacing='  ') {
        $children = array();
        
        foreach ( $parents as $parent ) {
            if ( isset($this->remove_elements[$parent])) {
                continue;
            }
            $children_html = array();
            $properties = array();
            $properties = $this->elements[$parent];// this is the current elements data
            // if $multiLevel is greater then 0 make the name like formname[] and htmlID#  default value is associated by # or rank
            
            if ( isset($this->associated[$parent]) ) {
                // get the children
                // @TODO Multigroup loops set dynamic level and max levels
                if ( isset($properties['multiLevel']) && $properties['multiLevel'] > 0 ) {
                    $children_html = '';
                    $stop = $properties['multiLevel'];
                    if ( isset($this->default_data[$properties['name']]) ) {
                        if ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 && $this->default_data[$properties['name']] <= $properties['multiLimit'] && $this->default_data[$properties['name']] > $properties['multiLevel'] ) {
                            $stop = (int) $this->default_data[$properties['name']]; 
                        } elseif ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 && $this->default_data[$properties['name']] > $properties['multiLimit'] ) {
                            $stop = (int) $properties['multiLimit'];
                        } elseif( $this->default_data[$properties['name']] > $properties['multiLevel'] ) {
                            $stop = (int) $this->default_data[$properties['name']]; 
                        }
                    }
                    if ( $stop <= $properties['multiLevel'] ) {
                        $properties['removeDisable'] = 'disabled';
                    }
                    if ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 &&  $stop >= $properties['multiLimit'] ) {
                        $properties['addDisable'] = 'disabled';
                    }
                    for( $x=1; $x <= $stop; $x++ ){
                        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->renderElement] - multiLevels - '.$x);
                        // wrap in divs/elements
                        if ( isset($properties['childChunk']) ) {
                            $chunk = $properties['childChunk'];
                        } else {
                            $chunk = $this->theme.'multiItem';
                        }
                        $multiItem_properties = array(
                            'itemID' => $parent.'_item'.( $x > 1 ?  $x : ''),
                            'itemClass' => ( isset($properties['itemClass']) ? $properties['itemClass'] : ''),
                            'itemAttr' => ( isset($properties['itemAttr']) ? $properties['itemAttr'] : '')
                        );
                        $itemElement = ''; 
                        $itemHtml = $this->renderElement($this->associated[$parent], (int)$x, (isset($properties['requireLevel']) ? $properties['requireLevel'] : 0), $spacing.'  ');
                        foreach( $itemHtml as $item => $html ) {
                            $itemElement .= $html;
                        }
                        $multiItem_properties['itemElement'] = $itemElement;
                        $children_html .= $this->modx->getChunk($chunk, $multiItem_properties );
                        
                    }
                    $properties['childElement'] = $children_html;
                
                } else {
                    $children_html = $this->renderElement($this->associated[$parent], $multiLevel, $requireLevel,  $spacing.'  ');
                    $properties = array_merge($children_html, $properties);
                }
            }
             
            $send_to = 'childElement';
            
            if ( isset($this->elements[$parent]['location']) ) {
                $send_to = $this->elements[$parent]['location'];
            }
            if ( !isset($children[$send_to]) ) {
                $children[$send_to] = '';
            }
            
            
            /**
             * elementType:
             *  fieldset
             *  container
             *  tab
             *  field -> many chunks - element types
             *  html -> no chunk straight HTML
             *  button
             */
            $properties['elementID'] = $parent;
            if ( $multiLevel > 1 ) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->renderElement] - multiLevels add to name and ID - '.$multiLevel);
                // change the names to name# and elementID#
                if ( isset($properties['name']) ) {
                    $properties['name'] .= $multiLevel;
                }
                $properties['elementID'] .= $multiLevel;
                if ( isset($properties['require']) && $multiLevel > $requireLevel ) {
                    unset($properties['require']);
                }
            }
            // set defaults and process data:
            if ( !isset($properties['label']) && isset($properties['name']) ) {
                $properties['label'] = $properties['name']; 
            }
            // set options for selects
            if ( isset($properties['options'])  ) {
                if ( is_array($properties['options']) ) {
                      $options = $properties['options'];
                } else {
                    // load common library:
                    require_once $this->modx->radui->getConfig('raduiPath').'library/commonselectoptions.php';
                    $tmp =  explode('=>', (string) $properties['options']);
                    if ( isset($tmp[1]) ) {
                        // load other user library
                    }
                    $options = getCommonSelectOptions($tmp[0]);
                }
                
                
                $option_str = '';
                $selected_value = '';
                if ( isset($this->default_data[$properties['name']]) ) {
                    $selected_value = $this->default_data[$properties['name']];
                } else if ( isset($properties['value']) ) {
                    $selected_value = $properties['value'];
                }
                    
                foreach ($options as $name => $value) {
                    $selected = '';
                    // optgroup
                    if ( is_array($value) ) {
                        $option_str .= '<optgroup label="'.$name.'">';
                        foreach ( $value as $n => $v ) {
                            $selected = '';
                            if ( $v == $selected_value ) {
                                $selected = 'selected="selected"';
                            }
                            $option_str .= '<option value="'.$v.'" '.$selected.'>'.$n.'</option>';
                        }
                        $option_str .= '</optgroup>';
                    } else {
                        // regular option
                        if ( $value == $selected_value ) {
                            $selected = 'selected="selected"';
                        }
                        $option_str .= '<option value="'.$value.'" '.$selected.'>'.$name.'</option>';
                    }
                }
                $properties['selectOptions'] = $option_str;
            }
            // set checked for radios/checkboxes
            if ( isset($properties['value']) && isset($this->default_data[$properties['name']]) &&
                    $this->default_data[$properties['name']] == $properties['value'] ) {
                $properties['checked'] = 'checked="checked"';
            } else {
                $properties['checked'] = '';
            }
            //@TODO checkbox groups:
            
            // set the value for all hidden, text, textarea on POST/GET 
            if ( isset($properties['name']) && isset($this->default_data[$properties['name']]) && 
                    (
                        $properties['elementType'] == 'multiContainer' 
                            ||
                        $properties['elementType'] == 'field' && 
                        ( $properties['element'] == 'textarea' || $properties['element'] == 'hidden' || ($properties['element'] == 'input' && 
                            $properties['type'] != 'radio' && $properties['type'] != 'submit' && $properties['type'] != 'checkbox' 
                        ) )
                    ) 
                ) {
                $properties['value'] = $this->default_data[$properties['name']];
                // $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->renderElement] Set Default Value: '.$properties['value'] );
            }
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->renderElement] Name: '.@$properties['name'].' Default Value: '.@$this->default_data[$properties['name']] );
            if ( isset($properties['name']) && $properties['name'] == 'page_number' ) {
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->renderElement] Name: '.@$properties['name'].' Default Value: '.@$this->default_data[$properties['name']] );
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->renderElement] D: '.print_r($properties, true) );
            }
            
            // set basic properties to empty/null - this to to reduce processing and pushed down placeholders
            $empty_data = array(
                     'containerID' => $parent.'Container',
                     'containerClass' => '',
                     'containerAttr' => '',
                     
                     'elementType' => '',
                     'parent' => '',
                     'value' => '',
                     // 'location' => childElement(default), startElement, endElement, or midElement
                     'class' => '',
                     'attr' => '',
                     'name' => '',
                     'title' => '',
                     'id' => '', // is this used?
                     'label' => '',
                     'element' => '',
                     'type' => '',
                     'require' => '',
                     'labelClass' => '',
                     'startElement' => '',
                     'midElement' => '',
                     'childElement' => '',
                     'endElement' => '',
                     // for all but only for plugins
                     'sequence' => '',
                     'placeAfter' => '',
                     'placeBefore' => '',
                     'error' => 0,
                     // multi Items:
                     'multiLevel' => 0,
                     'multiRequire' => 0,
                     'multiLimit' => 0,
                     'addDisable' => '', // disable
                     'removeDisable' => '', // disable
                     // for JS functions:
                     'currentUrl' => $_SERVER['REQUEST_URI'],
                );
            $properties = array_merge($empty_data, $properties);
            
            if ( isset($this->errors[$properties['name']]) ) {
                $properties['error'] = true;
            }
            if ( isset($properties['validation_rules']) && is_array($properties['validation_rules'])) {
                $attr = '';
                foreach ( $properties['validation_rules'] as $n => $v ) {
                    if (empty($v)) {
                        continue;
                    }
                    switch ($n) {
                        case 'required':
                            if ( (boolean) $v ) {
                                $attr .= ' required="required" ';
                            }
                            break;
                        case 'type':
                            // h5validation:
                            $properties['class'] .= ' h5-'.$v;
                            break;
                        case 'pattern':
                            // no break
                        case 'maxlength':
                            // no break
                        case 'min': 
                            // no break
                        case 'max':
                            $attr .= ' '.$n.'="'.$v.'" ';
                            break;
                    }
                }
                if ( !isset($properties['attr']) ) {
                    $properties['attr'] = '';
                }
                $properties['attr'] = $attr.' '.$properties['attr'];
            }
            if ( isset($properties['placeholder']) && !empty($properties['placeholder'])) {
                $properties['attr'] = ' placeholder="'.$properties['placeholder'].'" '.$properties['attr'];
            }
                
            $html = '';
            if ( $properties['elementType'] == 'html' ) {
                $chunk = $this->modx->newObject('modChunk');
                $chunk->setContent($properties['html']);
                
                if ( isset($properties['name']) && isset($this->default_data[$properties['name']]) ) {
                    $properties['value'] = $this->default_data[$properties['name']];
                }
                $html = $chunk->process($properties);
            } else {
                if ( isset($properties['chunk']) ) {
                    $chunk = $properties['chunk'];
                } else {
                    if ( $properties['elementType'] == 'field' ) {
                        // default types
                        $chunk = $this->theme.$properties['element'];
                        if ( isset($properties['type']) && $properties['type'] == 'file' ) {
                            $chunk = $this->theme.'file';
                        }
                        
                    } else {
                        $chunk = $this->theme.$properties['elementType'];
                    }
                }
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->Render] Chunk: '.$chunk.' E: '.$parent );
                $html = $this->modx->getChunk($chunk, $properties);
            }
            // now add spacing to the lines:
            if ( $this->pad_lines ) {
                $html = $this->lineSpacing($html, $spacing);
            }
            $children[$send_to] .= $html;
        }
        return $children;
    }
    
    
    /**
     * Utility Function - add spacing to lines to create well formed HTML
     * ex:
     * <ul>
     *     <li></li>
     * </ul>
     * @param (String) $sting - html
     * @param (String) $spaces the spaces to prefix each line
     */
    protected function lineSpacing($string, $spaces) {
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
        /**
         * this method seems very slow:
        $fp = fopen('data:text/plain,'. $string,'rb');
        while ( ($line = fgets($fp)) !== false) {
          $padded_string .= $spaces.$line."\r\n";
        }*/
        return $padded_string;
    }
    
    
    
    
    /**
     * Load DB objects into class
     * @param (xPDO Object) $radForm
     * @param (Array) $properties - name=>value
     * @return (String) $html - the rendered/processed form
     */
    public function loadEngine( &$radForm, $properties ) {
        
        /**
         * 1. load form elements
         * 2. if $_POST process it
         *  a. if success then save data and advance page, reload form to next page - give success message (placehoder)
         *  b. if failure show errors - give failed message (placeholder)
         * 3. Load default data for non $_POST
         * 4. render form
         */
         // $_POST - get any post data: save data
        $use_pages = $radForm->get('use_pages');
        $this->form_options = json_decode($radForm->get('options'),true);
        
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Options: '.$radForm->get('options'));
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Form Options: '.print_r($this->form_options, true));
        // to allow uploads
        if ( isset($this->form_options['allow_uploads']) && $this->form_options['allow_uploads'] ) {
            $this->setForm('attr', ' enctype="multipart/form-data" ');
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] set form attr enctype');
        }
        $this->loadPages( $radForm->get('id'), $properties, false, true );
        
        // get user instance:
        $this->instance = null;
        $instance_id = 0;
        if ( isset($_SESSION['rad_form_instance_id']) && is_numeric($_SESSION['rad_form_instance_id']) ) {
            // load instance:
            $instance = $radForm->getOne('Instances', array('id' => $_SESSION['rad_form_instance_id'], 'form_status:!=' => 'Void'));
            if ( is_object($instance) ) {
                $this->instance = &$instance;
                $_SESSION['rad_form_instance_id'] = $instance_id = $this->instance->get('id');
            }
        } else if ( $this->modx->user->isAuthenticated($this->modx->context->get('key')) || $this->modx->user->isAuthenticated('mgr')  ) {
            // get modx user id and instance
            $uid = $this->modx->user->get('id');
            
            // @TODO make this so user can have more then one
            $instance = $radForm->getOne('Instances', array('user_id' => $uid, 'form_status:!=' => 'Void'));
            if ( is_object($instance) ) {
                $this->instance = &$instance;
                $_SESSION['rad_form_instance_id'] = $instance_id = $this->instance->get('id');
            }
        }
        
        $continue = true;
        
        $uid = $this->modx->user->get('id');
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] line: '.__LINE__.' UID: '.$uid);
        
        // @TODO get the events:
        if ( isset($_POST['page_number']) ) {
            if ( $instance_id == 0 ) {
                // make new instance for user
                $instance = $this->modx->newObject('RadFormInstances' );
                if ( is_object($instance) ) {
                    $this->instance = &$instance;
                    $this->instance->set('form_id', $radForm->get('id'));
                    if ( $this->modx->user->isAuthenticated($this->modx->context->get('key')) || $this->modx->user->isAuthenticated('mgr')  ) {
                        // get modx user id and instance
                        $uid = $this->modx->user->get('id');
                        $this->instance->set('user_id', $uid);
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Auth User: '.$uid );
                    }
                    $this->instance->set('start_time', date('Y-m-d H:i:s'));
                    // $this->instance->set('crm_id', $radForm->get('id'));// ?
                    if ( !$this->instance->save() ) {
                        // @TODO report error 
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] Save New Instance Erron on line: '.__LINE__.' E: '.$uid);
                    }
                    $_SESSION['rad_form_instance_id'] = $instance_id = $this->instance->get('id');
                }
            } 
            if ( /* $use_pages && */ isset($_POST['page_number']) && isset($this->rank_pages[$_POST['page_number']]) ) {
                $this->current_page = $_POST['page_number'];
                
            } else {
                // error!
            }
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Page#: '.$_POST['page_number']);
            
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] -'.print_r($this->rank_pages, TRUE).' - c: '.current($this->rank_pages));
            $this->loadElements($radForm, (isset($this->rank_pages[$this->current_page]) ? $this->rank_pages[$this->current_page] : 0/*current($this->rank_pages)*/ ));
            
            $this->loadAnswers($this->instance->get('id'));
            // process and check for errors:
            $this->process();//$this->rank_pages[$this->current_page]/*$instance_id*/);
            
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Errors: '.print_r($this->errors, true) );
            if ( $this->has_errors ) {
                $continue = FALSE;
                $this->modx->setPlaceholder('raduiErrors', print_r($this->errors, true));//
                
                // load default POST data:
                $this->loadData($_POST);
            } else {
                if ( isset($this->rank_pages[($this->current_page+1)]) ) {
                    $this->current_page++;
                }
            }
        } elseif ( isset($_GET['page_number']) ) {
            $this->current_page = (int) $_GET['page_number'];
        }
        
        if ( $continue ) {
            // reset
            $this->associated = array();
            $this->elements = array();
            // load next page
            $this->loadElements($radForm, $this->rank_pages[$this->current_page]);
            if ( is_object($this->instance) ) {
                
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] LoadAnswers: '.$this->rank_pages[$this->current_page]);
                $this->loadAnswers($this->instance->get('id'), $this->rank_pages[$this->current_page]);
            }
            
            
        }
        
        $this->setFormValue('page_number', $this->current_page);
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] Add page rank: '.$this->current_page);
        
        $this->addField('page_number', 'page_number_hidden', array('type' => 'hidden', 'element' => 'hidden'));
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadEngine] D Data: '.print_r($this->default_data, true) );
        // render - add page_number and instanceID(modx user id) as hidden/cookies
        return $this->render();
    }
    /**
     * @param (Int) $instance_id
     * @param (Int) $page_id 
     * @param (Boolean) $load_defaults
     * 
     */
    public function loadAnswers($instance_id, $page_id=0, $load_default=true) {
        if ( !is_object($this->paths) ){
            $this->paths = new MaterializedPaths($this->modx, $config=array() );
        }
        $default_data = array();
        $fetch_answers = $this->paths->getBranchAnswers($page_id, $criteria=array('instance_id' => $instance_id, 'form_id' => $this->instance->get('form_id') ) );
        if ( is_object($fetch_answers) ) {
            while ( $answer = $fetch_answers->fetch(PDO::FETCH_ASSOC) ) {
            // foreach ( $fetch_answers as $answer ) {
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadAnswers] N: '.$answer['name'].' A: '.$answer['value']);
                if ( $answer['rank'] > 1 ) { 
                    $answer['name'] .= $answer['rank'];
                    $answer['html_id'] .= $answer['rank'];// html id not DB id
                }
                $this->answers[$answer['html_id']] = $answer;
                // default data
                $default_data[$answer['name']] = $answer['value'];
            }
        }
        if ( $load_default ) {
            $this->loadData($default_data);
        }
    }
    
    
    /**
     * Load DB objects elements into class
     * @param (xPDO Object) $radForm
     * @param (Int) $branch_id or $page_id
     * @param (Array) $properties - name=>value
     * @return (String) $html - the rendered/processed form
     */
    public function loadElements( &$radForm, $branch_id/*, $properties*/ ) {
        // load data from Answers:
        // $form->loadData($default_data);
        
        // WITH in form Engine:
        $id_to_element = array();// ID# => html_id
        $name_to_id = array();
        
        if ( !is_object($this->paths) ){
            $this->paths = new MaterializedPaths($this->modx, $config=array() );
        }
        
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] Branch: '.$branch_id);
        if ( $branch_id == 0 ) {
            $elements = $this->paths->getTree(array('form_id' => $radForm->get('id') ) );
        } else {
            $elements = $this->paths->getBranch($branch_id, array('form_id' => $radForm->get('id') ) );
            
        }
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] -'.print_r($elements, TRUE));
        // elements are now in order so I don't need this here:
        /*
        foreach ( $elements as $element ) {
            $id_to_element[$element['id']] = $element['html_id'];
            $name_to_id[$element['name']] = $element['id'];
        }
        /*
            $elements = $radForm->getMany('Elements');
            
            foreach ( $elements as $element ) {
                $id_to_element[$element->get('id')] = $element->get('html_id');
                $name_to_id[$element->get('name')] = $element->get('id');
            }
        */
        
        $this->setForm('method', 'post');
        $this->setForm('action', '[[~[[*id]]]]');
        
        $x = 0;
        // load elements
        while ( $element = $elements->fetch(PDO::FETCH_ASSOC) ) {
            if ( $x++ > 100 ) {
               // break;
            }
        //foreach ( $elements as $element ) {
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements - foreach element] -'.print_r($element, TRUE));
            
            $id_to_element[$element['id']] = $element['html_id'];
            $name_to_id[$element['name']] = $element['id'];
            
            // $data = $element->toArray();
            $config = json_decode($element['config'], TRUE);
            $config['validation_rules'] = json_decode($element['validation_rules'], TRUE);
            
            if ( isset($element['parent_id']) && isset($id_to_element[$element['parent_id']]) ){
                $config['parent'] = $id_to_element[$element['parent_id']];// gets the html id
            } else {
                $config['parent'] = 0;// set for the first element
            }
            if ( isset($element['description']) ) {
                $config['description'] = $element['description'];
            }
            if ( !empty($element['default_value']) ) {
                $config['value'] = $element['default_value'];
            }
            unset($element['validation_rules']);
            unset($element['config']);
            unset($config['id']);// reserved for the DB object
            
            $config = array_merge($element, $config);
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->loadElements] Add: '.$element['type'].' T: '.@$config['type']. ' P: '.$element['parent_id'].' Name: '.$element['name']. ' T: '.$element['text'].' ID: '.$element['html_id']);
            switch( $element['type'] ) {
                
                /**
                 * Add a tab 
                 * @param (string) $tabTitle - the title of the tab
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Tab': //($tabTitle, $id, $config=array()) {
                    $this->addTab($element['text'], $element['html_id'], $config);
                    break;
                /**
                 * Create a Generic Container
                 * @param (string) $elment - the HTML element - ul, div, etc..
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Container': //($element='ul', $id, $config=array()) {
                    $this->addContainer($element['text'], $element['html_id'], $config);
                    break; 
                /**
                 * Create a field set - just adds config specific info
                 * @param (string) $legend
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Fieldset': // ($legend, $id, $config=array()) {
                    $this->addFieldset($element['text'], $element['html_id'], $config);
                    break;
                /**
                 * Add form field - input, textarea, select, ect..
                 * @param (string) $fieldName - the name of the form element
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Field': //($fieldName, $id, $config=array()) {
                    if( !isset($config['label']) && !empty($element['text']) ){
                        $config['label'] = $element['text'];
                    } 
                    $this->addField($element['name'], $element['html_id'], $config);
                    break;
                /**
                 * Add custom HTML 
                 * maybe the element? then the innerHtml?  so they can be turned on/off
                 * @param (string) $html - the HTML string
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Html'://($html, $id, $config=array()) {
                    $this->addHtml($element['text'], $element['html_id'], $config);
                    break;
                /**
                 * Add Submit input field 
                 * @param (string) $fieldName - the name of the form element
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Submit': //($fieldName, $id, $config=array()) {
                    $this->addSubmit($element['name'], $element['html_id'], $config);
                    break;
                    
                /**
                 * Add Button 
                 * @param (string) $fieldName - the name of the form element
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                case 'Button'://($fieldName, $id, $config=array()) {    // elementType;
                    $this->addButton($element['name'], $element['html_id'], $config);
                    break;
                /**
                 * Create a Multi Container - a group of child elements that can be dynamically added
                 * @param (string) $fieldName - the name of the hidden form element that will keep count
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config - array( 'multiLevel' => INT, 'requireLevel' => INT )
                 */
                case 'MultiContainer': //($fieldName, $id, $config=array()) {
                    $config['label'] = $element['text'];
                    $this->addMultiContainer($element['name'], $element['html_id'], $config);
                    break;
                /**
                 * Add an html element to the form
                 * @param (string) $id - the value for the HTML id attribute 
                 * @param (array) $config
                 */
                // case 'Element': //($id, $config) { - NOT sure this should be here
                
                
                
                /*******************************************/
            }
        }
    }
    /**
     * Load form pages
     * @param (Int) $form_id
     * @param (Array) $properties - name=>value
     * @param (Boolean) $process_chunk - process and send to placeholder
     * @param (Boolean) $new - true will force new SQL query
     * @return Void
     */
    public function loadPages( $form_id, $properties, $process_chunk = false, $new=false ) {
        $form_id = (int) $form_id;
        /**
         * Loop pages via Chunks and put to placeholder
         */
         // $_POST - get any post data: save data
        if ( !is_object($this->pages) || $new ) {
            $this->rank_pages = array();
            // get pages: array(rank=>id); 
            $c = $this->modx->newQuery('RadFormElements', array('type' => 'Page', 'form_id' => $form_id));
            $c->sortby('rank', 'ASC');
            $this->current_page = 1;
            $this->pages = $this->modx->getIterator('RadFormElements', $c); // $radForm->getMany('Elements', $c );
        }
        $chunk = $this->modx->getOption('PageChunk', $properties, 'RadUiFormPage');
        $output = '';
        if ( is_object($this->pages) ) { 
            foreach ( $this->pages as $page ) {
                $this->rank_pages[$page->get('rank')] = $page->get('id'); 
                if ( $process_chunk ) {
                    $properties = $page->toArray();
                    $properties = array_merge(json_decode($properties['config']), $properties);
                    $properties = array_merge(json_decode($properties['validation_rules']), $properties);
                    if ($properties['rank'] == $this->current_page ) {
                        $properties['currentClass'] = 'active';
                    } else {
                        $properties['currentClass'] = '';
                    }
                    
                    $output .= $this->modx->getChunk($chunk, $properties);
                }
            }
        }
        if ( $process_chunk ) {
            // send to placeholder
            $placeholder_name = $this->modx->getOption('PagesPlaceholder', $properties, 'RadUiFormPages');
            
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->loadPages] Output: '.$output);
            $this->modx->setPlaceholder($placeholder_name, $output);
        }
        
    }
    /**
     * Process the POST data
     * @param (Int) $page - the current page
     * @return (Boolean)
     */
    public function process($depth=0/*, $instance_id*/) {
        // load validater:
        require_once 'easyformsvalidate.class.php';
        $this->validator = new EasyFormsValidate($this->modx);
        // loop through the associated method:
        $this->modx->beginTransaction();
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->process] Depth: '.$depth.' A: '.print_r($this->associated[$depth], true));
        $this->has_errors = false;
        $this->processElement($this->associated[$depth]);
        if ( $this->has_errors ) {
            // errors happended:
            if ( 1 == 2 ) {
                // save parital:
                $this->modx->commit();
                $this->validator->completeFiles(true);
            } else {
                $this->modx->rollback();
                $this->validator->completeFiles(false);
            }
            $this->errors = $this->validator->getErrors();
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->process] Rollback');
            return FALSE;
        } else {
            $this->modx->commit();
            $this->validator->completeFiles(true);
            $this->errors = array();
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->process] Commit');
            return TRUE;
        }
    }
    
    /**
     * Render the elements using recursion
     * @param (array) $parents
     * @param (Int) $multiLevel the count of the level in the
     * @param (Int) $requireLevel the count of the level in the  
     * @param (Sting) $spacing - the spaces to add to lines to create a well formed HTML
     * @return (array) $children
     * 
     * 
     * SQL to get the number of multi elements that have been saved
     * SELECT a.rank FROM modx_rad_form_elements e
        JOIN modx_rad_form_answers a ON a.element_id = e.id
        WHERE
            e.form_id = 1 AND 
            e.parent = 1
        ORDER BY a.rank DESC
        LIMIT 1
     *      
     * Validation
     * Required
     * 
     */
    protected function processElement($parents, $multiLevel=0, $requireLevel=0) {
        $children = array();
        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] P: '.print_r($parents, true));
        foreach ( $parents as $parent ) {
            
            if ( isset($this->remove_elements[$parent])) {
                // continue;??
            }
            $properties = $this->elements[$parent];// this is the current elements data
            // if $multiLevel is greater then 0 make the name like formname[] and htmlID#  default value is associated by # or rank
            
            //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] EL: '.@$properties['name'].' ID: '.$properties['id']. ' => ML: '.@$properties['multiLevel'] );
            
            if ( isset($this->associated[$parent]) ) {
                // get the children
                //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Get Children' );
                if ( isset($properties['multiLevel']) && $properties['multiLevel'] > 0 ) {
                    $children_html = '';
                    
                    $stop = $properties['multiLevel'];
                    if ( isset($_POST[$properties['name']]) && 
                        ( 
                            !isset($this->default_data[$properties['name']]) || 
                            ( isset($this->default_data[$properties['name']]) && $_POST[$properties['name']] >= $this->default_data[$properties['name']] ) 
                        ) ) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] POST Stop: '.$stop.' Default: '.@$this->default_data[$properties['name']] );
                        if ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 && $_POST[$properties['name']] <= $properties['multiLimit'] && $_POST[$properties['name']] > $properties['multiLevel'] ) {
                            $stop = (int) $_POST[$properties['name']]; 
                        } elseif ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 && $_POST[$properties['name']] > $properties['multiLimit'] ) {
                            $stop = (int) $properties['multiLimit'];
                        } elseif( $_POST[$properties['name']] > $properties['multiLevel'] ) {
                            $stop = (int) $_POST[$properties['name']]; 
                        }
                    } elseif ( isset($this->default_data[$properties['name']]) ) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Default Data Stop: '.$stop );
                        if ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 && $this->default_data[$properties['name']] <= $properties['multiLimit'] && $this->default_data[$properties['name']] > $properties['multiLevel'] ) {
                            $stop = (int) $this->default_data[$properties['name']]; 
                        } elseif ( isset($properties['multiLimit']) && $properties['multiLimit'] > 0 && $this->default_data[$properties['name']] > $properties['multiLimit'] ) {
                            $stop = (int) $properties['multiLimit'];
                        } elseif( $this->default_data[$properties['name']] > $properties['multiLevel'] ) {
                            $stop = (int) $this->default_data[$properties['name']]; 
                        }
                    }
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Stop: '.$stop );
                    for( $x=1; $x <= $stop; $x++ ){
                        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] - multiLevels - '.$x);
                        $this->processElement($this->associated[$parent], $x, $properties['requireLevel']);
                    }
                
                } else {
                    $this->processElement($this->associated[$parent], $multiLevel, $requireLevel);
                }
                
            }
            if ( $properties['elementType'] != 'field' && $properties['elementType'] != 'multiContainer' ) { // multiContainer must have a hidden field
                continue;
            }
            /**
             * elementType:
             *  fieldset
             *  container
             *  tab
             *  field -> many chunks - element types
             *  html -> no chunk straight HTML
             *  button
             */
            $properties['elementID'] = $parent;
            if ( $multiLevel > 1 ) {
                // change the names to name# and elementID#
                if ( isset($properties['name']) ) {
                    $properties['name'] .= $multiLevel;
                }
                $properties['elementID'] .= $multiLevel;
                
            } 
            if ( $multiLevel >= 1 && isset($properties['validation_rules']['required']) && $multiLevel > $requireLevel ) {
                // @TODO don't like this need to revise
                unset($properties['validation_rules']['required']);
            }
            // validation rules:
            if ( $properties['elementType'] == 'field' || $properties['elementType'] == 'multiContainer' ) {
                // set options for selects
                if ( isset($properties['options']) && is_array($properties['options']) ) {
                    $options = $properties['options'];
                    // @TODO  array_keys?  is the $_post value in the options if set?
                    
                }
                // @TODO checkbox groups:
                
                $type = $properties['elementType'];
                if ( isset($properties['type']) && !empty($properties['type']) ) {
                    $type = $properties['type'];
                } elseif ( isset($properties['element']) ) {
                    $type = $properties['element'];
                }
                // @TODO move to onload:
                if ( $type == 'file' ) {
                    // set some defaults:
                    if ( !isset($properties['validation_rules']['allow_ext']) ) {
                        $properties['validation_rules']['allow_ext'] = array('doc', 'docx', 'pdf', 'rtf','txt');
                    }
                    if ( !isset($properties['validation_rules']['size_limit']) ) {
                        $properties['validation_rules']['size_limit'] = 250;// 250 kb
                    } 
                }
                if ( !$this->validator->validate($properties['name'], $type, $properties['validation_rules'])) {
                    // did not validate
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Break loop return false');
                    $this->has_errors = true;
                    continue;
                    // return FALSE;
                } elseif ( isset($this->answers[$properties['elementID']]) && 
                            ( 
                                (!isset($_POST[$properties['name']]) && $type != 'file') || 
                                ( $type == 'file' && $this->validator->isRemoveFile($properties['name']) ) 
                            ) 
                    ) {
                    if ( $type == 'file' && $this->validator->isRemoveFile($properties['name']) ) {
                        $f_data = json_decode($this->answers[$properties['elementID']]['json_value'], true);
                        $existing = ( isset($f_data['path']) ? $f_data['path'] : '' );
                        // mark for unlink: 
                        $this->validator->removeFile($properties['name'], $existing);
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Remove Row eID: '.$properties['id'] /*.' => '.$_POST[$properties['name']] */);
                    }
                    // Remove multi elements that have been deleted
                    $answer = $this->modx->getObject('RadFormAnswers', array('id' => $this->answers[$properties['elementID']]['id']));
                    $answer->remove();
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Remove Row eID: '.$properties['id'] /*.' => '.$_POST[$properties['name']] */);
                } elseif ( $properties['group_element_id'] == 0 && 
                            ( 
                                ( isset($this->answers[$properties['elementID']]) && $type != 'file' ) || 
                                ( isset($_POST[$properties['name']]) && !empty($_POST[$properties['name']]) ) || 
                                // files: 
                                ( $type == 'file' && $this->validator->isProcessFile($properties['name']) )
                            ) 
                    ) {
                    $json_value = '';
                    if ( $this->validator->isProcessFile($properties['name']) ) {
                        $existing = '';
                        if ( isset($this->answers[$properties['elementID']]) ) {
                            $f_data = json_decode($this->answers[$properties['elementID']]['json_value'], true);
                            $existing = ( isset($f_data['path']) ? $f_data['path'] : '' );
                        }
                        // @TODO provide a way for the file element to override this 
                        $destination = $this->form_options['uploads'].'instance'.$this->instance->get('id').DIRECTORY_SEPARATOR;
                        if ( !is_dir($destination) ) {
                            // create the directory:
                            mkdir($this->form_options['uploads'].'instance'.$this->instance->get('id').DIRECTORY_SEPARATOR);
                        }
                        $new_name = $properties['name'].'_'.time();
                        // move file to location:
                        $file_data = $this->validator->uploadFile($properties['name'], $destination, $new_name, $existing);
                        $json_value = json_encode($file_data);
                        $_POST[$properties['name']] = $file_data['name'];
                    }
                    
                    // save to db:
                    // $this->answers[$answer['html_id']] = $answer;
                    if ( isset($this->answers[$properties['elementID']]) ) {
                        // update existing object:
                        $answer = $this->modx->getObject('RadFormAnswers', array('id' => $this->answers[$properties['elementID']]['id']));
                    } else {
                        // new object:
                        $answer = $this->modx->newObject('RadFormAnswers');
                    }
                    $save_data = array(
                        'instance_id' => $this->instance->get('id'),
                        'element_id' => $properties['id'],
                        'rank' => $multiLevel,
                        'value' => $_POST[$properties['name']],
                        'json_value' => $json_value,
                    );
                    $answer->fromArray($save_data);
                    $answer->save();
                    //$this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->processElement] Save Data eID: '.$properties['id'].' => '.$_POST[$properties['name']]);
                }
            }
        }
        return $children;
    }
    
}
