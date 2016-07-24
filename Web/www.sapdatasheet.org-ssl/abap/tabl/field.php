<!DOCTYPE html>
<!-- DDIC Table Field -->
<?php
$__ROOT__ = dirname(dirname(dirname(__FILE__)));
require_once ($__ROOT__ . '/include/common/global.php');
require_once ($__ROOT__ . '/include/common/abap_db.php');
require_once ($__ROOT__ . '/include/common/abap_ui.php');
require_once ($__ROOT__ . '/include/common/schemaorg.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

if (!isset($Table)) {
    $Table = filter_input(INPUT_GET, 'table');
}
if (!isset($Field)) {
    $Field = strtoupper(filter_input(INPUT_GET, 'field'));
}
if (!isset($Position)) {
    $Position = strtoupper(filter_input(INPUT_GET, 'position'));
}

if (empty($Table) || (strlen(trim($Field)) + strlen(trim($Position)) == 0)) {
    ABAP_UI_TOOL::Redirect404();
}

if (strlen(trim($Field)) > 0) {
    $dd03l = ABAP_DB_TABLE_TABL::DD03L(strtoupper($Table), strtoupper($Field));
    $json_ld_name = strtoupper($Table) . '-' . strtoupper($Field);
} else if (strlen(trim($Position)) > 0) {
    $dd03l = ABAP_DB_TABLE_TABL::DD03L_POSITION(strtoupper($Table), strtoupper($Position));
    $json_ld_name = strtoupper($Table) . '-' . $Position;
}
if (empty($dd03l['FIELDNAME'])) {
    ABAP_UI_TOOL::Redirect404();
}

$dd02l = ABAP_DB_TABLE_TABL::DD02L(strtoupper($Table));
$dd02l_desc = ABAP_DB_TABLE_TABL::DD02T($dd02l['TABNAME']);
$dd02l_tabclass_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_TABLE_TABL::DD02L_TABCLASS_DOMAIN, $dd02l['TABCLASS']);

$dd03l_fieldname_desc = htmlentities(ABAP_UI_TOOL::GetTablFieldDesc($dd03l['PRECFIELD'], $dd03l['ROLLNAME']));
$dd03l_checktable_desc = ABAP_DB_TABLE_TABL::DD02T($dd03l['CHECKTABLE']);
$dd03l_inttype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_INTTYPE, $dd03l['INTTYPE']);
$dd03l_reftable_desc = ABAP_DB_TABLE_TABL::DD02T($dd03l['REFTABLE']);
$dd03l_precfield_desc = ABAP_DB_TABLE_TABL::DD02T($dd03l['PRECFIELD']);
$dd03l_notnull_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DD03L_NOTNULL, $dd03l['NOTNULL']);
$dd03l_datatype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DATATYPE, $dd03l['DATATYPE']);
$dd03l_domname_desc = ABAP_DB_TABLE_DOMA::DD01T($dd03l['DOMNAME']);
$dd03l_shlporigin_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DD03L_SHLPORIGIN, $dd03l['SHLPORIGIN']);
$dd03l_tabletype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DD03L_TABLETYPE, $dd03l['TABLETYPE']);
$dd03l_comptype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DD03L_COMPTYPE, $dd03l['COMPTYPE']);
$dd03l_reftype_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DD03L_REFTYPE, $dd03l['REFTYPE']);
$dd03l_languflag_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_DD03L_LANGUFLAG, $dd03l['LANGUFLAG']);

$dd17s_list = ABAP_DB_TABLE_TABL::DD17S_FIELDNAME($dd02l['TABNAME'], $dd03l['FIELDNAME']);

$wul_counter_list = ABAPANA_DB_TABLE::WULCOUNTER_List(GLOBAL_ABAP_OTYPE::DTF_NAME , $dd02l['TABNAME'], $dd03l['FIELDNAME']);

$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::TABL_NAME, $dd02l['TABNAME']);
$title_desc = (empty($dd03l_fieldname_desc)) ? '' : ' (' . $dd03l_fieldname_desc . ')';
$GLOBALS['TITLE_TEXT'] = 'SAP ABAP Table Field ' . $dd02l['TABNAME'] . '-' . $dd03l['FIELDNAME'] . $title_desc;

