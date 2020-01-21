<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php if(isset($page_title)) : ?><?php echo $page_title ?> - <?php endif; ?><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

         <!-- Styles -->
        <link href="<?php echo base_url();?>styles/deepblue.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>styles/theme.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>styles/elements.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>styles/responsive.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,500,550,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

        <!-- SCRIPTS -->
        <script type="text/javascript">
        var global_base_url = "<?php echo site_url('/') ?>";
        var global_hash = "<?php echo $this->security->get_csrf_hash() ?>";
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

        <!-- Favicon: http://realfavicongenerator.net -->
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url() ?>images/favicon/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url() ?>images/favicon/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url() ?>images/favicon/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url() ?>images/favicon/apple-touch-icon-76x76.png">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>images/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>images/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="<?php echo base_url() ?>images/favicon/manifest.json">
        <link rel="mask-icon" href="<?php echo base_url() ?>images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="<?php echo base_url() ?>images/favicon/favicon.ico">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="<?php echo base_url() ?>images/favicon/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        

    </head>
    <body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url() ?>"><?php echo $this->settings->info->site_name ?> - <?php echo lang("ctn_791") ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container theme-showcase" role="main">
    <?php $gl = $this->session->flashdata('globalmsg'); ?>
        <?php if(!empty($gl)) :?>
                    <div class="row">
                        <div class="col-md-12">
                                <div class="alert alert-success"><b><span class="glyphicon glyphicon-ok"></span></b> <?php echo $this->session->flashdata('globalmsg') ?></div>
                        </div>
                    </div>
        <?php endif; ?>
    <div class="panel panel-default">
<div class="panel-heading"><?php echo $form->title ?></div>
<div class="panel-body">
<?php echo form_open(site_url("leads/process_form/" . $form->ID . "/1"), array("class" => "form-horizontal")) ?>
<p><?php echo $form->welcome ?></p>
<hr>
<?php if($form->collect_user) : ?>
<h4><?php echo lang("ctn_227") ?></h4>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_419") ?>*</label>
        <div class="col-md-8 ui-front">
            <input type="email" class="form-control" name="email" value="">
            <span class="help-block"><?php echo lang("ctn_1358") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1250") ?>*</label>
        <div class="col-md-4">
            <input type="text" class="form-control" name="first_name" value="" placeholder="<?php echo lang("ctn_29") ?>">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="last_name" value="" placeholder="<?php echo lang("ctn_30") ?>">
        </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_429") ?></label>
    <div class="col-md-8">
      <input type="text" name="address_1" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_430") ?></label>
    <div class="col-md-8">
      <input type="text" name="address_2" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_431") ?></label>
    <div class="col-md-8">
      <input type="text" name="city" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_432") ?></label>
    <div class="col-md-8">
      <input type="text" name="state" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_433") ?></label>
    <div class="col-md-8">
      <input type="text" name="zipcode" class="form-control" value="">
    </div>
</div>
<div class="form-group">
    <label for="inputEmail3" class="col-md-4 label-heading"><?php echo lang("ctn_434") ?></label>
    <div class="col-md-8">
      <input type="text" name="country" class="form-control" value="">
    </div>
