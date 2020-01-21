<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <link href="<?php echo base_url();?>styles/elements.css?v=1.0" rel="stylesheet" type="text/css">
               

        <!-- CODE INCLUDES -->
    </head>
    <body>
    	<div class="center-pdf-logo"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>"></div>
    	<h1 class="pdf-project-heading"><?php echo $project->name ?></h1>
    	<?php echo $project->description ?>

    </body>
    </html>