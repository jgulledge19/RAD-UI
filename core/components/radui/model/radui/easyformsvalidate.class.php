<?php

/**
 * Basic validation class
 * Validates 1 form element at a time
 */
class EasyFormsValidate {
     
    /**
     * @param (String) method - get or post, where the data will be checked from 
     */
    protected $method = 'post';
    
    /**
     * @param (array) $errors - array(name => array( rank => array(error)) )
     * @access protected
     */
    protected $errors = array();
    
    /**
     * @param (Boolean) $has_errors
     * 
     */
    protected $has_errors = FALSE;
    
    /**
     * form-element-name => 
     *          array('remove'=> boolean, 
     *                'upload' => boolean, 
     *                'path' => 'path', 
     *                'size' => int, 
     *                'ext' => 'doc' )
     * @param (Array) $file_data - form-element-name => 
     *          array('remove'=> boolean, 
     *                'upload' => boolean, 
     *                'path' => 'path', 
     *                'size' => int, 
     *                'ext' => 'doc' )
     */
    protected $file_data = array();
    /**
     * @param modx $modx 
     * 
     */
    public $modx;
    
    /**
     * @param (MODX) $modx
     * @param (String) $method - post or get, default is post
     */
    function __construct(&$modx, $method='post') {
        $this->modx = &$modx;
        $this->method = $method;
    }
    /**
     * 
     * @return (Boolean) true then has errors false does not
     * 
     */
    public function hasErrors()
    {
        return $this->has_errors;
    }
    /**
     * Get the error messages
     * @param (String) optional - the form element name
     * @return (Mixed) if no form element name provided then the complete error array as name=>error
     *      if form element name is provided then the error will be returned if it exists else NULL 
     */
    public function getErrors($name='')
    {
        if ( !empty($name) ) {
            if ( isset($this->errors[$name]) ) {
                return $this->errors[$name];
            } else {
                return null;
            }
        }
        return $this->errors;
    }
    
