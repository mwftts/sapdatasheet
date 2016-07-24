<?php
$__ROOT__ = dirname(dirname(dirname(__FILE__)));
require_once ($__ROOT__ . '/include/common/global.php');
require_once ($__ROOT__ . '/include/common/abap_db.php');
require_once ($__ROOT__ . '/include/common/abap_ui.php');
GLOBAL_UTIL::UpdateSAPDescLangu();

// Get Index
if (!isset($index)) {
    if (php_sapi_name() == 'cli') {
        $index = $argv[1];
        $GLOBALS[GLOBAL_UTIL::SAP_DESC_LANGU] = $argv[2];
    } else {
        $index = filter_input(INPUT_GET, 'index');
    }
}

if (strlen(trim($index)) == 0) {
    $index = ABAP_DB_CONST::INDEX_PAGE_1;
} else {
    $index = strtoupper($index);
}

// Check Buffer
$ob_folder = GLOBAL_UTIL::GetObFolder(dirname(__FILE__));
if (file_exists($ob_folder) == FALSE) {
    mkdir($ob_folder);
}
$ob_fname = $ob_folder . "/index-" . strtolower($index) . ".html";
if (file_exists($ob_fname)) {
    $ob_file_content = file_get_contents($ob_fname);
    if ($ob_file_content !== FALSE) {
        echo $ob_file_content;
        exit();
    }
}
ob_start();
?>
<!DOCTYPE html>
<!-- Function Module index -->
<?php
$GLOBALS['TITLE_TEXT'] = "SAP ABAP " . GLOBAL_ABAP_OTYPE::CUS0_DESC . " - Index " . $index . " ";
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="/abap.css" type="text/css" />
        <title><?php echo $GLOBALS['TITLE_TEXT'] ?> <?php echo GLOBAL_WEBSITE_SAPDS::TITLE ?> </title>
        <meta name="keywords" content="SAP,ABAP,<?php echo GLOBAL_ABAP_OTYPE::CUS0_DESC ?>" />
        <meta name="description" content="<?php echo GLOBAL_WEBSITE_SAPDS::META_DESC ?>" />
        <meta name="author" content="SAP Datasheet" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>

        <!-- Header -->
        <?php require $__ROOT__ . '/include/header.php' ?>

        <!-- Left -->
        <?php require $__ROOT__ . '/include/abap_index_left.php' ?>

        <!-- Content -->
        <div class="content">
            <!-- Content Navigator -->
            <div class="content_navi">
                <a href="/"><?php echo GLOBAL_ABAP_ICON::getIcon4Home() ?> Home page</a> &gt;
                <a href="/abap/">ABAP Object</a> &gt;
                <a href="/abap/cus0/"><?php echo GLOBAL_ABAP_OTYPE::CUS0_DESC ?></a>
            </div>

            <!-- Content Object -->
            <div class="content_obj_title"><span><?php echo $GLOBALS['TITLE_TEXT'] ?></span></div>
            <div class="content_obj">
                <div>
                    <?php include $__ROOT__ . '/include/google/adsense-content-top.html' ?>
                </div>

                <div>
                    <?php for ($count = 1; $count <= ABAP_DBDATA::CUS_IMGACT_INDEX_MAX; $count++) { ?>
                        <a href="index-<?php echo $count ?>.html"><?php echo $count ?></a>&nbsp;
                    <?php } ?>
                    <!--<a href="index-roadmap.html">Road map</a>&nbsp;-->
                </div>

                <h4> <?php echo GLOBAL_ABAP_OTYPE::CUS0_DESC ?> - <?php echo $index ?></h4>
                <table class="alv">
                    <tr>
                        <th class="alv"> # </th>
                        <th class="alv"> IMG Activity </th>
                        <th class="alv"> Transaction Code </th>
                        <th class="alv"> Short Description </th>
                    </tr>
                    <tr>
                        <th class="alv"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_CONST::INDEX_SEQNO_DTEL, '?') ?></th>
                        <th class="alv"> &nbsp; </th>
                        <th class="alv"> <?php echo ABAP_UI_DS_Navigation::GetHyperlink4DtelDocument(ABAP_DB_TABLE_CUS0::CUS_IMGACH_TCODE_DTEL, '?') ?></th>
                        <th class="alv"> &nbsp; </th>
                    </tr>
                    <?php
                    $img_list = ABAP_DB_TABLE_CUS0::CUS_IMGACH_List($index);
                    $count = 0;
                    foreach ($img_list as $img) {
                        $count ++;
                        $img_desc = ABAP_DB_TABLE_CUS0::CUS_IMGACT($img['ACTIVITY']);
                        ?>
                        <tr><td class="alv" style="text-align: right;"><?php echo number_format($count) ?> </td>
                            <td class="alv"><?php echo GLOBAL_ABAP_ICON::getIcon4OtypeCUS0() ?> 
                                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Cus0IMGActivity($img['ACTIVITY'], $img_desc, TRUE) ?> </td>
                            <td class="alv"><?php echo (GLOBAL_UTIL::IsNotEmpty($img['TCODE'])) ? GLOBAL_ABAP_ICON::getIcon4OtypeTRAN() : '' ?>
                                <?php echo ABAP_UI_DS_Navigation::GetHyperlink4Tran($img['TCODE'], '', TRUE) ?> </td>
                            <td class="alv"><?php echo htmlentities($img_desc) ?>&nbsp;</td>
                        </tr>
                    <?php } ?>
                </table>

            </div>
        </div><!-- Content: End -->

        <!-- Footer -->
        <?php require $__ROOT__ . '/include/footer.php' ?>

    </body>
</html>
<?php
$ob_content = ob_get_contents();
ob_end_flush();
file_put_contents($ob_fname, $ob_content);

// Make default index file
if ($index == ABAP_DB_CONST::INDEX_PAGE_1) {
    $ob_fname = $ob_folder . "/index.html";
    file_put_contents($ob_fname, $ob_content);
}

// Close Database Connection
ABAP_DB_TABLE::close_conn();