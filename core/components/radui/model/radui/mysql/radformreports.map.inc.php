<?php
$xpdo_meta_map['RadFormReports']= array (
  'package' => 'radui',
  'version' => '1.1',
  'table' => 'rad_form_reports',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'form_id' => 0,
    'name' => NULL,
    'description' => NULL,
    'type' => 'List',
    'options' => NULL,
  ),
  'fieldMeta' => 
  array (
    'form_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
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
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => false,
      'default' => 'List',
    ),
    'options' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'Form' => 
    array (
      'alias' => 'Form',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'form_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Events' => 
    array (
      'class' => 'RadFormEvents',
      'local' => 'id',
      'foreign' => 'element_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Filters' => 
    array (
      'class' => 'RadFormFilters',
      'local' => 'id',
      'foreign' => 'report_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Form' => 
    array (
      'class' => 'RadForm',
      'local' => 'form_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
