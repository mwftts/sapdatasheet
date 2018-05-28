<?php
$__WS_ROOT__ = dirname(__FILE__, 4);
$__ROOT__ = dirname(__FILE__, 3);

require_once ($__WS_ROOT__ . '/common-php/library/global.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_ui.php');
require_once ($__WS_ROOT__ . '/common-php/library/schemaorg.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

if (empty($ObjID)) {
    ABAP_UI_TOOL::Redirect404();
}
$tdevc = ABAP_DB_TABLE_HIER::TDEVC(strtoupper($ObjID));
if (empty($tdevc['DEVCLASS'])) {
    ABAP_UI_TOOL::Redirect404();
}

$tdevc_desc = ABAP_DB_TABLE_HIER::TDEVCT($tdevc['DEVCLASS']);
$tdevc_parent_desc = ABAP_DB_TABLE_HIER::TDEVCT($tdevc['PARENTCL']);
$tdevc_mainpack_desc = ABAP_DB_TABLE_DOMA::DD07T(ABAP_DB_CONST::DOMAIN_TDEVC_MAINPACK, $tdevc['MAINPACK']);
$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::DEVC_NAME, $tdevc['DEVCLASS']);
$child_tabl = ABAP_DB_TABLE_HIER::TADIR_Child($tdevc['DEVCLASS'], ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::TABL_NAME);
$child_tran = ABAP_DB_TABLE_HIER::TADIR_Child($tdevc['DEVCLASS'], ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::TRAN_NAME);

$GLOBALS['TITLE_TEXT'] = ABAP_UI_TOOL::GetObjectTitle(GLOBAL_ABAP_OTYPE::DEVC_NAME, $tdevc['DEVCLASS']);

