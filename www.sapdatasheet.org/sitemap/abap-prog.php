<?php
$__ROOT__ = dirname(dirname(__FILE__));
require_once ($__ROOT__ . '/include/common/global.php');
require_once ($__ROOT__ . '/include/common/abap_db.php');
require_once ($__ROOT__ . '/include/common/sitemap.php');

$obj_type = 'prog';
$list = ABAP_DB_TABLE_HIER::TADIR_PROG_Sitemap();
$column_name = 'OBJ_NAME';

Sitemap4ABAPOType($obj_type, $list, $column_name);
