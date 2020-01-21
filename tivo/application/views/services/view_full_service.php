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
          <a class="navbar-brand" href="<?php echo site_url() ?>"><?php echo $this->settings->info->site_name ?> - <?php echo lang("ctn_1215") ?></a>
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
<?php echo form_open(site_url("services/process_form/" . $form->ID . "/1"), array("class" => "form-horizontal")) ?>
<p><?php echo $form->welcome ?></p>

<div class="service-cost">
<?php echo lang("ctn_1247") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($form->cost, 2) ?></strong>
</div>
<hr>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1248") ?> *</label>
        <div class="col-md-8 ui-front">
            <input type="email" class="form-control" name="email">
            <span class="help-block"><?php echo lang("ctn_1249") ?></span>
        </div>
</div>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1250") ?> *</label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="name">
        </div>
</div>
<?php foreach($fields->result() as $r) : ?>
<input type="hidden" id="field_price_<?php echo $r->ID ?>" value="<?php echo $r->cost ?>">
<?php if($r->type == 1) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <input type="text" class="form-control" name="field_id_<?php echo $r->ID ?>" id="field_id_<?php echo $r->ID ?>" value="">
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 2) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <textarea name="field_id_<?php echo $r->ID ?>" rows="8" class="form-control" id="field_id_<?php echo $r->ID ?>"></textarea>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 3) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <?php foreach($options as $k=>$v) : ?>
                <div class="form-group"><input type="checkbox" name="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>" value="1" id="field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>"> <?php echo $v ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 4) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <?php foreach($options as $k=>$v) : ?>
                <div class="form-group"><input type="radio" name="field_id_<?php echo $r->ID ?>" value="<?php echo $k ?>" id="field_radio_<?php echo $r->ID ?>_<?php echo $k ?>" class="field_radio_<?php echo $r->ID ?>"> <?php echo $v ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php elseif($r->type == 5) : ?>
<div class="form-group">
        <label for="p-in" class="col-md-4 label-heading"><?php echo $r->title ?> <?php if($r->required) : ?>*<?php endif; ?></label>
        <div class="col-md-8 ui-front">
            <?php $options = explode(",", $r->options); ?>
            <?php if(count($options) > 0) : ?>
                <select name="field_id_<?php echo $r->ID ?>" class="form-control" id="field_id_<?php echo $r->ID ?>">
                <option value="-1">None</option>
                <?php foreach($options as $k=>$v) : ?>
                <option value="<?php echo $k ?>"><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <span class="help-block"><?php echo $r->description ?></span>
            <?php if($r->cost > 0) : ?>
                <p><?php echo lang("ctn_1243") ?> <strong><?php echo $currency->symbol ?><?php echo number_format($r->cost, 2) ?></strong></p>
            <?php endif; ?>
        </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<p><?php echo lang("ctn_1251") ?>: <strong><?php echo $currency->symbol ?><span id="total_cost"><?php echo number_format($form->cost, 2) ?></span></strong></p>
<hr>
<p>* = <?php echo lang("ctn_803") ?>.</p>

<?php if($form->invoice) : ?>
<p><?php echo lang("ctn_1252") ?></p>
<?php endif; ?>


<input type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_1253") ?>">
<?php echo form_close() ?>
</div>
</div>

</div>

<script type="text/javascript">
var extras = [];
$(document).ready(function() {

var total = parseFloat($('#total_cost').html().replace(',', ''));
console.log(total);
<?php foreach($fields->result() as $r) : ?>

// Input Box
<?php if($r->type == 1 || $r->type == 2) : ?>
$('#field_id_<?php echo $r->ID ?>').on("change", function() {
    console.log("fired");
    var value = $('#field_id_<?php echo $r->ID ?>').val();
    var price = parseFloat($('#field_price_<?php echo $r->ID ?>').val().replace(',', ''));
    console.log(value.length);
    if(value.length > 0) {
        if(!check_in_array(<?php echo $r->ID ?>)) {
            // Get price and add it
            total = total + price;
            extras.push(<?php echo $r->ID ?>);
        }
    } else {
        // Remove from array
        if(check_in_array(<?php echo $r->ID ?>)) {
            total = total - price;
            remove_from_array(<?php echo $r->ID ?>);
        }
    }
    $('#total_cost').html(total);
});
<?php endif; ?>

// checkbox
<?php if($r->type == 3) : ?>
<?php $options = explode(",", $r->options); ?>
<?php if(count($options) > 0 ) : ?>
<?php foreach($options as $k=>$v) : ?>
$('#field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>').on("change", function() {
    var value = $('#field_checkbox_<?php echo $r->ID ?>_<?php echo $k ?>').prop("checked");
    console.log(value);
    var price = parseFloat($('#field_price_<?php echo $r->ID ?>').val().replace(',', ''));
    if(value) {
        if(!check_in_array('<?php echo $r->ID ?>+"_"+<?php echo $k ?>')) {
            // Get price and add it
            total = total + price;
            extras.push('<?php echo $r->ID ?>+"_"+<?php echo $k ?>');
        }
    } else {
        if(check_in_array('<?php echo $r->ID ?>+"_"+<?php echo $k ?>')) {
            // Get price and add it
            total = total - price;
            remove_from_array('<?php echo $r->ID ?>+"_"+<?php echo $k ?>');
        }
    }
    $('#total_cost').html(total);
});
<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>

// Radio
<?php if($r->type == 4) : ?>
$('.field_radio_<?php echo $r->ID ?>').on("change", function() {
    // check any of the radios
    var value_flag = false;
    <?php $options = explode(",", $r->options); ?>
    <?php if(count($options) > 0 ) : ?>
    <?php foreach($options as $k=>$v) : ?>
        var value = $('#field_radio_<?php echo $r->ID ?>_<?php echo $k ?>').prop("checked");
        if(value) {
            value_flag = true;
        }
    <?php endforeach; ?>
    <?php endif; ?>
    var price = parseFloat($('#field_price_<?php echo $r->ID ?>').val().replace(',', ''));
    if(value_flag) {
        if(!check_in_array(<?php echo $r->ID ?>)) {
            // Get price and add it
            total = total + price;
            extras.push(<?php echo $r->ID ?>);
        }
    } else {
        if(check_in_array(<?php echo $r->ID ?>)) {
            // Get price and add it
            total = total - price;
            remove_from_array(<?php echo $r->ID ?>);
        }
    }
    $('#total_cost').html(total);
});
<?php endif; ?>

<?php if($r->type == 5) : ?>
$('#field_id_<?php echo $r->ID ?>').on("change", function() {
    console.log("fired");
    var value = $('#field_id_<?php echo $r->ID ?>').val();
    var price = parseFloat($('#field_price_<?php echo $r->ID ?>').val().replace(',', ''));
    console.log(value);
    if(value > -1) {
        if(!check_in_array(<?php echo $r->ID ?>)) {
            // Get price and add it
            total = total + price;
            extras.push(<?php echo $r->ID ?>);
        }
    } else {
        // Remove from array
        if(check_in_array(<?php echo $r->ID ?>)) {
            total = total - price;
            remove_from_array(<?php echo $r->ID ?>);
        }
    }
    $('#total_cost').html(total);
});
<?php endif; ?>
<?php endforeach; ?>
});

function check_in_array(element) {
    for(var i =0;i<extras.length;i++) {
        if(extras[i] == element) {
            return true;
        }
    }
    return false;
}

function remove_from_array(element) {
    var index = extras.indexOf(element);
    if (index > -1) {
        extras.splice(index, 1);
    }
}
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