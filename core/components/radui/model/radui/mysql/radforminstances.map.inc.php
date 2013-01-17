<?php
$xpdo_meta_map['RadFormInstances']= array (
  'package' => 'radui',
  'version' => '1.1',
  'table' => 'rad_form_instances',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'form_id' => 0,
    'user_id' => 0,
    'session' => NULL,
    'foriegn_id' => 0,
    'sub_type' => NULL,
    'form_status' => 'Created',
    'crm_id' => 0,
    'possible_crm_id' => 0,
    'crm_import_status' => 'Incomplete',
    'start_time' => NULL,
    'last_time' => NULL,
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
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'session' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'foriegn_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'sub_type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
    ),
    'form_status' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Created\',\'Incomplete\',\'Complete\',\'Void\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'Created',
    ),
    'crm_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'possible_crm_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'crm_import_status' => 
    array (
      'dbtype' => 'set',
      'precision' => '\'Complete\',\'Incomplete\',\'Partial\',\'Processing\',\'Void\'',
      'phptype' => 'string',
      'null' => false,
      'default' => 'Incomplete',
    ),
    'start_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'last_time' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'User' => 
    array (
      'alias' => 'User',
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
        'user_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'Report' => 
    array (
      'alias' => 'Report',
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
        'sub_type' => 
        array (
          'length' => '10',
          'collation' => 'A',
          'null' => true,
        ),
        'form_status' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'start_time' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
        'last_time' => 
        array (
          'length' => '',
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
      'foreign' => 'instance_id',
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
