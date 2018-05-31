<?php
$__WS_ROOT__ = dirname(__FILE__, 3);           // Root folder for the Workspace
$__ROOT__ = dirname(__FILE__, 2);              // Root folder for Current web site

require_once ($__WS_ROOT__ . '/common-php/library/global.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_db.php');
require_once ($__WS_ROOT__ . '/common-php/library/abap_ui.php');
require_once ($__WS_ROOT__ . '/common-php/library/sitemap.php');

$fname = pathinfo(__FILE__, PATHINFO_FILENAME);

SitemapStartOB();

SitemapEchoUrl(GLOBAL_WEBSITE::URLPREFIX_SAPTCODES_ORG . '/download/book/', '0.9');
SitemapEchoUrl(GLOBAL_WEBSITE::URLPREFIX_SAPTCODES_ORG . '/download/sheet/', '0.9');
SitemapEchoUrl(GLOBAL_WEBSITE::URLPREFIX_SAPTCODES_ORG . ABAP_UI_TCODES_Navigation::PATH_DOWNLOAD_BOOK_DIST, '0.9');
SitemapEchoUrl(GLOBAL_WEBSITE::URLPREFIX_SAPTCODES_ORG . ABAP_UI_TCODES_Navigation::PATH_DOWNLOAD_SHEET_DIST, '0.9');

$dir = new DirectoryIterator('C:\Data\Business\SAP-TCodes\Runtime\www-root\download\book\dist');
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        $url = ABAP_UI_TCODES_Navigation::DownloadBookPath($fileinfo->getFilename(), TRUE);
        SitemapEchoUrl($url, '0.9');
    }
}

$dir = new DirectoryIterator('C:\Data\Business\SAP-TCodes\Runtime\www-root\download\sheet\dist');
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        $url = ABAP_UI_TCODES_Navigation::DownloadSheetPath($fileinfo->getFilename(), TRUE);
        SitemapEchoUrl($url, '0.9');
    }
}

SitemapEndOB($fname);