$json_ld = new \OrgSchema\Thing();
$json_ld->name = $tdevc['DEVCLASS'];
$json_ld->alternateName = $tdevc_desc;
$json_ld->description = $GLOBALS['TITLE_TEXT'];
$json_ld->image = GLOBAL_ABAP_ICON::getIconURL(GLOBAL_ABAP_ICON::OTYPE_DEVC, TRUE);
$json_ld->url = ABAP_UI_DS_Navigation::GetObjectURL(GLOBAL_ABAP_OTYPE::DEVC_NAME, $json_ld->name);
?>
<!DOCTYPE html>
<!-- Package object. -->
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="author" content="SAP Datasheet" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC; ?>" />
        <meta name="keywords" content="SAP,<?php echo GLOBAL_ABAP_OTYPE::DEVC_DESC ?>,<?php echo $tdevc['DEVCLASS'] ?>,<?php echo $tdevc_desc ?>" />

        <link rel="stylesheet" type="text/css"  href="/3rdparty/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css"  href="/sapdatasheet.css"/>

        <script type="application/ld+json"><?php echo $json_ld->toJson() ?></script>
    </head>
    <body>
        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <!-- Left -->
        <div class="left">
            <h5>&nbsp;</h5>
            <h5>Object Hierarchy</h5>
            <table>
                <tbody>
                    <tr><td>Software Component</td></tr>
                    <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?>
                            <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td></tr>
                    <tr><td> Application Component ID</td></tr>
                    <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                            <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;</td></tr>
                    <tr><td> Package </td></tr>
                    <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                            <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($tdevc['DEVCLASS'], $tdevc_desc) ?></td></tr>
                </tbody>
            </table>

            <?php require $__ROOT__ . '/include/abap_relatedlinks.php' ?>
            <h5>&nbsp;</h5>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Content Navigator -->
            <div class="content_navi">
                <a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home page</a> &gt; 
                <a href="/abap/">ABAP Object</a> &gt; 
                <a href="/abap/devc/">ABAP <?php echo GLOBAL_ABAP_OTYPE::DEVC_DESC ?></a> &gt; 
                <a href="#"><?php echo $tdevc['DEVCLASS'] ?></a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?>
                </div>

                <h5 class="pt-4"> Basic Data </h5>
                <table>
                    <tbody>
                        <tr><td class="sapds-gui-label"> Package                </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?></td>
                            <td class="sapds-gui-field"><a href="#"><?php echo $tdevc['DEVCLASS'] ?></a>&nbsp;</td>
                            <td>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> Short Description      </td>
                            <td>&nbsp;</td>
                            <td class="sapds-gui-field"> <?php echo htmlentities($tdevc_desc) ?> &nbsp;</td>
                            <td>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> Super package          </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?></td>
                            <td class="sapds-gui-field"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($tdevc['PARENTCL'], $tdevc_parent_desc) ?> &nbsp;</td>
                            <td><?php echo $tdevc_parent_desc ?>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> Main package indicator </td>
                            <td>&nbsp;</td>
                            <td class="sapds-gui-field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_CONST::DOMAIN_TDEVC_MAINPACK, $tdevc['MAINPACK'], $tdevc_mainpack_desc) ?> &nbsp;</td>
                            <td><?php echo $tdevc_mainpack_desc ?>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> Created on/by          </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4Date() ?></td>
                            <td class="sapds-gui-field"><?php echo $tdevc['CREATED_ON'] ?>&nbsp;</td>
                            <td class="sapds-gui-field"><?php echo $tdevc['CREATED_BY'] ?>&nbsp;</td></tr>
                    </tbody>
                </table><!-- Basic Data: End -->

                <!-- Package Content -->
                <h4> Package Content</h4>
                <!-- Contained Tables or Views -->
                <?php if (count($child_tabl) > 0) { ?>
                    <table class="sapds-alv">
                        <caption class="sapds-alv">Contained Tables / Views</caption>
                        <tr>
                            <th class="sapds-alv"> Table Name </th>
                            <th class="sapds-alv"> Short Description </th>
                            <th class="sapds-alv"> Table Category </th>
                            <th class="sapds-alv"> Delivery Class </th>
                        </tr>
                        <?php
                        foreach ($child_tabl as $child_tabl_item) {
                            $table_dd02l = ABAP_DB_TABLE_TABL::DD02L($child_tabl_item['OBJ_NAME']);
                            $child_tabl_item_t = ABAP_DB_TABLE_TABL::DD02T($child_tabl_item['OBJ_NAME']);
                            if ($table_dd02l['TABCLASS'] === ABAP_DB_CONST::DOMAINVALUE_TABCLASS_TRANSP || $table_dd02l['TABCLASS'] == ABAP_DB_CONST::DOMAINVALUE_TABCLASS_POOL || $table_dd02l['TABCLASS'] == ABAP_DB_CONST::DOMAINVALUE_TABCLASS_CLUSTER || $table_dd02l['TABCLASS'] == ABAP_DB_CONST::DOMAINVALUE_TABCLASS_VIEW
                            ) {
                                ?>
                                <tr><td class="sapds-alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTABL() ?>
                                        <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tabl($child_tabl_item['OBJ_NAME'], $child_tabl_item_t) ?></td>
                                    <td class="sapds-alv"><?php echo htmlentities($child_tabl_item_t) ?>&nbsp;</td>
                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_TABL::DD02L_TABCLASS_DOMAIN, $table_dd02l['TABCLASS'], '') ?> &nbsp;</td>
                                    <td class="sapds-alv"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4DomainValue(ABAP_DB_TABLE_TABL::DD02L_CONTFLAG_DOMAIN, $table_dd02l['CONTFLAG'], '') ?> &nbsp;</td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        <tr><td class="sapds-alv">&nbsp;</td>
                            <td class="sapds-alv">&nbsp;</td>
                            <td class="sapds-alv">&nbsp;</td>
                            <td class="sapds-alv">&nbsp;</td>
                        </tr>
                    </table><!-- Contained Tables or Views: End -->
                <?php } ?>
                <!-- Contained T-Codes -->
                <?php if (count($child_tran) > 0) { ?>
                    <table class="sapds-alv">
                        <caption class="sapds-alv">Contained Transaction Codes</caption>
                        <tr>
                            <th class="sapds-alv"> Transaction Code </th>
                            <th class="sapds-alv"> Short Description </th>
                            <th class="sapds-alv"> Program </th>
                        </tr>                        
                        <?php
                        foreach ($child_tran as $child_tran_item) {
                            $tcode_tstc = ABAP_DB_TABLE_TRAN::TSTC($child_tran_item['OBJ_NAME']);
                            $child_tran_item_t = ABAP_DB_TABLE_TRAN::TSTCT($child_tran_item['OBJ_NAME']);
                            ?>
                            <tr><td class="sapds-alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTRAN() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tran($child_tran_item['OBJ_NAME'], $child_tran_item_t) ?></td>
                                <td class="sapds-alv"><?php echo htmlentities($child_tran_item_t) ?>&nbsp;</td>
                                <td class="sapds-alv"><?php echo (GLOBAL_UTIL::IsNotEmpty($tcode_tstc['PGMNA'])) ? GLOBAL_ABAP_ICON::getIcon4OtypePROG() : '' ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Prog($tcode_tstc['PGMNA'], '') ?> &nbsp;</td>
                            </tr>
                        <?php } ?>
                        <tr><td class="sapds-alv">&nbsp;</td>
                            <td class="sapds-alv">&nbsp;</td>
                            <td class="sapds-alv">&nbsp;</td>
                        </tr>
                    </table>
                <?php } ?><!-- Contained T-Codes: End -->
                <!-- Package Content: End -->

                <h4> Hierarchy </h4>
                <table>
                    <tbody>
                        <tr><td class="sapds-gui-label"> Software Component      </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?></td>
                            <td class="sapds-gui-field"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td>
                            <td> <?php echo $hier->DLVUNIT_T ?>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> SAP Release Created in  </td>
                            <td>&nbsp;</td>
                            <td class="sapds-gui-field"><?php echo $hier->CRELEASE ?>&nbsp;</td>
                            <td>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> Application Component   </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?></td>
                            <td class="sapds-gui-field"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;(<?php echo $hier->FCTR_ID ?>)</td>
                            <td> <?php echo $hier->POSID_T ?>&nbsp;</td></tr>
                        <tr><td class="sapds-gui-label"> Package                 </td>
                            <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?></td>
                            <td class="sapds-gui-field"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T); ?>&nbsp;</td>
                            <td> <?php echo $hier->DEVCLASS_T ?>&nbsp;</td></tr>
                    </tbody>
                </table><!-- Hierarchy: End -->

            </div>
        </div><!-- Content: End -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>

    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();
