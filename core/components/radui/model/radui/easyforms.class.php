<?php

/**
 * Create HTML Forms using MVC the MODX way
 * Form types:
 *     
 */
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
     * default form data
     * @param (array) $default_data
     */
    protected $default_data = array();
    
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
     */
    public function addClass($element_id, $class) {
        
    }
    /**
     * add a class to an existing HTML element
     * @param (string) $element_id - the id for the HTML 
     *    element that is to have a class added 
     * @param (string) $class - can be one or many, just separate with space as you would in HTML
     */
    public function removeClass($element_id, $class) {
        
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
        
        $form_parts = $this->renderElement($this->associated[0]);// start with the heighest level
        $properties = array_merge($this->config,$form_parts);
        
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
     * @param (Sting) $spacing - the spaces to add to lines to create a well formed HTML
     * @return (array) $children
     */
    protected function renderElement($parents, $spacing='  ') {
        $children = array();
        
        foreach ( $parents as $parent ) {
            if ( isset($this->remove_elements[$parent])) {
                continue;
            }
            $children_html = array();
            $properties = array();
            $properties = $this->elements[$parent];// this is the current elements data
            if ( isset($this->associated[$parent]) ) {
                // get the children
                $children_html = $this->renderElement($this->associated[$parent], $spacing.'  ');
                $properties = array_merge($children_html, $properties);
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
            // set defaults and process data:
            if ( !isset($properties['label']) && isset($properties['name']) ) {
                $properties['label'] = $properties['name']; 
            }
            // set options for selects
            if ( isset($properties['options']) && is_array($properties['options']) ) {
                $options = $properties['options'];
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
            
            // set basic properties to empty/null - this to to reduce processing and pushed down placeholders
            $empty_data = array(
                     'elementType' => '',
                     'parent' => '',
                     // 'location' => childElement(default), startElement, endElement, or midElement
                     'class' => '',
                     'attr' => '',
                     'name' => '',
                     'title' => '',
                     'id' => '',
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
                );
            $properties = array_merge($empty_data, $properties);
            $html = '';
            if ( $properties['elementType'] == 'html' ) {
                $chunk = $this->modx->newObject('modChunk');
                $chunk->setContent($properties['html']);
                $html = $chunk->process($properties);
            } else {
                if ( isset($properties['chunk']) ) {
                    $chunk = $properties['chunk'];
                } else {
                    if ( $properties['elementType'] == 'field' ) {
                        // default types
                        $chunk = $this->theme.$properties['element'];
                    } else {
                        $chunk = $this->theme.$properties['elementType'];
                    }
                }
                $properties['elementID'] = $parent;
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
     * order the elements and place them into an finished array
     */
    protected function orderElements() {
        // start fresh
        $this->ordered_elements = array();
        $this->associated = array();
        /**
         * $this->ordered_elements = array(
         *      elementID => data( 'elementType' => , 'elementName' => '', 'children' => REPEAT )
         * );
         * 
         * associate = array(
         *      parentElementID => array( childrenElmentId, ... ),
         *      txtName => 
         *      fldFieldSet =>
         * 
         * )
         * temp_elments = array(
         *      elementID => data( 'elementType' => , 'elementName' => '', 'children' => REPEAT )
         * );
         */
        $tmp_parents = array();
        $this->tmp_children = array();
        $tmp_holder = array();
        $tmp_prebuild = array();
        $prefix = '';
        foreach ( $this->form_data as $element_type => $items ) {
            switch ($element_type) {
                case 'fieldSets':
                    $prefix = 'fds';
                case 'tabs':
                    $prefix = 'tab';
                case 'containers':
                    $prefix = 'ctn';
                    break;
                case 'fields':
                default:
                    $prefix = 'fld';
                    break;
            }
            foreach ($items as $name => $data) {
                if ( isset($data['id']) & !empty($data['id']) ) {
                    $el_id = $data['id'];
                } else {
                    $el_id = $prefix.'_'.strtolower($name);
                } 
                $parent = $sequence = '';
                $set = FALSE;
                // this is a child elment
                if ( isset($data['attachTo']) && !empty($data['attachTo']) ) {
                    // does the child elment exist:
                    $parent = $data['attachTo'];
                    if ( !isset($this->tmp_children[$parent]) ) {
                        $this->tmp_children[$parent] = array();
                    }
                    $this->tmp_children[$parent][$el_id] = array('name' => $name, 'data' => $data);
                    /*/ does it exist?
                    if ( isset($tmp_holder[$data['attachTo']]) ) {
                        $tmp_holder[$data['attachTo']]['children'][] = $data;
                    } else {
                        // if not put it in the pre_build
                        $tmp_prebuild[$data['attachTo']]['children'][] = $data;
                    } */

                } else {
                    // this is a parent
                    $tmp_parents[$el_id] = array('data' => $data);
                    
                    /*/ create new base level
                    $tmp_holder[$data['attachTo']]['children'][] = $data;
                    $tmp_holder[$data['attachTo']]['children'][] = $data;
                    */
                }/*
                $this->associated[$el_id] = array(
                        'parent' => $parent,
                        'sequence' => '',
                        'set' => $set,
                    );
                 */
                
                /**
                 * parent to child
                 * feildset1 => array(container1)
                 * container1 => array(field1, field2)
                 */
                $this->associated[$parent][] = $el_id;
                
                $tmp_holder[''];
                
            }
        }
        // order the parents:
        
        // now build the parent->child relationships 
        foreach ( $tmp_parents as $element => $data ) {
            $this->ordered_elements[$element] = array(
                'element_data' => $data,
                'children' => $this->buildChildren($element)
            );
        }
    }
    
    /**
     * 
     */
    protected function buildChildren($element) {
        if ( isset($this->associated[$element]) ) {
            // return the children elementID
            $children = $this->associated[$element];
            // get the current elements data
            $data = array();
            $data = $this->tmp_children[$element];
            // el_id => data
            
            // now see if it has any children
            foreach ( $children as $child ) {
                
                $children = $this->buildChildren($child);
            }
            // @TODO Get the data!
            return $data;
        }
        return FALSE;
    }
}
