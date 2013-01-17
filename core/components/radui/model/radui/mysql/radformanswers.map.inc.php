<?php
$xpdo_meta_map['RadFormAnswers']= array (
  'package' => 'radui',
  'version' => '1.1',
  'table' => 'rad_form_answers',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'instance_id' => NULL,
    'element_id' => NULL,
    'rank' => 1,
    'int_value' => NULL,
    'value' => NULL,
    'json_value' => NULL,
  ),
  'fieldMeta' => 
  array (
    'instance_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'element_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => true,
      'default' => 1,
    ),
    'int_value' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'json_value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'Page' => 
    array (
      'alias' => 'Page',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'instance_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'element_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Element' => 
    array (
      'class' => 'RadFormElements',
      'local' => 'element_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Instance' => 
    array (
      'class' => 'RadFormInstances',
      'local' => 'instance_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
