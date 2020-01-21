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