$json_ld = new \OrgSchema\Thing();
$json_ld->name = $json_ld_name;
$json_ld->alternateName = $dd03l_fieldname_desc;
$json_ld->description = $GLOBALS['TITLE_TEXT'];
$json_ld->image = GLOBAL_ABAP_ICON::getIconURL(GLOBAL_ABAP_ICON::OTYPE_DTF, TRUE);
$json_ld->url = ABAP_UI_DS_Navigation::GetObjectURL(GLOBAL_ABAP_OTYPE::TABL_NAME, $json_ld->name);
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/abap.css" type="text/css" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="keywords" content="SAP,Table Field,<?php echo $dd02l['TABNAME'] . '-' . $dd03l['FIELDNAME'] ?>" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC; ?>" />
        <meta name="author" content="SAP Datasheet" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <script type="application/ld+json"><?php echo $json_ld->toJson() ?></script>
    </head>
    <body>

        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <!-- Left -->
        <div class="left">
            <h5>&nbsp;</h5>
            <h5>Object Hierarchy</h5>
            <table class="content_obj">
                <tbody>
                    <tr><td class="left_attribute">Software Component</td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td></tr>
                    <tr><td class="left_attribute"> Application Component ID</td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;</td></tr>
                    <tr><td class="left_attribute"> Package </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T) ?></td></tr>
                    <tr><td class="left_attribute"> Object type </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTABL() ?>
                            <a href="/abap/tabl/"><?php echo htmlentities($dd02l_tabclass_desc) ?></a></td></tr>
                    <tr><td class="left_attribute"> Object name </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTABL() ?>
                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd02l['TABNAME'], $dd02l['TABNAME']) ?></td></tr>
                    <tr><td class="left_attribute"> Field </td></tr>
                    <tr><td class="left_value"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDTF() ?>
                            <a href="#"><?php echo $dd03l['FIELDNAME'] ?></a> </td></tr>
                </tbody>
            </table>

            <?php require $__ROOT__ . '/include/abap_oname_wul.php' ?>
            <?php require $__ROOT__ . '/include/abap_relatedlinks.php' ?>
            <h5>&nbsp;</h5>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Content Navigator -->
            <div class="content_navi">
                <a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home page</a> &gt; 
                <a href="/abap/">ABAP Object</a> &gt;
                <a href="/abap/tabl/"><?php echo htmlentities($dd02l_tabclass_desc) ?></a> &gt; 
                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd02l['TABNAME'], $dd02l_desc) ?>-
                <a href="#"><?php echo $dd03l['FIELDNAME'] ?></a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__ROOT__ . '/include/google/adsense-content-top.html' ?>
                </div>

                <?php require $__ROOT__ . '/include/abap_oname_hier.php' ?>

                <h4> Basic Data </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Table </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd02l['TABNAME'], $dd02l_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd02l_desc) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Field </td>
                            <td class="field"> <a href="#"><?php echo $dd03l['FIELDNAME'] ?></a> &nbsp;</td>
                            <td> <?php echo $dd03l_fieldname_desc ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Position </td>
                            <td class="field"> <?php echo $dd03l['POSITION'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                    </tbody>
                </table>

                <h4> Field Attributes </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Key </td>
                            <td> <?php echo ABAP_UI_TOOL::GetCheckBox('field_' . $dd03l['FIELDNAME'], $dd03l['KEYFLAG']) ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Mandatory </td>
                            <td class="field"> <?php echo $dd03l['MANDATORY'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Data Element </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Dtel($dd03l['ROLLNAME'], $dd03l_fieldname_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_fieldname_desc) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Check Table </td>
                            <td class="field"> <?php echo (trim($dd03l['CHECKTABLE']) == '*') ? '*' : ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd03l['CHECKTABLE'], $dd03l_checktable_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_checktable_desc) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Nesting depth for includes </td>
                            <td class="field"> <?php echo $dd03l['ADMINFIELD'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Internal ABAP Type </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_INTTYPE, $dd03l['INTTYPE'], $dd03l_inttype_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_inttype_desc) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Internal Length in Bytes </td>
                            <td class="field"> <?php echo $dd03l['INTLEN'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Reference table </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd03l['REFTABLE'], $dd03l_reftable_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_reftable_desc) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Name of Include </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd03l['PRECFIELD'], $dd03l_precfield_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_precfield_desc) ?> &nbsp;</td>
                        </tr>
                        <tr><td class="content_label"> Reference Field (CURR or QTY) </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4TablField($dd03l['REFTABLE'], $dd03l['REFFIELD']) ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Check module </td>
                            <td class="field"> <?php echo $dd03l['CONROUT'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> NOT NULL forced </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DD03L_NOTNULL, $dd03l['NOTNULL'], $dd03l_notnull_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_notnull_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Data Type in ABAP Dictionary </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DATATYPE, $dd03l['DATATYPE'], $dd03l_datatype_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_datatype_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Length (No. of Characters) </td>
                            <td class="field"> <?php echo $dd03l['LENG'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Number of Decimal Places </td>
                            <td class="field"> <?php echo $dd03l['DECIMALS'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Domain name </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Doma($dd03l['DOMNAME'], $dd03l_domname_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_domname_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Origin of an input help (F4) </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DD03L_SHLPORIGIN, $dd03l['SHLPORIGIN'], $dd03l_shlporigin_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_shlporigin_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> DD: Flag if it is a table </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DD03L_TABLETYPE, $dd03l['TABLETYPE'], $dd03l_tabletype_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_tabletype_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> DD: Depth for structured types </td>
                            <td class="field"> <?php echo $dd03l['DEPTH'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> DD: Component Type </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DD03L_COMPTYPE, $dd03l['COMPTYPE'], $dd03l_comptype_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_comptype_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Type of Object Referenced </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DD03L_REFTYPE, $dd03l['REFTYPE'], $dd03l_reftype_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_reftype_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> DD: Indicator for a Language Field </td>
                            <td class="field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_DD03L_LANGUFLAG, $dd03l['LANGUFLAG'], $dd03l_languflag_desc) ?> &nbsp;</td>
                            <td> <?php echo htmlentities($dd03l_languflag_desc) ?> &nbsp;</td>
                        </tr> 
                        <tr><td class="content_label"> Position of the field in the table </td>
                            <td class="field"> <?php echo $dd03l['DBPOSITION'] ?> &nbsp;</td>
                            <td> &nbsp;</td>
                        </tr> 
                    </tbody>
                </table>
                <?php if (count($dd17s_list) > 0) { ?>
                    <h4> Contained in Index </h4>
                    <table class="alv">
                        <tr>
                            <th class="alv"> Table Name </th>
                            <th class="alv"> Index Name </th>
                            <th class="alv"> Position </th>
                            <th class="alv"> Field Name </th>
                            <th class="alv"> DESC Flag </th>
                        </tr>                        
                        <?php foreach ($dd17s_list as $dd17s) { ?>
                            <tr><td class="alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($dd17s['SQLTAB'], '') ?></td>
                                <td class="alv"><?php echo $dd17s['INDEXNAME'] ?></td>
                                <td class="alv"><?php echo $dd17s['POSITION'] ?></td>
                                <td class="alv"><a href="#"><?php echo $dd17s['FIELDNAME'] ?></a> &nbsp;</td>
                                <td class="alv"><?php echo ABAP_UI_TOOL::GetCheckBox('DESC', $dd17s['DESCFLAG']) ?> &nbsp;</td>
                            </tr>
                        <?php } ?>
                        <tr><td class="alv">&nbsp;</td>
                            <td class="alv">&nbsp;</td>
                            <td class="alv">&nbsp;</td>
                            <td class="alv">&nbsp;</td>
                            <td class="alv">&nbsp;</td>
                        </tr>
                    </table><!-- Contained Tables or Views: End -->
                <?php } ?>

                <h4> History </h4>
                <table class="content_obj">
                    <tbody>
                        <tr><td class="content_label"> Last changed by/on      </td><td class="field"><?php echo $dd02l['AS4USER'] ?>&nbsp;</td><td> <?php echo $dd02l['AS4DATE'] ?>&nbsp;</td></tr>
                        <tr><td class="content_label"> SAP Release Created in  </td><td class="field"><?php echo $hier->CRELEASE ?>&nbsp;</td><td>&nbsp;</td></tr>
                    </tbody>
                </table>

            </div>
        </div><!-- End of Content -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>

    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();