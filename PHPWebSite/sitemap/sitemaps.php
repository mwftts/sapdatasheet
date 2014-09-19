<?php ob_start(); ?>
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>http://www.sapdatasheet.org/sitemap/index.xml</loc>
        <lastmod><?php echo date("Y-m-d") ?></lastmod>
    </sitemap>
    <sitemap>
        <loc>http://www.sapdatasheet.org/sitemap/abap-bmfr.xml</loc>
        <lastmod><?php echo date("Y-m-d") ?></lastmod>
    </sitemap>
    <sitemap>
        <loc>http://www.sapdatasheet.org/sitemap/abap-cvers.xml</loc>
        <lastmod><?php echo date("Y-m-d") ?></lastmod>
    </sitemap>
    <sitemap>
        <loc>http://www.sapdatasheet.org/sitemap/abap-devc.xml</loc>
        <lastmod><?php echo date("Y-m-d") ?></lastmod>
    </sitemap>
    <sitemap>
        <loc>http://www.sapdatasheet.org/sitemap/abap-sqlt.xml</loc>
        <lastmod><?php echo date("Y-m-d") ?></lastmod>
    </sitemap>
    <?php
    $abap_type_count = array(
        array("doma", 2),
        array("dtel", 9),
        array("func", 9),
        array("prog", 6),
        array("tabl", 9),
        array("table-field", 153),
        array("tran", 3),
    );
    ?>
    <?php foreach ($abap_type_count as $abap_type_item) { ?>
        <?php for ($i = 1; $i <= $abap_type_item[1]; $i++) { ?>
            <sitemap>
                <loc>http://www.sapdatasheet.org/sitemap/abap-<?php echo $abap_type_item[0] ?><?php echo $i ?>.xml</loc>
                <lastmod><?php echo date("Y-m-d") ?></lastmod>
            </sitemap>
        <?php } ?>
    <?php } ?>
</sitemapindex>
<?php
$ob_content = ob_get_contents();
ob_end_flush();
$ob_fp = fopen("./sitemaps.xml", "w");
fwrite($ob_fp, $ob_content);
fclose($ob_fp);

