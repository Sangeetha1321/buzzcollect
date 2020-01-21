<!DOCTYPE html>
<?php if($enable_rtl) : ?>
<html dir="rtl">
<?php else : ?>
<html lang="en">
<?php endif; ?>
    <head>
        <title><?php if(isset($page_title)) : ?><?php echo $page_title ?> - <?php endif; ?><?php echo $this->settings->info->site_name ?></title>         
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="<?php echo base_url();?>bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

         <!-- Styles -->
        <link href="<?php echo base_url();?>styles/layouts/titan/main.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>styles/layouts/titan/responsive.css" rel="stylesheet" type="text/css">
        <?php if($this->settings->info->enable_chat && $this->common->has_permissions(array("admin", "live_chat"), $this->user)) : ?>
          <link href="<?php echo base_url();?>styles/chat.css" rel="stylesheet" type="text/css">
        <?php endif; ?>
        
        <link href="<?php echo base_url();?>styles/elements.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,500,550,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

        <!-- SCRIPTS -->
        <script type="text/javascript">
        var global_base_url = "<?php echo site_url('/') ?>";
        var global_hash = "<?php echo $this->security->get_csrf_hash() ?>";
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js "></script>

        <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
		<script src="<?php echo base_url();?>scripts/libraries/highcharts/highcharts.js"></script>
		<script src="<?php echo base_url();?>scripts/libraries/highcharts/funnel.js"></script>
		<script src="<?php echo base_url();?>scripts/libraries/highcharts/exporting.js"></script>
		<script src="<?php echo base_url();?>scripts/libraries/highcharts/export-data.js"></script>
    
        <?php if($this->settings->info->enable_chat && $this->common->has_permissions(array("admin", "live_chat"), $this->user)) : ?>
        <script src="<?php echo base_url() ?>scripts/custom/chat.js" type="text/javascript"></script>
        <script type="text/javascript">
         time_to_update = <?php echo $this->settings->info->chat_update ?>;
        </script>
        <?php endif; ?>

        
        <!-- CODE INCLUDES -->
        <?php echo $cssincludes ?> 

        <!-- Favicon: http://realfavicongenerator.net -->
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url() ?>images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>images/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo base_url() ?>images/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="<?php echo base_url() ?>images/favicon/manifest.json">
        <link rel="mask-icon" href="<?php echo base_url() ?>images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="theme-color" content="#ffffff">




        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <?php if(isset($datatable_lang) && !empty($datatable_lang)) : ?>
        <script type="text/javascript">
            $(document).ready(function() {
              $.extend( true, $.fn.dataTable.defaults, {
              "language": {
                "url": "<?php echo $datatable_lang ?>"
            }
              });
          });
        </script>
        <?php endif; ?>
        

    </head>
    <body>

    <nav class="navbar navbar-inverse navbar-header2">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php if($this->settings->info->logo_option) : ?>
          <a class="navbar-brand-two" href="<?php echo site_url() ?>" title="<?php echo $this->settings->info->site_name ?>"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->settings->info->site_logo ?>" width="123" height="32"></a>
        <?php else : ?>
          <a class="navbar-brand" href="<?php echo site_url() ?>" title="<?php echo $this->settings->info->site_name ?>"><?php echo $this->settings->info->site_name ?></a>
        <?php endif; ?>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
          <?php if($this->user->loggedin) : ?>
            <?php if($this->user->info->active_projectid > 0) : ?>
              <li class="user_bit">
              <img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->project_image ?>" class="user_avatar"> <a href="#" onclick="load_projects()" data-target="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="project-menu-drop"><?php echo $this->user->info->project_name ?></a> <span class="badge project-badge small-text"><a href="<?php echo site_url("projects/make_active/0") ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo lang("ctn_674") ?>"><span class="glyphicon glyphicon-remove"></span></a></span>

          <ul class="dropdown-menu" aria-labelledby="project-menu-drop">
            <li>
            <div class="notification-box-title">
            <?php echo lang("ctn_673") ?>
            </div>
            <div id="projects-scroll">
              <div id="loading_spinner_projects">
                <span class="glyphicon glyphicon-refresh" id="ajspinner_projects"></span>
              </div>
            </div>
            <div class="project-box-footer">
            <a href="<?php echo site_url("projects/make_active/0") ?>"><?php echo lang("ctn_674") ?></a>
            </div>
          </li>
          </ul>

              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_time && $this->common->has_permissions(array("admin", "project_admin", "time_worker", "time_manage"), $this->user)) : ?>
            <li><a href="#" data-target="#" onclick="load_timers()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="timer-menu-drop"><span class="glyphicon glyphicon-time notification-icon"></span><span class="badge timer-badge small-text" id="timer-count" <?php if($this->user->info->timer_count <= 0) : ?>style="display: none;"<?php endif; ?>><?php echo number_format($this->user->info->timer_count) ?></span></a>
        
            <ul class="dropdown-menu" aria-labelledby="time-menu-drop" id="time-menu-drop">
            <li>
            <div class="notification-box-title">
            <?php echo lang("ctn_675") ?> <input type="button" class="btn btn-primary btn-xs" value="<?php echo lang("ctn_676") ?>" id="start_timer_button" onclick="start_timer()"> 
            </div>
            <div id="timer-scroll">
              <div id="loading_spinner_timer">
                <span class="glyphicon glyphicon-refresh" id="ajspinner_timer"></span>
              </div>
            </div>
            <div class="notification-box-footer">
            <a href="<?php echo site_url("time") ?>"><?php echo lang("ctn_677") ?></a>
            </div>
          </li>
          </ul>
          </li>
        <?php endif; ?>
            <li><a href="#" data-target="#" onclick="load_notifications()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="noti-menu-drop"><span class="glyphicon glyphicon-bell notification-icon"></span><?php if($this->user->info->noti_count > 0) : ?><span class="badge notification-badge small-text"><?php echo $this->user->info->noti_count ?></span><?php endif; ?></a>
        
            <ul class="dropdown-menu" aria-labelledby="noti-menu-drop">
            <li>
            <div class="notification-box-title">
            <?php echo lang("ctn_678") ?> <?php if($this->user->info->noti_count > 0) : ?><span class="badge click" id="noti-click-unread" onclick="load_notifications_unread()"><?php echo $this->user->info->noti_count ?></span><?php endif; ?>
            </div>
            <div id="notifications-scroll">
              <div id="loading_spinner_notification">
                <span class="glyphicon glyphicon-refresh" id="ajspinner_notification"></span>
              </div>
            </div>
            <div class="notification-box-footer">
            <a href="<?php echo site_url("home/notifications") ?>"><?php echo lang("ctn_679") ?></a>
            </div>
          </li>
          </ul>
          </li>
            <li><a href="#" data-target="#" onclick="load_emails()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="email-menu-drop"><span class="glyphicon glyphicon-envelope notification-icon"></span><?php if($this->user->info->email_count > 0) : ?><span class="badge notification-badge small-text"><?php echo $this->user->info->email_count ?></span><?php endif; ?></a>

            <ul class="dropdown-menu" aria-labelledby="email-menu-drop">
            <li>
              <div class="notification-box-title">
                <?php echo lang("ctn_680") ?> <?php if($this->user->info->email_count > 0) : ?><span class="badge"><?php echo $this->user->info->email_count ?></span><?php endif; ?>
                </div>
                <div id="email-scroll">
                  <div id="loading_spinner_email">
                    <span class="glyphicon glyphicon-refresh" id="ajspinner_email"></span>
                  </div>
                </div>
                <div class="notification-box-footer">
                <a href="<?php echo site_url("mail") ?>"><?php echo lang("ctn_681") ?></a>
              </div>
            </li>
            </ul>

            </li>
            <li class="user_bit"><img src="<?php echo base_url() ?><?php echo $this->settings->info->upload_path_relative ?>/<?php echo $this->user->info->avatar ?>" class="user_avatar"> <a href="<?php echo site_url("user_settings") ?>"><?php echo $this->user->info->username ?></a></li>
            <li><a href="<?php echo site_url("login/logout/" . $this->security->get_csrf_hash()) ?>"><?php echo lang("ctn_149") ?></a></li>
          <?php else : ?>
          <li><a href="<?php echo site_url("login") ?>"><?php echo lang("ctn_150") ?></a></li>
            <li><a href="<?php echo site_url("register") ?>"><?php echo lang("ctn_151") ?></a></li>
          <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
	
