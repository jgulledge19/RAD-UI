<?php

/**
 * 
 * Dynamic Grid

Description
Dynamic Grid uses the jQuery SlickGrid: https://github.com/mleibman/SlickGrid/ plugin to generate a grid.
Easily connect your custom data to make an editable grid in minutes!

http://drupal.org/project/slickgrid

OPTIONS:

GridID (string) the element ID of where the grid will be generated
URL (string) the data URL
SendToHead (bool), default = true (false will echo where called)
Columns (string), this is in the form a JSON object
    example: {name:{}}

Pagiagion (bool)
BeginLimit (int)


Fixed columns: http://stackoverflow.com/questions/8151727/slickgrid-with-first-column-fixed
Save cells: http://stackoverflow.com/questions/4210120/getting-data-from-cells-in-slickgrid
Add/Remove columns: http://stackoverflow.com/questions/4463176/can-i-add-a-column-to-slickgrid-on-on-the-fly
Select options: http://stackoverflow.com/questions/3211956/slickgrid-select-editor
Add class to a cell: http://stackoverflow.com/questions/2742166/how-to-add-a-class-to-a-cell-in-slickgrid 
Excel: http://stackoverflow.com/questions/4228091/excel-like-behavior-with-slickgrid
New Row ontop: http://stackoverflow.com/questions/7567518/how-to-add-new-row-on-top-in-slickgrid


============================================
Concept

[[!dynamicgrid?
  &customJSFile=`Path to JS file`
  &customJSChunk=`ChunkName`
  &gridProject=`slickgrid`
  &dataMethod=`jsonp` - jsonmodel, ajax, object
    &object=``// the function to create the object data 
  &useDataView=`true`
  &gridID=`mygrid`
  
  &baseUrl=`[[~13]]`
    &newObjectUrl=``// if different than the baseUrl
    &saveObjectUrl=``
    &removeObjectUrl=``
  &urlParams=`[{name: 'id', value: 13}, {name: 'event_id', value: eventID},{name: 'church_id', value: churchID}]`
  &action=`getCollection`
  &limit=`5000`
  &sortCol=`last_name`
  &sortDir=`DESC`
  &columns=`[{id: "actionLinks", name: "Edit", field: "actionLinks", formatter: actionLinkFormatter, width: 60},
        {
            id: "submit_time", 
            name: "Date", 
            field: "submit_time",
            sortable: true, 
            width: 80
        },{
            id: "first_name", 
            name: "First name", 
            field: "first_name", 
            width: 90, 
            cssClass: "cell-title", 
            editor: Slick.Editors.Text, 
            validator: requiredFieldValidator,
            sortable: true,
            showOnLoad: true
                modalEditor: Slick.Editors.Text - optional override?
        }]`
  &options=`{
        editable: true,
        enableAddRow: false,
        enableCellNavigation: true,
        asyncEditorLoading: false,
        autoEdit: true,// Cell will not automatically go into edit mode when selected.
        enableColumnReorder: true,
        syncColumnCellResize: true
        /*
        rowHeight: 64,
        enableRowReordering,
        leaveSpaceForNewRows: true,
        * /
    }`
]]

Create JSON API requirements
Required JSON Send form client:
    action => 'newObject', 
            'save',
            'saveObject',
            'saveCollection',
            'getObject',
            'getCollection', 
            'remove'
            'getPermissions'
Standard Options:
    'start' => the limit offset
    'limit' => the limit for the SQL results
    'sort' => the column/feild to sort by
    'dir' => ASC or DESC
    
Required JSON returned from server:
    status => 'success','failed' or 'deined' 
    message => A message describing the status
            
Standard Options:
    'data' => the object data 
    'permissions' => {'save' => true/false, ... }
    
 
 * 
 */
require_once 'datagrid.class.php';
/**
 * SlickGrid
 */
class SlickGrid extends DataGrid {
	/**
     * @param (object) $columnData
     */
    protected $columnData;
    /**
     * @param (string) $gridID - the HTML ID for the grid
     */
    protected $gridID;
    /**
     * @param (Array) $scriptProperties
     */
    public $scriptProperties;
    /**
     * @param (boolean) $debug
     * 
     */
    protected $debug = true;
    /**
     * @param modx $modx 
     * 
     */
    public $modx;
    