</div>
<?php foreach($cfields->result() as $r) : ?>
    <div class="form-group">

        <label for="name-in" class="col-md-4 label-heading"><?php echo $r->name ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8">
            <?php if($r->type == 0) : ?>
                <input type="text" class="form-control" id="name-in" name="cf_<?php echo $r->ID ?>" value="<?php if(isset($_POST['cf_'. $r->ID])) echo $_POST['cf_' . $r->ID] ?>">
            <?php elseif($r->type == 1) : ?>
                <textarea name="cf_<?php echo $r->ID ?>" rows="8" class="form-control"><?php if(isset($_POST['cf_'. $r->ID])) echo $_POST['cf_' . $r->ID] ?></textarea>
            <?php elseif($r->type == 2) : ?>
                 <?php $options = explode(",", $r->options); ?>
                <?php if(count($options) > 0) : ?>
                    <?php foreach($options as $k=>$v) : ?>
                    <div class="form-group"><input type="checkbox" name="cf_cb_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" <?php if(isset($_POST['cf_cb_' . $r->ID . "_" . $k])) echo "checked" ?>> <?php echo $v ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php elseif($r->type == 3) : ?>
                <?php $options = explode(",", $r->options); ?>
                <?php if(count($options) > 0) : ?>
                    <?php foreach($options as $k=>$v) : ?>
                    <div class="form-group"><input type="radio" name="cf_radio_<?php echo $r->ID ?>" value="<?php echo $k ?>" <?php if(isset($_POST['cf_radio_' . $r->ID]) && $_POST['cf_radio_' . $r->ID] == $k) echo "checked" ?>> <?php echo $v ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php elseif($r->type == 4) : ?>
                <?php $options = explode(",", $r->options); ?>
                <?php if(count($options) > 0) : ?>
                    <select name="cf_<?php echo $r->ID ?>" class="form-control">
                    <?php foreach($options as $k=>$v) : ?>
                    <option value="<?php echo $k ?>" <?php if(isset($_POST['cf_' . $r->ID]) && $_POST['cf_'.$r->ID] == $k) echo "selected" ?>><?php echo $v ?></option>
                    <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            <?php endif; ?>
            <span class="help-text"><?php echo $r->help_text ?></span>
        </div>
</div>
<?php endforeach; ?>
<hr>
<?php endif; ?>
<?php foreach($fields->result() as $r) : ?>

<?php if($r->type == 1) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="field_id_<?php echo $r->ID ?>" value="">
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 2) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <textarea name="field_id_<?php echo $r->ID ?>" id="field-<?php echo $r->ID ?>-textarea"></textarea>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 3) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <?php foreach($options as $k=>$v) : ?>
                <div class="form-group"><input type="checkbox" name="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>" value="1"> <?php echo $v ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 4) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <?php foreach($options as $k=>$v) : ?>
                <div class="form-group"><input type="radio" name="field_id_<?php echo $r->ID ?>" value="<?php echo $k ?>"> <?php echo $v ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php elseif($r->type == 5) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <select name="field_id_<?php echo $r->ID ?>" class="form-control">
                <?php foreach($options as $k=>$v) : ?>
                <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
        </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<hr>
* = <?php echo lang("ctn_803") ?>.

<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_61") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    <?php foreach($fields->result() as $r) : ?>
    <?php if($r->type == 2) : ?>
CKEDITOR.replace('field-<?php echo $r->ID ?>-textarea', { height: '100'});
<?php endif; ?>
<?php endforeach; ?>
});
</script>
    </div>

    <!-- SCRIPTS -->
    <script src="<?php echo base_url();?>scripts/custom/global.js"></script>
    <script src="<?php echo base_url();?>scripts/libraries/jquery.nicescroll.min.js"></script>
    <script type="text/javascript">
      $.widget.bridge('uitooltip', $.ui.tooltip);
    </script>
    
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>

     <script type="text/javascript">
            $(document).ready(function() {
              $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    <script type="text/javascript">
     $(document).ready(function() {
        // Get sidebar height
        var sb_h = $('.sidebar').height();
        var mc_h = $('#main-content').height();
        if(sb_h > mc_h) {
          $('#main-content').css("min-height", sb_h+50 + "px");
        }

        $('.nav-sidebar li').on('shown.bs.collapse', function () {
           $(this).find(".glyphicon-menu-right")
                 .removeClass("glyphicon-menu-right")
                 .addClass("glyphicon-menu-down");
        });
        $('.nav-sidebar li').on('hidden.bs.collapse', function () {
           $(this).find(".glyphicon-menu-down")
                 .removeClass("glyphicon-menu-down")
                 .addClass("glyphicon-menu-right");
        });
     });
    </script>

    <!-- CODE INCLUDES -->
    <?php echo $cssincludes ?>
    </body>

    </html>