<div class="wrapper">
    <div class="sidebar" id="sidebar">
      <?php if(isset($sidebar)) : ?>
          <?php echo $sidebar ?>
        <?php endif; ?>
          <?php include(APPPATH . "views/layout/sidebar_links.php") ?>
    </div>

    <div id="main-content" class="container-fluid">
		<button type="button" id="sidebarCollapse" class="navbar-btn">
			<span></span>
			<span></span>
			<span></span>
		</button>
        <?php include(APPPATH . "views/layout/mobile_links.php") ?>

        <?php if($this->settings->info->install) : ?>
          <div class="row">
                        <div class="col-md-12">
                                <div class="alert alert-info"><b>NOTICE</b> - <a href="<?php echo site_url("install") ?>">Great job on uploading all the files and setting up the site correctly! Let's now create the Admin account and set the default settings. Click here! This message will disappear once you have run the install process.</a></div>
                        </div>
                    </div>
        <?php endif; ?>
      <?php $gl = $this->session->flashdata('globalmsg'); ?>
        <?php if(!empty($gl)) :?>
                    <div class="row">
                        <div class="col-md-12">
                                <div class="alert alert-success"><b><span class="glyphicon glyphicon-ok"></span></b> <?php echo $this->session->flashdata('globalmsg') ?></div>
                        </div>
                    </div>
        <?php endif; ?>

        <?php echo $content ?>
    </div>
