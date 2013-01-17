<?php
$xpdo_meta_map['RadFormEvents']= array (
  'package' => 'radui',
  'version' => '1.1',
  'table' => 'rad_form_events',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'element_id' => 0,
    'snippet_id' => 0,
    'snippet_name' => NULL,
    'name' => NULL,
    'description' => NULL,
    'type' => 'Event',
    'conditions' => NULL,
  ),
  'fieldMeta' => 
  array (
    'element_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'snippet_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'snippet_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
      'phptype' => 'string',
      'null' => true,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
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
      'dbtype' => 'set',
      'precision' => '\'Event\',\'Logic\',\'Render\',\'Save\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'Event',
    ),
    'conditions' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'Search' => 
    array (
      'alias' => 'Search',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'id' => 
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
        'name' => 
        array (
          'length' => '10',
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
  ),
);
