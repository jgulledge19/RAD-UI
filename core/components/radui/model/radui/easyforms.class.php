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
     *                      'element' => '' - input, textarea, select, html
     *                      'type' => '' input -> button, text, checkbox, file, radio, hidden, password, reset, submit, image, ect.. (combobox, autosuggest, )
     *                      'require' => '' HTML5 attribute
     *                      'labelClass' => '' userINput
     *                      
     *                      'preElement' =>
     *                      'postElement' =>
     *                      'midElement' => 
     *                      'childElement' =>
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
     * removed elements
     */
    protected $remove_elements = array();
    
    
    
    
    /**
     * 
     */
    protected $ordered_elements = array();
    
    /**
     * temp child array
     */
    protected $tmp_children = array();
    
	function __construct($argument) {
		
	}
    
    /**
     * Create a new form
     */
    public function newForm($config, $theme='') {
        // need all form stuff plus theme value
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
     * @param (string) $tabTitle - the title of the tab
     * @param (string) $id - the value for the HTML id attribute 
     * @param (array) $config
     * @return (string) $id - the value for the HTML id attribute
     */
    public function addField($fieldName, $id, $config=array()) {
        // elementType;
        $config['elementType'] = 'field';
        $config['name'] = $fieldName;
        // set some defaults:
        
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
     * add button
     */
    public function addButton($value='') {
        
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
        
        return $this->renderElement($this->associated[0]);// start with the heighest level
        
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
     * @return (string) $processChunkArray
     */
    protected function renderElement($parents) {
        $html = array();
        
        foreach ( $parents as $parent ) {
            if ( isset($this->remove_elements[$parent])) {
                continue;
            }
            $children_html = array();
            $placeholders = array();
            if ( isset($this->associated[$parent]) ) {
                // get the children
                $children_html = $this->renderElement($this->associated[$parent]);
            }
            
            if ( isset($children_html['pre']) ) {
                $placeholders['pre'] = $children_html['pre'];
            }
            if ( isset($children_html['post']) ) {
                $placeholders['post'] = $children_html['post'];
            }
            
            $placeholders = array(
                'prehtml' => '',
                'posthtml' => '',
            );
            $this->modx->getChunk();
        }
        return $html;
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