</div>

    <div id="footer" class="clearfix">
      <span class="pull-left">
		<?php echo lang("ctn_170") ?> 
		<a target="_blank" title="SPi Global" href="https://www.spi-global.com/">SPi Global</a> - <?php echo $this->settings->info->site_name ?> - <!-- <?php echo $this->settings->version ?> -->
	    <strong>Copyright &copy; <?php echo date("Y"); ?></strong>
	  </span> 
	  <span class="pull-right">
		<!-- <a href="<?php echo site_url("home/change_language") ?>"><?php echo lang("ctn_171") ?></a> 
		<strong>Copyright &copy; <?php echo date("Y"); ?> - <a title="SPi Global" href="https://www.spi-global.com/">SPi Global</a></strong> -->
	  </span>
    </div>

    <?php if($this->settings->info->enable_chat && $this->common->has_permissions(array("admin", "live_chat"), $this->user)) : ?>
      <?php include(APPPATH . "views/layout/chat.php"); ?>
    <?php endif; ?>

    <!-- SCRIPTS -->
    <?php if(isset($datepicker) && !empty($datepicker)) : ?>
    <script src="<?php echo base_url();?>scripts/libraries/datepicker/i18n/datepicker-<?php echo $datepicker ?>.js"></script>
    <?php endif; ?>
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

            $.fn.modal.Constructor.prototype.enforceFocus = function () {
    var $modalElement = this.$element;
    $(document).on('focusin.modal', function (e) {
        var $parent = $(e.target.parentNode);
        if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length
            // add whatever conditions you need here:
            &&
            !$parent.hasClass('cke_dialog_ui_input_select') && !$parent.hasClass('cke_dialog_ui_input_text')) {
            $modalElement.focus()
        }
    })
};
        </script>
        
    <script type="text/javascript">
     $(document).ready(function() {
        <?php if(isset($datepicker) && !empty($datepicker)) : ?>
        $('.datepicker').datepicker($.datepicker.regional[ "<?php echo $datepicker ?>" ]);
        <?php endif; ?>

        // Get sidebar height
        resize_layout();
        $('.datepicker').datepicker({dateFormat: '<?php echo $this->common->date_php_to_jquery($this->settings->info->date_picker_format) ?>'});
        var sb_h = $('.sidebar').height();
        var mc_h = $('#main-content').height();
        if(sb_h > mc_h) {
          $('#main-content').css("min-height", sb_h+50 + "px");
        }

        $('.nav-sidebar li').on('shown.bs.collapse', function () {
           $(this).find(".glyphicon-menu-right")
                 .removeClass("glyphicon-menu-right")
                 .addClass("glyphicon-menu-down");
            resize_layout();
        });
        $('.nav-sidebar li').on('hidden.bs.collapse', function () {
           $(this).find(".glyphicon-menu-down")
                 .removeClass("glyphicon-menu-down")
                 .addClass("glyphicon-menu-right");
            resize_layout();
        });

        function resize_layout() 
        {
          var sb_h = $('.sidebar').height();
          var mc_h = $('#main-content').height();
          var w_h = $(window).height();
          if(sb_h > mc_h) {
            $('#main-content').css("min-height", sb_h+50 + "px");
          }
          if(w_h > mc_h) {
            $('#main-content').css("min-height", (w_h-(51+30)) +"px");
          }
        }
     });
	 
	$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
    });
	});
    </script>
    </body>
</html>