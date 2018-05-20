<?php
// Script Generator for Sheets

$__ROOT__ = dirname(dirname(dirname(__FILE__)));
require_once ($__ROOT__ . '/include/common/global.php');
require_once ($__ROOT__ . '/include/common/abap_db.php');
require_once ($__ROOT__ . '/include/common/abap_ui.php');
require_once ($__ROOT__ . '/include/common/download.php');
require_once ($__ROOT__ . '/include/site/site_ui.php');

$module_l1_list = ABAPANA_DB_TABLE::ABAPTRAN_ANALYTICS_PS_POSID_L1();
foreach ($module_l1_list as $item) {
    echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_MODULE, $item['PS_POSID_L1'], DOWNLOAD::FORMAT_CSV);
    echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_MODULE, $item['PS_POSID_L1'], DOWNLOAD::FORMAT_XLS);
    echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_MODULE, $item['PS_POSID_L1'], DOWNLOAD::FORMAT_XLSX);
}

$component_list = ABAPANA_DB_TABLE::ABAPTRAN_ANALYTICS_SOFTCOMP();
foreach ($component_list as $item) {
    echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_COMPONENT, $item['SOFTCOMP'], DOWNLOAD::FORMAT_CSV);
    echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_COMPONENT, $item['SOFTCOMP'], DOWNLOAD::FORMAT_XLS);
    echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_COMPONENT, $item['SOFTCOMP'], DOWNLOAD::FORMAT_XLSX);
}

$name_list = ABAPANA_DB_TABLE::ABAPTRAN_ANALYTICS_NAME_LEFT2();
foreach ($name_list as $item) {
    if ($item['COUNT'] >= ABAP_UI_TCODES_Navigation::DOWNLOAD_NAME_ROW_MIN) {
        echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_NAME, $item['TCODEPREFIX'], DOWNLOAD::FORMAT_CSV);
        echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_NAME, $item['TCODEPREFIX'], DOWNLOAD::FORMAT_XLS);
        echo_cmd(ABAP_UI_TCODES_Navigation::SHEET_PARAMETER_FILTER_NAME, $item['TCODEPREFIX'], DOWNLOAD::FORMAT_XLSX);
    }
}

function echo_cmd($filter, $id, $format) {
    $filename = ABAP_UI_TCODES_Navigation::SheetName($filter, $id, $format);
    echo 'php download.php ' . $filter . ' ' . $id . ' ' . $format . ' >> ' . ABAP_UI_TCODES_Navigation::DistPath($filename);
    echo "\r\n";
}