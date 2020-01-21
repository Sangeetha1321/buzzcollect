<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <link href="<?php echo base_url();?>styles/client2.css?v=1.0" rel="stylesheet" type="text/css">
               

        <!-- CODE INCLUDES -->
    </head>
    <body>
		

		<?php if($document->link_documentid > 0) : ?>
		<a name="document-<?php echo $document->ID ?>" class="pdf-heading"><strong><?php echo $document->link_title ?></strong> - <a href="#top"><?php echo lang("ctn_1546") ?></a></a>
		<hr>
		<?php echo $document->link_document ?>
		<?php else : ?>
		<div class="pdf-heading"><a name="document-<?php echo $document->ID ?>"><strong><?php echo $document->title ?></strong> - <a href="#top"><?php echo lang("ctn_1546") ?></a></a></div>
		<hr>
		<?php echo $document->document ?>
		<?php endif; ?>

		<hr>

    </body>
    </html>