    /**
     * @param $modx
     * @param $config - array() of config options like array('corePath'=>'Path.../core/)
     */
	function __construct(modX &$modx, $scriptProperties) {
		$this->modx =& $modx;
        $this->scriptProperties = $scriptProperties;
        
	}
    /**
     * Create/Attach the CSS
     */
    
    /**
     * Build the JS and any Add, Edit and Delete forms
     *  (string) $columns
     * $columns is a PHP string but in JS object format like so:
     * {
     *      "columnName": { // the grid options:
                "id": "status", 
                "name": "Status", 
                "field": "status",
                "editor": Slick.Editors.BasicSelect,
                "selectOptions": {array:[{'n': 'Yes', 'v': 'Yes'},{'n': 'No', 'v': 'No'},{'n': 'Cancel', 'v': 'Cancel'},{'n': 'Duplicate', 'v': 'Duplicate'}]},
                "sortable": "true", 
                "width": "70",
     *          "onLoadVisible": "false",// default is true
     * // this would be the forms
     *          "onAddVisible": "false", 
     *          "onEditVisible": "false",
     *          "holderCSSClass": "", (medium spaceRight clear ect..)
     *          "CSSClass": "" (radio)
     * // Set to hav a search option
     *          "searchType": "text", (default:none; others: select, checkbox, radio)
     *          "searchValues": {"Name":"Value", "Name":"Value"} (only for select, radio and checkbox - others selectOptions and {"ajax":{"url","params"}} )
     *     }, ...
     * }
     * 
     *          (useModalonEdit:false - default)
     *          (useModalonDelete:false - default)
     *          (useModalonAdd:false - default)
     * editor, validator or methods - no quotes for output
     * 
     * formatter: actionLinkFormatter
     * 
     * types - string, method, object, boolean
     * 
     * Note for PHP: all name and value must be enclosed in double quotes
     */
    public function build() {
        $columns = $this->modx->getOption('columns', $this->scriptProperties, '{}');
        
        $this->columnData = json_decode($columns);
        
        // build all columns
        // build visible columns
        $json = '';
        if ( $this->debug ) {
            $json .= "\r\n    ";// 4 spaces
        }
        $columnCount = 0;
        if ( !empty($this->columnData)) {
            foreach ( $this->columnData as $columnName=>$row) {
                if ( $columnCount > 0 ) {
                    $json .= ', ';
                }
                $columnCount++;
                $json .= '{';
                $c = 0;
                foreach( $row as $name=>$value ) {
                    if ( $this->debug ) {
                        $json .= "\r\n        ";// 8 spaces
                    }
                    if ( $c > 0 ) {
                        $json .= ',';
                    }
                    $c++;
                    $json .= '"'.$name.'": ';
                    // format the value correctly:
                    if ( is_object($value) || is_array($value) ) {
                        // build the select options:
                        if ( $name == 'selectOptions' ) {
                            // {array:[ {n: Name, v: value}, {n: Name2, v: value2 }  ]}
                            // {Name:value, name2:value2,...}
                            $json .= '{array:[ ';
                            $selCount = 0;
                            foreach($value as $n=>$v) {
                                if ( $selCount > 0 ) {
                                    $json .= ',';
                                }
                                $json .= '{n: "'.$n.'", v: "'.$v.'"}';
                                $selCount++;
                            }
                            $json .= ']}';
                        } else {
                            $json .= json_encode($value);
                        }
                    } else if (is_numeric($value)) {
                        $json .= $value;
                    } else if (is_bool($value)) {
                        $json .= $value;
                    } else {
                        switch ($name) {
                            // these are methods/functions
                            case 'editor':
                            case 'validator':
                            case 'formatter':
                                $json .= $value;
                                break;
                            // the defaults are just strings:
                            default:
                                $json .= '"'.$value.'"';
                                break;
                        }
                    }
                }
                if ( $this->debug ) {
                    $json .= "\r\n    ";// 4 spaces
                }
                $json .= '}';
                
            }
        } else {
            return '<h3>The columns property for the snippet is invalid JSON</h3>';
        }
        
        $this->gridID = $this->modx->getOption('gridID', $this->scriptProperties, 'mySlickGrid');
        
        // build add item
        // build edit item
        // build search options
        
        // permissions?
        // $this->buildForms();
        $dataUrl = $this->modx->getOption('dataUrl', $this->scriptProperties, '[[~1]]');
        $placeholders = array(
            'dataColumns' => $json,
            'loadJQuery' => $this->modx->getOption('loadJQuery', $this->scriptProperties, 'true'),
            'gridID' => $this->gridID,
            'addForm' => $this->buildForm('Add'),// @TODO allow override with custom chunk
            'editForm' => $this->buildForm('Edit'),
            'searchForm' => $this->buildForm('Search'),
            'deleteForm' => $this->modx->getOption('deleteForm', $this->scriptProperties, ''),
            'importForm' => $this->modx->getOption('importForm', $this->scriptProperties, ''),
            'dataUrl' => $dataUrl,
            'addUrl' => $this->modx->getOption('addUrl', $this->scriptProperties, $dataUrl),
            'editUrl' => $this->modx->getOption('editUrl', $this->scriptProperties, $dataUrl),
            'deleteUrl' => $this->modx->getOption('deleteUrl', $this->scriptProperties, $dataUrl),
            'importUrl' => $this->modx->getOption('importUrl', $this->scriptProperties, $dataUrl),
        );
        
        // &toArray - list of availabe properties and placeholders?
        $cssChunk = $this->modx->getOption('tplCSS', $this->scriptProperties, 'SlickGridCss');
        $jsChunk = $this->modx->getOption('tplJS', $this->scriptProperties, 'SlickGridJs');
        //$jsChunk = $this->modx->getOption('js', $scriptProperties, 'SlickGrid_JS');
        $this->modx->regClientStartupHTMLBlock(
            $this->modx->getChunk($cssChunk, $placeholders).
            $this->modx->getChunk($jsChunk, $placeholders)
        );
        
        $gridChunk = $this->modx->getOption('tplGrid', $this->scriptProperties, 'SlickGridGrid');
        // get the html grid
        
        return $this->modx->getChunk($gridChunk, $placeholders);
    }
    
