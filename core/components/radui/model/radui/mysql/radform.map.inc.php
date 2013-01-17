<?php
$xpdo_meta_map['RadForm']= array (
  'package' => 'radui',
  'version' => '1.1',
  'table' => 'rad_form',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'parent_id' => 0,
    'version' => 1,
    'name' => NULL,
    'description' => NULL,
    'use_pages' => 1,
    'type' => 'data-collection',
    'theme' => 'radui',
    'class_key' => 'EasyForms',
    'create_time' => NULL,
    'update_time' => NULL,
    'open_time' => NULL,
    'close_time' => NULL,
    'submit_mulitple' => 1,
    'options' => NULL,
    'active' => 1,
    'lock_edits' => 0,
  ),
  'fieldMeta' => 
  array (
    'parent_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'version' => 
    array (
      'dbtype' => 'float',
      'precision' => '5,2',
      'phptype' => 'float',
      'null' => true,
      'default' => 1,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => false,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'use_pages' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
      'default' => 1,
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => false,
      'default' => 'data-collection',
    ),
    'theme' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
      'default' => 'radui',
    ),
    'class_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
      'default' => 'EasyForms',
    ),
    'create_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'update_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'open_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'close_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'submit_mulitple' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
      'default' => 1,
    ),
    'options' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
      'default' => 1,
    ),
    'lock_edits' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'Instances' => 
    array (
      'class' => 'RadFormInstances',
      'local' => 'id',
      'foreign' => 'form_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Elements' => 
    array (
      'class' => 'RadFormElements',
      'local' => 'id',
      'foreign' => 'form_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Reports' => 
    array (
      'class' => 'RadFormReports',
      'local' => 'id',
      'foreign' => 'form_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
