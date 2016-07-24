<?php
$__ROOT__ = dirname(dirname(__FILE__));
require_once ($__ROOT__ . '/include/common/global.php');
require_once ($__ROOT__ . '/include/common/abap_db.php');
require_once ($__ROOT__ . '/include/common/abap_ui.php');
require_once ($__ROOT__ . '/include/common/sitemap.php');

$obj_type = 'cus0';
$column_name = 'ACTIVITY';
$list = ABAP_DB_TABLE_CUS0::CUS_IMGACH_Sitemap();

Sitemap4ABAPOType($obj_type, $list, $column_name);