    /**
     * Build the Add, Edit and Delete forms:
     * @param (String) $formType
     */
    public function buildForm($formType) {
        $form = '';
        $columnCount = 0;
        if ( !empty($this->columnData)) {
            foreach ( $this->columnData as $columnName=>$row) {
                $columnCount++;
                switch ($formType) {
                    case 'Add':
                        // print_r($row);
                        if ( isset($row->onAddVisible) && ( $row->onAddVisible || $row->onAddVisible == 'true') ) {
                            // make the form element
                            $form .= $this->formElement($row, FALSE, $formType);
                        }
                        break;
                    case 'Search':
                        if ( isset($row->search) && ( $row->search || $row->search == 'true') ) {
                            // make the form element
                            $form .= $this->formElement($row, TRUE, $formType);
                        }
                        break;
                    case 'Edit':
                    default:
                        if ( isset($row->onEditVisible) && ( $row->onEditVisible || $row->onEditVisible == 'true') ) {
                            // make the form element
                            $form .= $this->formElement($row, FALSE, $formType);
                        }
                        break;
                }
            }
        }
        //echo 'TEST...'.$form;
        return $form;
    }
    /**
     * Map the editor type to HTML form type
     * Put all slickGrid options here
     * @param (Array) $data the form element data (name=>value)
     * @param (boolean) show empty first option for selects
     * @param (STring) $formType
     */
    public function formElement($data, $firstOption=FALSE, $formType='Add') {
        if (!isset($data->editor) ) {
            //return '';
            $data->editor = 'hidden';//??
        }
        // input
        // buttons
        // textarea
        // select
        /**
         * "id": "status", 
                "name": "Status", 
                "field": "status",
                "editor": Slick.Editors.BasicSelect,
                "selectOptions": {array:[{'n': 'Yes', 'v': 'Yes'},{'n': 'No', 'v': 'No'},{'n': 'Cancel', 'v': 'Cancel'},{'n': 'Duplicate', 'v': 'Duplicate'}]},
                "sortable": "true", 
                "width": "70",
     *          "onLoadVisible": "false",// default is true
     * // this would be the forms
     *          "onAddVisible": "false",
         *      "require" : "true" // default is false 
     *          "onEditVisible": "false",
     *          "holderCSSClass": "", (medium spaceRight clear ect..)
     *          "CSSClass": "" (radio)
     * // Set to hav a search option
     *          "searchType": "text", (default:none; others: select, checkbox, radio)
     *          "searchValues": {"Name":"Value", "Name":"Value"} (only for select, radio and checkbox - others selectOptions and {"ajax":{"url","params"}} )
     *     }, ...
         */
        $type = $jsclass = '';
        $isRequired = (isset($data->require) && ( $data->require || $data->require == 'true') ? TRUE : FALSE);
        $element = '';
        $holderCss = ( isset($data->holderCSSClass) ? $data->holderCSSClass : '');
        $cssClass = ( isset($data->CSSClass) ? $data->CSSClass : '');
        switch ($data->editor) {
            // text area
            case 'Slick.Editors.LongText': 
                $type = 'textarea';
                break;
            
            // select 
            case 'Slick.Editors.YesNoSelect': 
                $type = 'select';
                break;
            case 'Slick.Editors.BasicSelect': // select drop down 
                $type = 'select';
                break;
                
            // Input types:
            case 'hidden': 
                $type = 'hidden';
                break;
            case 'Slick.Editors.Text': 
                $type = 'text';
                break;
            case 'Slick.Editors.Checkbox': 
                $type = 'checkbox';
                break;
            case 'Slick.Editors.Integer': 
                $type = 'text';
                break;
            case 'Slick.Editors.Date': 
                $type = 'text';
                break;
            case 'Slick.Editors.PercentComplete': 
                // jquery percentage?
                $type = 'text';
                break;
            default: 
                
                $type = 'text';
                break;
        }
        // ----------
        switch ($type) {
            case 'select':
                $element = '
        <li class="'.$holderCss.'">
            <label for="'.$this->gridID.$formType.'_'.$data->field.'">'.$data->name.'</label>
            <select name="'.$data->field.'" class="'.$cssClass.' '.$jsclass.'" id="'.$this->gridID.$formType.'_'.$data->field.'" '
                .( $isRequired ? 'require' : '').' title="Error message" placeholder="Display message" data-editor="'.$data->editor.'">
				'.($firstOption ? '<option value="" >Select</option>' : '' ).'
            </select>
        </li>';
                
                break;
            case 'textarea':
                $element = '
        <li class="'.$holderCss.'">
            <label for="'.$this->gridID.$formType.'_'.$data->field.'">'.$data->name.'</label>
            <textarea name="'.$data->field.'" class="'.$cssClass.' '.$jsclass.'" id="'.$this->gridID.$formType.'_'.$data->field.'" '
                .( $isRequired ? 'require' : '').' title="Error message" placeholder="Display message"></textarea>
        </li>';
                
                break;
                
            case 'checkbox':
            case 'radio':
                $element = '
        <li class="'.$holderCss.'">
            <input type="'.$type.'" name="'.$data->field.'" value="" class="'.$cssClass.' '.$jsclass.'" id="'.$this->gridID.$formType.'_'.$data->field.'" '
                .( $isRequired ? 'require' : '').' title="Error message" placeholder="Display message" />
            <label for="'.$this->gridID.$formType.'_'.$data->field.'">'.$data->name.'</label>
        </li>';
                break;
            case 'hidden':
                $element = '
            <input type="'.$type.'" name="'.$data->field.'" value="" class="'.$cssClass.' '.$jsclass.'" id="'.$this->gridID.$formType.'_'.$data->field.'" />';
                
            case 'text':
            case 'input':
            default:
                $element = '
        <li class="'.$holderCss.'">
            <label for="'.$this->gridID.$formType.'_'.$data->field.'">'.$data->name.'</label>
            <input type="'.$type.'" name="'.$data->field.'" data-editor="'.$data->editor.'" value="" class="'.$cssClass.' '.$jsclass.'" id="'.$this->gridID.$formType.'_'.$data->field.'" '.( $isRequired ? 'require' : '').' />
        </li>';
                break;
        }
        return $element;
    }
}