    /** 
     * validate user input
     * @param (Mixed) $input: variable to be validated
     * @param (String) $type - radio, chechbox, file, text, textarea, ect. 
     * @param (Array) $config: array of HTML5 attributes=>value
     * 
     * @TODO Use or extend xPDO Validation
     * @return (Boolean) true on success
     */
    public function validate($name, $type, $config=array() ) { //  $type, $len = null, $chars = null) {
        $value = null;
        if ( isset($_POST[$name]) ) {
            $value = $_POST[$name];
        }
        $required = false;
        if ( isset($config['required']) && (boolean) $config['required'] === true ) {
            $required = true;
        }
        if ( $type == 'file' ) {
            if ( isset($_POST[$name.'Remove']) && $_POST[$name.'Remove'] == 'Y' ) {
                // add to the list:
                $this->file_data[$name] = array(
                        'remove' => true
                    );
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->validate] Add to remove Name: '.$name.'Remove ' );
            }
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->validate] Name: '.$name.' Line: '.__LINE__);
            return $this->validateFile($name, $config['allow_ext'], $config['size_limit'], $required);
        }
        
        if ( $required  && empty($value) ) {
            // set error message:
            $this->errors[$name] = 'Required';
            $this->has_errors = true;
            return FALSE;
        } else if ( empty($value) ) {
            return TRUE;
        }
        /**
         * maxlength
         * HTML5
         *  pattern
         *  required
         * - numbers only:
         *  min
         *  max
         *  
         */
        // 
        if ( isset($config['pattern']) && !empty($config['pattern']) ) {
            // http://stackoverflow.com/questions/10993451/filter-var-using-filter-validate-regexp
            // set error message: 
            // $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyForms()->validate] Pattern: '.$config['pattern'].' => '.$name );
            if ( filter_var($value, FILTER_VALIDATE_REGEXP, array("options" => array( "regexp" => $config['pattern'] ) ) ) ) {
                $this->errors[$name] = 'Pattern does not match';
                $this->has_errors = true;
                return FALSE;
            }
        }
        
        if ( isset($config['maxlength']) && is_numeric($config['maxlength']) ) {
            if ( strlen($value) > $config['maxlength']) {
                $this->errors[$name] = 'Exceeds maxlength';
                $this->has_errors = true;
                return FALSE;
            }
        }
        // numbers only
        if ( isset($config['min']) && $config['min'] >= 0) {
            if ( $value < $config['min']) {
                $this->errors[$name] = 'Value to small';
                $this->has_errors = true;
                return FALSE;
            }
        }
        if ( isset($config['max']) && $config['max'] > 0 ) {
            if ( $value > $config['max']) {
                $this->errors[$name] = 'Exceeds max value';
                $this->has_errors = true;
                return FALSE;
            }
        }
        if ( isset($config['type']) ) {
            switch ($config['type']) {
                case 'alpha':
                    if (!ctype_alpha($value)) { //  [A-Za-z]
                        $this->errors[$name] = 'Only alphabetic characters';
                        $this->has_errors = true;
                        return FALSE;
                    }
                    break;
                case 'number': // INT
                    // no break
                    // @TODO make this match jQuery h5validation
                case 'integer':
                    if (!ctype_digit($value)) {
                        $this->errors[$name] = 'Not a valid whole number';
                        $this->has_errors = true;
                        return FALSE;
                    }
                    break;
                case 'alphaNumeric':
                    if (!ctype_alnum($value)) {
                        $this->errors[$name] = 'Only alphanumeric characters';
                        $this->has_errors = true;
                        return FALSE;
                    }
                    break;
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$name] = 'Invalid email address';
                        $this->has_errors = true;
                        return FALSE;
                    }
                    break;
                case 'url':
                    if(!filter_var($value, FILTER_VALIDATE_URL)) {
                        $this->errors[$name] = 'Invalid URL';
                        $this->has_errors = true;
                        return FALSE;
                    }
                    break;
            }
        }
        return TRUE;
    }
     
    /**
     * FILES
     * 1. save file name, path, upload time, size to Answers table
     * 2. validation_rules - file_type => array(), file_size => INT, file_path => String-overrides default set in the form options 
     * 3. If fail to commit then need to flush any uploaded files
     * 3. show file name with link to download and options to remove and override
     */
    
    
    /**
     * validate the file
     * @param (String) $name - the name of the HTML input field
     * @param (Array) $allow_ext - array of allowable extensions
     * @param (Int) $size_limit - the size limit in kb
     * @param (Boolean) $required
     * 
     * @return (Boolean) 
     */
    public function validateFile($name, $allow_ext, $size_limit, $required) {
        // @TODO make this lexicons
        $upload_errors = array(
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
        );
        if ( !isset($_FILES[$name]) ) {
            if ( $required ) {
                $this->errors[$name] = 'Required';
                $this->has_errors = true;
                return false;
            }
            return true;
        }
        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->validateFile] Name: '.$name.' Line: '.__LINE__);
        if ( $_FILES[$name]['error'] == UPLOAD_ERR_NO_FILE  ) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->validateFile] Name: '.$name.' NO FILE Line: '.__LINE__);
            if ( $required ) {
                $this->errors[$name] = 'Required';
                $this->has_errors = true;
                return false;
            }
            return true;
        } elseif ( $_FILES[$name]['error'] !== UPLOAD_ERR_OK) { 
            // @TODO it has an error, one of the above now set it:
            $this->errors[$name] = $upload_errors[$_FILES[$name]['error']].' '.$_FILES[$name]['error'];
            $this->has_errors = true;
            return false;
        }
        $file_ext = substr($_FILES[$name]['name'], strripos($_FILES[$name]['name'], '.')+1 );
        if( !in_array($file_ext, $allow_ext)  ) {
            if ( $file_ext == 'rtf' && $_FILES[$name]['type'] == 'application/msword' ){
                // do nothing!
            } else {
                $this->errors[$name] = 'Incorrect file type';
                $this->has_errors = true;
                return false;
            }
        }
        
        // size
        $limit = $size_limit*1024;// convert to bytes
        if ( $_FILES[$name]['size'] > $limit) {
            if ( $limit > 1048576 ){ // 1 mb
                $limit_txt = number_format(($limit/1048576),2).' Mb';
            } else {
                $limit_txt = number_format(($limit/1024),2).' Kb';
            }
            $this->errors[$name] = 'File size is to large, it must be smaller than: '.$limit_txt;
            $this->has_errors = true;
            return false;
        }
        // add to the list:
        $this->file_data[$name] = array(
                'upload' => true, 
                //'path' => 'path', 
                'size' => $_FILES[$name]['size'], 
                'ext' => $file_ext
            );
        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->validateFile] Name: '.$name.' File is set to upload Line: '.__LINE__);
        return true;
    }
    
    
    /**
     * 1. files are uploaded on validate if no errors and remove files are stored in array
     * 2. On error unlink uploaded files or restore deleted 
     * 3. On no error delete marked files
     * 
     * will unlink any files that are marked to be deleted if there are errors, or restore saved version
     * on no errors will complete upload of files
     */
    public function completeFiles($proceed=true)
    {
        foreach ( $this->file_data as $name => $data ) {
            if ( $proceed ) {
                if ( isset($data['remove']) && $data['remove'] ) {
                    unlink($data['path']);
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->completeFiles] Remove Name: '.$name);
                }
            } else {
                if ( isset($data['upload']) && $data['upload'] ) {
                    unlink($data['path']);
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->completeFiles] Undue Name: '.$name);
                }
            }
        }
    }
    /**
     * Return true if there is an associated file to delete
     * @param (String) $name - the form element name
     * @return (Boolean) true if form element has a file to delete
     */
    public function isRemoveFile($name)
    {
        if ( isset($this->file_data[$name]['remove']) ) {
            return (boolean) $this->file_data[$name]['remove'];
        }
        return false;
    }
    
    
    /**
     * Return true if there is an associated file to upload
     * @param (String) $name - the form element name
     * @return (Boolean) true if form element has a file to upload/override
     */
    public function isProcessFile($name)
    {
        if ( isset($this->file_data[$name]['upload']) ) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->isProcessFile] Name: '.$name.' Line: '.__LINE__);
            return (boolean) $this->file_data[$name]['upload'];
        }
        return false;
        
    }
    /**
     * @param (String) $name - the form element name
     * @param (String) $destination - the directory path of where to put the uploaded file
     * @param (String) $new_name - the new name of the file, leave off the extension
     * @param (String) $existing_file - the complete path of any existing file to be replaced
     * 
     * @return (Array) $file_data - the uploaded file data
     */
    public function uploadFile($name, $destination, $new_name, $existing_file=null) 
    {
        // mark existing file to be deleted
        if ( !empty($existing_file) && is_file($existing_file) ) {
            $this->file_data[$name.'Override'] = array(
                'remove' => true, 
                'path' => $existing_file,
            );
        }
    
        $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->uploadFile] Name: '.$name.' Dest: '.$destination.' New name: '.$new_name);
        // upload file:
        $org_file = $_FILES[$name]['tmp_name'];
        $new_file = $destination.$new_name.'.'.$this->file_data[$name]['ext'];
        if ( move_uploaded_file ($org_file, $new_file) ) {
            return array(
                    'ext' => $this->file_data[$name]['ext'],
                    'name' => $new_name.'.'.$this->file_data[$name]['ext'],
                    'path' => $new_file,
                    'size' => $this->file_data[$name]['size'],
                );
        } else {
            // permissions error:
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[RAD-UI->EasyFormsValidate()->uploadFile] ERROR check permissions for folder: '.$destination);
        }
        return false;
    }
    /**
     * @param (String) $name - the form element name
     * @param (String) $destination - the directory path of where to put the uploaded file
     * @param (String) $new_name - the new name of the file, leave off the extension
     * @param (String) $existing_file - the complete path of any existing file to be replaced
     * 
     * @return (Array) $file_data - the uploaded file data
     */
    public function removeFile($name, $existing_file=null) 
    {
        // mark existing file to be deleted
        if ( !empty($existing_file) && is_file($existing_file) ) {
            $this->file_data[$name] = array(
                'remove' => true, 
                'path' => $existing_file,
            );
            return true;
        }
        return false;
    }
    
}
 
