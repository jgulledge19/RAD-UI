<?php
$xpdo_meta_map['RadFormElements']= array (
  'package' => 'radui',
  'version' => '1.1',
  'table' => 'rad_form_elements',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'form_id' => NULL,
    'parent' => 0,
    'depth' => 0,
    'path' => '/',
    'rank' => 0,
    'type' => NULL,
    'text' => NULL,
    'description' => NULL,
    'name' => NULL,
    'html_id' => NULL,
    'default_value' => NULL,
    'config' => NULL,
    'classkey' => NULL,
    'table' => NULL,
    'table_field' => NULL,
    'validation_rules' => NULL,
    'active' => 1,
  ),
  'fieldMeta' => 
  array (
    'form_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'depth' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'path' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '/',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
    ),
    'text' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'html_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'default_value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'config' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'classkey' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'table' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'table_field' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'validation_rules' => 
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
  ),
  'indexes' => 
  array (
    'PageCreate' => 
    array (
      'alias' => 'PageCreate',
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
        'parent' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
    'KeySearch' => 
    array (
      'alias' => 'KeySearch',
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
        'name' => 
        array (
          'length' => '10',
          'collation' => 'A',
          'null' => true,
        ),
        'html_id' => 
        array (
          'length' => '10',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Answers' => 
    array (
      'class' => 'RadFormAnswers',
      'local' => 'id',
      'foreign' => 'element_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
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
      'foreign' => 'element_id',
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
