<?php
$__WS_ROOT__ = dirname(__FILE__, 4);
$__ROOT__ = dirname(__FILE__, 3);

require_once ($__WS_ROOT__ . '/common-php/library/global.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_ui.php');
require_once ($__WS_ROOT__ . '/common-php/library/schemaorg.php');
require_once ($__WS_ROOT__ . '/common-php/library/graphviz.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

if (empty($ObjID)) {
    ABAP_UI_TOOL::Redirect404();
}
$tstc = ABAP_DB_TABLE_TRAN::TSTC(strtoupper($ObjID));
if (empty($tstc['TCODE'])) {
    ABAP_UI_TOOL::Redirect404();
}
$tstc_cinfo_desc = ABAP_UI_TOOL::GetTCodeTypeDesc($tstc['CINFO']);
$tstc_pgmna_desc = ABAP_DB_TABLE_PROG::TRDIRT($tstc['PGMNA']);
$tstc_desc = htmlentities(ABAP_DB_TABLE_TRAN::TSTCT($tstc['TCODE']));
$tstca_list = ABAP_DB_TABLE_TRAN::TSTCA_List($tstc['TCODE']);
$tstcc = ABAP_DB_TABLE_TRAN::TSTCC($tstc['TCODE']);
$tstcp = ABAP_DB_TABLE_TRAN::TSTCP($tstc['TCODE']);

$wul_counter_list = ABAPANA_DB_TABLE::WULCOUNTER_List(GLOBAL_ABAP_OTYPE::TRAN_NAME, $tstc['TCODE']);
$wil_enabled = TRUE;
$wil_counter_list = ABAPANA_DB_TABLE::WILCOUNTER_List(GLOBAL_ABAP_OTYPE::TRAN_NAME, $tstc['TCODE']);

$hier = ABAP_DB_TABLE_HIER::Hier(ABAP_DB_TABLE_HIER::TADIR_PGMID_R3TR, GLOBAL_ABAP_OTYPE::TRAN_NAME, $tstc['TCODE']);
$GLOBALS['TITLE_TEXT'] = ABAP_UI_TOOL::GetObjectTitle(GLOBAL_ABAP_OTYPE::TRAN_NAME, $tstc['TCODE']);

$json_ld = new \OrgSchema\Thing();
$json_ld->name = $tstc['TCODE'];
$json_ld->alternateName = $tstc_desc;
$json_ld->description = $GLOBALS['TITLE_TEXT'];
$json_ld->image = GLOBAL_ABAP_ICON::getIconURL(GLOBAL_ABAP_ICON::OTYPE_TRAN, TRUE);
$json_ld->url = ABAP_UI_DS_Navigation::GetObjectURL(GLOBAL_ABAP_OTYPE::TRAN_NAME, $json_ld->name);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE::SAPDS_ORG_TITLE ?> </title>
        <meta name="author" content="SAP Datasheet" />
        <meta name="description" content="<?php echo $GLOBALS['TITLE_TEXT'] . GLOBAL_WEBSITE::SAPDS_ORG_TITLE ?>" />
        <meta name="keywords" content="SAP,<?php echo GLOBAL_ABAP_OTYPE::TRAN_DESC ?>,<?php echo $tstc['TCODE']; ?>,<?php echo $tstc_desc ?>" />

        <link rel="stylesheet" type="text/css"  href="/3rdparty/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css"  href="/sapdatasheet.css"/>

        <script type="application/ld+json"><?php echo $json_ld->toJson() ?></script>
    </head>
    <body>
        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <div class="container-fluid">
            <div class="row">
                <div  class="col-xl-2 col-lg-2 col-md-3  col-sm-3    bd-sidebar bg-light">
                    <!-- Left Side bar -->
                    <h6 class="pt-4">Object Hierarchy</h6>
                    <table>
                        <tbody>
                            <tr><td><small><strong>Software Component</strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCVERS() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cvers($hier->DLVUNIT, $hier->DLVUNIT_T) ?>&nbsp;</td></tr>
                            <tr><td><small><strong> Application Component ID</strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeBMFR() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Bmfr($hier->FCTR_ID, $hier->POSID, $hier->POSID_T) ?>&nbsp;</td></tr>
                            <tr><td><small><strong> Package </strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeDEVC() ?>
                                    <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Devc($hier->DEVCLASS, $hier->DEVCLASS_T) ?></td></tr>
                            <tr><td><small><strong> Object type </strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTRAN() ?>
                                    <a href="/abap/tran/"><?php echo GLOBAL_ABAP_OTYPE::TRAN_DESC ?></a></td></tr>
                            <tr><td><small><strong>Object name </strong></small></td></tr>
                            <tr><td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTRAN() ?>
                                    <a href="#" title="<?php echo $tstc_desc ?>"><?php echo $tstc['TCODE'] ?></a> </td></tr>
                        </tbody>
                    </table>

                    <?php require $__ROOT__ . '/include/abap_oname_wul.php' ?>
                    <?php require $__ROOT__ . '/include/abap_ads_side.php' ?>
                </div>

                <main class="col-xl-8 col-lg-8 col-md-6  col-sm-9    col-12 bd-content" role="main">
                    <nav class="pt-3" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home</a></li>
                            <li class="breadcrumb-item"><a href="/abap/">ABAP Object Types</a></li>
                            <li class="breadcrumb-item"><a href="/abap/tran/"><?php echo GLOBAL_ABAP_OTYPE::TRAN_DESC ?></a></li>
                            <li class="breadcrumb-item active"><a href="#"><?php echo $tstc['TCODE'] ?></a></li>
                        </ol>
                    </nav>

                    <div class="card shadow">
                        <div class="card-header sapds-card-header"><?php echo $GLOBALS['TITLE_TEXT'] ?></div>
                        <div class="card-body table-responsive sapds-card-body">
                            <div class="align-content-start"><?php include $__WS_ROOT__ . '/common-php/google/adsense-content-top.html' ?></div>
                            <?php require $__ROOT__ . '/include/abap_desc_language.php' ?>
                            <?php require $__ROOT__ . '/include/abap_oname_hier.php' ?>

                            <h5 class="pt-4"> <?php echo GLOBAL_ABAP_ICON::getIcon4Header() ?> Basic Data </h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> Transaction Code        </td>
                                        <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeTRAN() ?> </td>
                                        <td class="sapds-gui-field"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tran($tstc['TCODE'], $tstc_desc) ?> </td>
                                        <td>&nbsp; <?php echo GLOBAL_ABAP_ICON::getIcon4Analytics() ?> <a href="<?php echo ABAP_UI_TCODES_Navigation::TCode($tstc['TCODE'], TRUE) ?>" title="<?php echo $tstc_desc ?>" target="_blank">TCode <?php echo $tstc['TCODE'] ?> Analytics</a> <sup><img src="<?php echo ABAP_UI_CONST::ICON_EXTERNAL_LINK ?>"></sup></td></tr>
                                    <tr><td class="sapds-gui-label"> Transaction Description </td>
                                        <td><?php echo GLOBAL_ABAP_ICON::getIcon4Description() ?> </td>
                                        <td class="sapds-gui-field"> <?php echo $tstc_desc ?> &nbsp;</td>
                                        <td>&nbsp;</td></tr>
                                    <tr><td class="sapds-gui-label"> Transaction Type        </td>
                                        <td>&nbsp;</td>
                                        <td class="sapds-gui-field"> <?php echo $tstc['CINFO'] ?> </td>
                                        <td><?php echo $tstc_cinfo_desc ?></td></tr>
                                </tbody>
                            </table>

                            <!-- Attributes - Screen Specific -->
                            <h5 class="pt-4"> <?php echo GLOBAL_ABAP_ICON::getIcon4Header() ?> Attribute </h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> Program       </td>
                                        <td><?php echo GLOBAL_ABAP_ICON::getIcon4OtypePROG() ?> </td>
                                        <td class="sapds-gui-field"><?php echo ABAP_UI_DS_Navigation::GetHyperlink4Prog($tstc['PGMNA'], $tstc_pgmna_desc) ?>&nbsp; </td>
                                        <td><?php echo htmlentities($tstc_pgmna_desc) ?></td></tr>
                                    <tr><td class="sapds-gui-label"> Screen number </td>
                                        <td>&nbsp;</td>
                                        <td class="sapds-gui-field"><?php echo $tstc['DYPNO'] ?>&nbsp;</td>
                                        <td>&nbsp;</td></tr>
                                </tbody>
                            </table>

                            <?php if (count($tstca_list) > 0) { ?>
                                <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypePFCG() ?> Authorization</h5>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr><th class="sapds-alv">Authorization Object</th><th class="sapds-alv">Authorization Field</th><th class="sapds-alv">Value</th></tr>
                                        <?php foreach ($tstca_list as $tstca_item) { ?>
                                            <tr><td class="sapds-alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeSU21() ?> 
                                                    <?php echo $tstca_item['OBJCT'] ?>&nbsp;</td>
                                                <td class="sapds-alv"><?php echo $tstca_item['FIELD'] ?></td>
                                                <td class="sapds-alv"><?php echo $tstca_item['VALUE'] ?>&nbsp;</td></tr>
                                        <?php } ?>
                                        <tr><td class="sapds-alv">&nbsp;</td><td class="sapds-alv">&nbsp;</td><td class="sapds-alv">&nbsp;</td></tr>
                                    </tbody>
                                </table>
                            <?php } ?>

                            <h5 class="pt-4"> <?php echo GLOBAL_ABAP_ICON::getIcon4Parameter() ?> Parameter</h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> Transaction Code Parameter </td>
                                        <td class="sapds-gui-field"> <?php
                                            $param = $tstcp['PARAM'];
                                            if (!empty($param)) {
                                                $param_s1 = explode(' ', $param);
                                                foreach ($param_s1 as $param_s1_value) {
                                                    $param_s2 = explode(';', $param_s1_value);
                                                    foreach ($param_s2 as $param_s2_value) {
                                                        echo $param_s2_value;
                                                        echo '<br />';
                                                    }
                                                }
                                            }
                                            ?>
                                        </td></tr>
                                </tbody>
                            </table>

                            <h5 class="pt-4"><?php echo GLOBAL_ABAP_ICON::getIcon4Sapgui() ?> GUI Support</h5>
                            <table>
                                <tbody>
                                    <tr><td><?php echo ABAP_UI_TOOL::GetCheckBox("cb_sapgui_web", $tstcc['S_WEBGUI']) ?> SAPGUI for HTML</td></tr>
                                    <tr><td><?php echo ABAP_UI_TOOL::GetCheckBox("cb_sapgui_java", $tstcc['S_PLATIN']) ?> SAPGUI for Java</td></tr>
                                    <tr><td><?php echo ABAP_UI_TOOL::GetCheckBox("cb_sapgui_win", $tstcc['S_WIN32']) ?> SAPGUI for Windows</td></tr>
                                </tbody>
                            </table>

                            <h5  class="pt-4"> <?php echo GLOBAL_ABAP_ICON::getIcon4History() ?> History </h5>
                            <table>
                                <tbody>
                                    <tr><td class="sapds-gui-label"> SAP Release Created in  </td><td class="sapds-gui-field"><?php echo $hier->CRELEASE ?>&nbsp;</td><td>&nbsp;</td></tr>
                                </tbody>
                            </table>
                            
                            <h5  class="pt-4"> <?php echo GLOBAL_ABAP_ICON::getIcon4ForeignKey() ?> Usage </h5>
                            <div class="container-fluid">
                            <object class="img-fluid" type="image/svg+xml"
									data="<?php echo ABAP_UI_TCODES_Navigation::TCodeGraph($tstc['TCODE'], TCodeGraphviz::layout_sfdp, TRUE) ?>">
									Your browser does not support SVG</object>
                            </div>
                            
                        </div> 
                    </div><!-- End Card -->
                </main>

                <div  class="col-xl-2 col-lg-2 d-md-3    col-sm-none" >
                    <!-- Right Side bar -->
                    <h6 class="pt-4">SAP T-Code Analytics</h6>
                    <div>
                        <?php echo GLOBAL_ABAP_ICON::getIcon4Analytics() ?>
                        <?php echo ABAP_UI_TCODES_Navigation::TCodeHyperlink($tstc['TCODE'], TRUE) ?>
                        <sup><img src="<?php echo ABAP_UI_CONST::ICON_EXTERNAL_LINK ?>"></sup>
                        
                        <a href="<?php echo ABAP_UI_TCODES_Navigation::TCode($tstc['TCODE'], TRUE) ?>" target="_blank">
                        <img class="img-fluid img-thumbnail mx-auto d-block"
                             src="<?php echo ABAP_UI_TCODES_Navigation::TCodeGraph($tstc['TCODE'], TCodeGraphviz::layout_dot, TRUE) ?>"
                             alt="<?php echo $tstc_desc ?>"
                             title="<?php echo $tstc_desc ?>">
                        </a>
                    </div>

                    <?php require $__ROOT__ . '/include/abap_relatedlinks.php' ?>
                </div>
            </div><!-- End of row -->
            <hr>
        </div><!-- End container-fluid, main content -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>
    </body>
</html>
<?php
// Close PDO Database Connection
ABAP_DB_TABLE::close_conn();
