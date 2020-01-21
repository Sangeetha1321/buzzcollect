<ul class="newnav nav nav-sidebar">
           <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment)

           ) : ?>
               <!-- <li id="admin_sb">
                <a data-toggle="collapse" data-parent="#admin_sb" href="#admin_sb_c" class="collapsed <?php if(isset($activeLink['admin'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-wrench sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_157") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['admin'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="admin_sb_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['admin'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['admin']['general'])) echo "active" ?>"><a href="<?php echo site_url("admin") ?>"> <?php echo lang("ctn_682") ?></a></li>
					<?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
                      <li class="<?php if(isset($activeLink['admin']['settings'])) echo "active" ?>"><a href="<?php echo site_url("admin/settings") ?>"> <?php echo lang("ctn_158") ?></a></li>
                      <li class="<?php if(isset($activeLink['admin']['date'])) echo "active" ?>"><a href="<?php echo site_url("admin/date_settings") ?>"> <?php echo lang("ctn_1134") ?></a></li> 
					  <li class="<?php if(isset($activeLink['admin']['chat'])) echo "active" ?>"><a href="<?php echo site_url("admin/chat_settings") ?>"> <?php echo lang("ctn_1338") ?></a></li>
                    <?php endif; ?>
					
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['members'])) echo "active" ?>"><a href="<?php echo site_url("admin/members") ?>"> <?php echo lang("ctn_160") ?></a></li>
                    
					<?php endif; ?>
					
                    <?php if($this->user->info->admin) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_roles'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_roles") ?>"> <?php echo lang("ctn_316") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
                    <li class="<?php if(isset($activeLink['admin']['user_groups'])) echo "active" ?>"><a href="<?php echo site_url("admin/user_groups") ?>"> <?php echo lang("ctn_161") ?></a></li>
                    <?php endif; ?>
					
                    <?php if($this->user->info->admin) : ?>
                    <?php endif; ?>
                    <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
					<li class="<?php if(isset($activeLink['admin']['ipblock'])) echo "active" ?>"><a href="<?php echo site_url("admin/ipblock") ?>"> <?php echo lang("ctn_162") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['email_templates'])) echo "active" ?>"><a href="<?php echo site_url("admin/email_templates") ?>"> <?php echo lang("ctn_163") ?></a></li>
                    <li class="<?php if(isset($activeLink['admin']['email_members'])) echo "active" ?>"><a href="<?php echo site_url("admin/email_members") ?>"> <?php echo lang("ctn_164") ?></a></li>
                    <?php endif; ?>
					
				  </ul>
                </div>
              </li> -->
            <?php endif; ?>
            <li class="<?php if(isset($activeLink['home']['general'])) echo "active" ?>"><a href="<?php echo site_url() ?>"><!-- <span class="glyphicon glyphicon-home sidebar-icon sidebar-icon-blue"></span> --> <?php echo lang("ctn_688")  ?> <span class="sr-only">(current)</span></a></li>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "project_worker", "project_client"), $this->user)) : ?>
            <li id="projects_links">
                <a data-toggle="collapse" data-parent="#projects_links" href="#projects_links_c" class="collapsed <?php if(isset($activeLink['projects'])) echo "active" ?>" >
                  <!-- <span class="glyphicon glyphicon-filter sidebar-icon sidebar-icon-green"></span> --> <?php echo lang("ctn_689"); ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['projects'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="projects_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['projects'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                   <!-- <li class="<?php if(isset($activeLink['projects']['general'])) echo "active" ?>"><a href="<?php echo site_url("projects") ?>"> <?php echo lang("ctn_690") ?></a></li> -->
                    <?php if($this->common->has_permissions(array("admin", "project_admin"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['projects']['all'])) echo "active" ?>"><a href="<?php echo site_url("projects/all") ?>"> <?php echo "Source Onboarding";  ?></a></li>
                      <li class="<?php if(isset($activeLink['projects']['cats'])) echo "active" ?>"><a href="<?php echo site_url("projects/cats") ?>"> <?php echo lang("ctn_692") ?></a></li>
                      <!-- <li class="<?php if(isset($activeLink['projects']['custom_fields'])) echo "active" ?>"><a href="<?php echo site_url("projects/custom_fields") ?>"> <?php echo lang("ctn_714"); ?></a></li>
                    -->
					<?php endif; ?>
                  </ul>
                </div>
            </li>
          <?php endif; ?>
            <?php if($this->settings->info->enable_calendar && $this->common->has_permissions(array("admin", "project_admin", "calendar_worker", "calendar_manage"), $this->user)) : ?>
             <!-- <li id="calendar_links">
                  <a data-toggle="collapse" data-parent="#calendar_links" href="#calendar_links_c" class="collapsed <?php if(isset($activeLink['calendar'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-calendar sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_693") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['calendar'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="calendar_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['calendar'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['calendar']['general'])) echo "active" ?>"><a href="<?php echo site_url("calendar") ?>"> <?php echo lang("ctn_694") ?></a></li>
                       <?php if($this->common->has_permissions(array("admin", "project_admin", "calendar_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['calendar']['all'])) echo "active" ?>"><a href="<?php echo site_url("calendar/all") ?>"> <?php echo lang("ctn_695") ?></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
              </li> -->
            <?php endif; ?>
			
            <?php if($this->settings->info->enable_tasks && $this->common->has_permissions(array("admin", "project_admin", "task_worker", "task_manage", "task_client"), $this->user)) : ?>
            <!-- <li id="task_links">
                  <a data-toggle="collapse" data-parent="#task_links" href="#task_links_c" class="collapsed <?php if(isset($activeLink['task'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-tasks sidebar-icon sidebar-icon-pink"></span> <?php echo lang("ctn_696") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['task'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="task_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['task'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
					<?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['task']['general'])) echo "active" ?>"><a href="<?php echo site_url("tasks") ?>"> <?php echo lang("ctn_697") ?></a></li>
                    <?php endif; ?>
                    <?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage", "task_worker"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['task']['your'])) echo "active" ?>"><a href="<?php echo site_url("tasks/assigned") ?>"> <?php echo lang("ctn_698") ?></a></li>
                      <li class="<?php if(isset($activeLink['task']['templates'])) echo "active" ?>"><a href="<?php echo site_url("tasks/templates") ?>"><?php echo lang("ctn_723") ?></a></li>
                    <?php endif; ?>
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['task']['all'])) echo "active" ?>"><a href="<?php echo site_url("tasks/all") ?>"> <?php echo lang("ctn_699") ?></a></li>
                        <li class="<?php if(isset($activeLink['task']['archived'])) echo "active" ?>"><a href="<?php echo site_url("tasks/archived") ?>"><?php echo lang("ctn_1339") ?></a></li>
                      <?php endif; ?>
                      <?php if($this->common->has_permissions(array("task_client"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['task']['client'])) echo "active" ?>"><a href="<?php echo site_url("tasks/client") ?>"> <?php echo lang("ctn_1340") ?></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
              </li> -->
            <?php endif; ?>
			<?php if($this->settings->info->enable_team && $this->common->has_permissions(array("admin", "project_admin", "team_worker", "team_manage"), $this->user)) : ?>
             <!-- <li id="team_links">
                  <a data-toggle="collapse" data-parent="#team_links" href="#team_links_c" class="collapsed <?php if(isset($activeLink['team'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-user sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_1468") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['team'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="team_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['team'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['team']['general'])) echo "active" ?>"><a href="<?php echo site_url("team") ?>"> <?php echo lang("ctn_704") ?></a></li>
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['team']['all'])) echo "active" ?>"><a href="<?php echo site_url("team/all") ?>"> <?php echo lang("ctn_705") ?></a></li>
                        <li class="<?php if(isset($activeLink['team']['roles'])) echo "active" ?>"><a href="<?php echo site_url("team/roles") ?>"> <?php echo lang("ctn_706") ?></a></li>
                        <li class="<?php if(isset($activeLink['team']['all_users'])) echo "active" ?>"><a href="<?php echo site_url("team/users") ?>"> <?php echo lang("ctn_47") ?></a></li>
                      <?php endif; ?>
                      <li class="<?php if(isset($activeLink['team']['clients'])) echo "active" ?>"><a href="<?php echo site_url("team/clients") ?>"> <?php echo lang("ctn_1469") ?></a></li>
					  <li class="<?php if(isset($activeLink['team']['team_name'])) echo "active" ?>"><a href="<?php echo site_url("machine/team_name") ?>"> <?php echo lang("ctn_1630") ?></a></li>	
					  <li class="<?php if(isset($activeLink['team']['team_users'])) echo "active" ?>"><a href="<?php echo site_url("machine/team_users") ?>"> <?php echo "Team Vs Users"; ?></a></li>	
					</ul>
                  </div>
              </li> -->
            <?php endif; ?>
			  <!-- <li id="shift_allocation">
                <a data-toggle="collapse" data-parent="#shift" href="#shift_c" class="collapsed <?php if(isset($activeLink['machine'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-equalizer sidebar-icon sidebar-icon-yellow"></span> <?php echo lang("ctn_1604") ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['machine'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="shift_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['machine'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
				  <?php if($this->settings->info->enable_team && $this->common->has_permissions(array("admin", "project_admin", "team_worker", "team_manage"), $this->user)) : ?>
                    <li class="<?php if(isset($activeLink['machine']['general'])) echo "active" ?>"><a href="<?php echo site_url("machine") ?>"> <?php echo lang("ctn_1605") ?></a></li>
					<li class="<?php if(isset($activeLink['machine']['softwares'])) echo "active" ?>"><a href="<?php echo site_url("machine/softwares") ?>"> <?php echo lang("ctn_1606") ?></a></li>
					<li class="<?php if(isset($activeLink['machine']['shifts'])) echo "active" ?>"><a href="<?php echo site_url("machine/shifts") ?>"> <?php echo lang("ctn_1622") ?></a></li>
				  <?php endif; ?>
					<li class="<?php if(isset($activeLink['machine']['shift_users'])) echo "active" ?>"><a href="<?php echo site_url("machine/shift_users") ?>"> <?php echo lang("ctn_1631") ?></a></li>
				  </ul>
                </div>
            </li>			
			<?php if($this->settings->info->enable_calendar && $this->common->has_permissions(array("admin", "project_admin", "calendar_worker", "calendar_manage"), $this->user)) : ?>
              <li id="calendar_links">
                  <a data-toggle="collapse" data-parent="#calendar_links" href="#calendar_links_c" class="collapsed <?php if(isset($activeLink['calendar'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-calendar sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_693") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['calendar'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="calendar_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['calendar'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['calendar']['general'])) echo "active" ?>"><a href="<?php echo site_url("calendar") ?>"> <?php echo lang("ctn_694") ?></a></li>
                       <?php if($this->common->has_permissions(array("admin", "project_admin", "calendar_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['calendar']['all'])) echo "active" ?>"><a href="<?php echo site_url("calendar/all") ?>"> <?php echo lang("ctn_695") ?></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
			-->
            <?php if($this->settings->info->enable_files && $this->common->has_permissions(array("admin", "project_admin", "file_worker", "file_manage"), $this->user)) :  ?>
              <li id="file_links">
                  <a data-toggle="collapse" data-parent="#file_links" href="#file_links_c" class="collapsed <?php if(isset($activeLink['file'])) echo "active" ?>" >
                    <!-- <span class="glyphicon glyphicon-file sidebar-icon sidebar-icon-brown"></span> --> <?php echo lang("ctn_700") ?>
                    <span class="plus-sidebar">
						<span class="glyphicon <?php if(isset($activeLink['file'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span>
					</span>
                  </a>
                  <div id="file_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['file'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <!-- <li class="<?php if(isset($activeLink['file']['general'])) echo "active" ?>"><a href="<?php echo site_url("files") ?>"> <?php echo lang("ctn_701") ?></a></li>
                        <li class="<?php if(isset($activeLink['file']['monitor'])) echo "active" ?>"><a href="<?php echo site_url("files/monitor") ?>"> <?php echo lang("ctn_1638") ?></a></li> -->
                        <li class="<?php if(isset($activeLink['file']['general'])) echo "active" ?>"><a href="<?php echo site_url("files/add_file/0") ?>"> <?php echo "Add File"; ?></a></li> 
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['file']['all'])) echo "active" ?>"><a href="<?php echo site_url("files/all") ?>"> <?php echo lang("ctn_1638") ?></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
			
            <?php if($this->settings->info->enable_team && $this->common->has_permissions(array("admin", "project_admin", "team_worker", "team_manage"), $this->user)) : ?>
              <!-- <li id="team_links">
                  <a data-toggle="collapse" data-parent="#team_links" href="#team_links_c" class="collapsed <?php if(isset($activeLink['team'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-user sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_1468") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['team'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="team_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['team'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['team']['general'])) echo "active" ?>"><a href="<?php echo site_url("team") ?>"> <?php echo lang("ctn_704") ?></a></li>
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['team']['all'])) echo "active" ?>"><a href="<?php echo site_url("team/all") ?>"> <?php echo lang("ctn_705") ?></a></li>
                        <li class="<?php if(isset($activeLink['team']['roles'])) echo "active" ?>"><a href="<?php echo site_url("team/roles") ?>"> <?php echo lang("ctn_706") ?></a></li>
                        <li class="<?php if(isset($activeLink['team']['all_users'])) echo "active" ?>"><a href="<?php echo site_url("team/users") ?>"> <?php echo lang("ctn_47") ?></a></li>
                      <?php endif; ?>
                      <li class="<?php if(isset($activeLink['team']['clients'])) echo "active" ?>"><a href="<?php echo site_url("team/clients") ?>"> <?php echo lang("ctn_1469") ?></a></li>
                    </ul>
                  </div>
              </li> -->
            <?php endif; ?>
            <?php if($this->settings->info->enable_time && $this->common->has_permissions(array("admin", "project_admin", "time_worker", "time_manage"), $this->user)) : ?>
             <!-- <li id="time_links">
                  <a data-toggle="collapse" data-parent="#time_links" href="#time_links_c" class="collapsed <?php if(isset($activeLink['time'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-time sidebar-icon sidebar-icon-blue"></span> <?php echo lang("ctn_707") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['time'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="time_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['time'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['time']['general'])) echo "active" ?>"><a href="<?php echo site_url("time") ?>"> <?php echo lang("ctn_708") ?></a></li>
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "time_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['time']['all'])) echo "active" ?>"><a href="<?php echo site_url("time/all") ?>"> <?php echo lang("ctn_709") ?></a></li>
                      <?php endif; ?>
                      <li class="<?php if(isset($activeLink['time']['stats'])) echo "active" ?>"><a href="<?php echo site_url("time/stats") ?>"> <?php echo lang("ctn_710") ?></a></li>
                    </ul>
                  </div>
              </li> -->
            <?php endif; ?>
            <!-- <?php if($this->settings->info->enable_tickets && $this->common->has_permissions(array("admin", "project_admin", "ticket_worker", "ticket_manage", "ticket_client"), $this->user)) : ?>
              <li id="tickets_links">
                  <a data-toggle="collapse" data-parent="#tickets_links" href="#tickets_links_c" class="collapsed <?php if(isset($activeLink['tickets'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-send sidebar-icon sidebar-icon-green"></span> <?php echo lang("ctn_711") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['tickets'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="tickets_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['tickets'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                    <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['tickets']['general'])) echo "active" ?>"><a href="<?php echo site_url("tickets") ?>"> <?php echo lang("ctn_711") ?></a></li>
                      <li class="<?php if(isset($activeLink['tickets']['your'])) echo "active" ?>"><a href="<?php echo site_url("tickets/your") ?>"> <?php echo lang("ctn_712") ?></a></li>
                    <?php endif; ?>
                       <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['tickets']['departments'])) echo "active" ?>"><a href="<?php echo site_url("tickets/departments") ?>"> <?php echo lang("ctn_713") ?></a></li>
                        <li class="<?php if(isset($activeLink['tickets']['custom'])) echo "active" ?>"><a href="<?php echo site_url("tickets/custom") ?>"> <?php echo lang("ctn_714") ?></a></li>
                      <?php endif; ?>
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_client"), $this->user)) : ?>
                        <li class="<?php if(isset($activeLink['tickets']['client'])) echo "active" ?>"><a href="<?php echo site_url("tickets/client") ?>"> <?php echo lang("ctn_715") ?></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_finance && $this->common->has_permissions(array("admin", "project_admin", "finance_worker", "finance_manage"), $this->user)) : ?>
              <li id="finance_links">
                  <a data-toggle="collapse" data-parent="#finance_links" href="#finance_links_c" class="collapsed <?php if(isset($activeLink['finance'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-piggy-bank sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_716") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['finance'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="finance_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['finance'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['finance']['general'])) echo "active" ?>"><a href="<?php echo site_url("finance") ?>"> <?php echo lang("ctn_717") ?></a></li>
                       <?php if($this->common->has_permissions(array("admin", "project_admin", "finance_manage"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['finance']['all'])) echo "active" ?>"><a href="<?php echo site_url("finance/all") ?>"> <?php echo lang("ctn_718") ?></a></li>
                      <li class="<?php if(isset($activeLink['finance']['reoccuring'])) echo "active" ?>"><a href="<?php echo site_url("finance/reoccuring") ?>"> <?php echo lang("ctn_722"); ?></a></li>
                        <li class="<?php if(isset($activeLink['finance']['cats'])) echo "active" ?>"><a href="<?php echo site_url("finance/categories") ?>"> <?php echo lang("ctn_719") ?></a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_invoices && $this->common->has_permissions(array("admin", "project_admin", "invoice_manage", "invoice_client", "invoice_worker"), $this->user)) : ?>
              <li id="invoice_links">
                  <a data-toggle="collapse" data-parent="#invoice_links" href="#invoice_links_c" class="collapsed <?php if(isset($activeLink['invoice'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-credit-card sidebar-icon sidebar-icon-pink"></span> <?php echo lang("ctn_720") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['invoice'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="invoice_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['invoice'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                    <?php if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage", "invoice_worker"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['invoice']['general'])) echo "active" ?>"><a href="<?php echo site_url("invoices") ?>"> <?php echo lang("ctn_721") ?></a></li>
                    <?php endif; ?>
                      <?php if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['invoice']['reoccuring'])) echo "active" ?>"><a href="<?php echo site_url("invoices/reoccuring") ?>"> <?php echo lang("ctn_722") ?></a></li>
                      <li class="<?php if(isset($activeLink['invoice']['templates'])) echo "active" ?>"><a href="<?php echo site_url("invoices/templates") ?>"> <?php echo lang("ctn_723") ?></a></li>
                      <li class="<?php if(isset($activeLink['invoice']['pay'])) echo "active" ?>"><a href="<?php echo site_url("invoices/paying_accounts") ?>"> <?php echo lang("ctn_1341") ?></a></li>
                      <li class="<?php if(isset($activeLink['invoice']['items'])) echo "active" ?>"><a href="<?php echo site_url("invoices/items") ?>"> <?php echo lang("ctn_1470") ?></a></li>
                    <?php endif; ?>
                      <li class="<?php if(isset($activeLink['invoice']['client'])) echo "active" ?>"><a href="<?php echo site_url("invoices/client") ?>"> <?php echo lang("ctn_724") ?></a></li>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_notes && $this->common->has_permissions(array("admin", "project_admin", "notes_manage", "notes_worker"), $this->user)) : ?>
              <li id="notes_links">
                  <a data-toggle="collapse" data-parent="#notes_links" href="#notes_links_c" class="collapsed <?php if(isset($activeLink['notes'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-pencil sidebar-icon sidebar-icon-brown"></span> <?php echo lang("ctn_725") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['notes'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="notes_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['notes'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                    <?php if($this->common->has_permissions(array("admin", "project_admin", "notes_manage"), $this->user)) : ?>
                      <li class="<?php if(isset($activeLink['notes']['general'])) echo "active" ?>"><a href="<?php echo site_url("notes") ?>"> <?php echo lang("ctn_726") ?></a></li>
                    <?php endif; ?>
                      <li class="<?php if(isset($activeLink['notes']['your'])) echo "active" ?>"><a href="<?php echo site_url("notes/your") ?>"> <?php echo lang("ctn_727") ?></a></li>
                      <li class="<?php if(isset($activeLink['notes']['personal'])) echo "active" ?>"><a href="<?php echo site_url("notes/personal") ?>"> <?php echo lang("ctn_1471") ?></a></li>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_leads && $this->common->has_permissions(array("admin", "project_admin", "lead_manage"), $this->user)) : ?>
              <li id="quote_links">
                  <a data-toggle="collapse" data-parent="#lead_links" href="#lead_links_c" class="collapsed <?php if(isset($activeLink['lead'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-blackboard sidebar-icon sidebar-icon-red"></span> <?php echo lang("ctn_728") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['lead'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="lead_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['lead'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['lead']['general'])) echo "active" ?>"><a href="<?php echo site_url("leads") ?>"> <?php echo lang("ctn_728") ?></a></li>
                      <li class="<?php if(isset($activeLink['lead']['your'])) echo "active" ?>"><a href="<?php echo site_url("leads/your") ?>"> <?php echo lang("ctn_1342") ?></a></li>
                      <li class="<?php if(isset($activeLink['lead']['forms'])) echo "active" ?>"><a href="<?php echo site_url("leads/forms") ?>"> <?php echo lang("ctn_729") ?></a></li>
                      <li class="<?php if(isset($activeLink['lead']['manage'])) echo "active" ?>"><a href="<?php echo site_url("leads/manage") ?>"> <?php echo lang("ctn_1343") ?></a></li>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_services && $this->common->has_permissions(array("admin", "project_admin", "services_manage"), $this->user)) : ?>
              <li id="services_links">
                  <a data-toggle="collapse" data-parent="#services_links" href="#services_links_c" class="collapsed <?php if(isset($activeLink['services'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-th-list sidebar-icon sidebar-icon-blue"></span> <?php echo lang("ctn_1215") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['services'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="services_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['services'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['services']['general'])) echo "active" ?>"><a href="<?php echo site_url("services") ?>"> <?php echo lang("ctn_1215") ?></a></li>
                      <li class="<?php if(isset($activeLink['services']['orders'])) echo "active" ?>"><a href="<?php echo site_url("services/orders") ?>"> <?php echo lang("ctn_1216") ?></a></li>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_docs && $this->common->has_permissions(array("admin", "project_admin", "doc_manage", "doc_worker"), $this->user)) : ?>
              <li id="doc_links">
                  <a data-toggle="collapse" data-parent="#doc_links" href="#doc_links_c" class="collapsed <?php if(isset($activeLink['doc'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-blackboard sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_1527") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['doc'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="doc_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['doc'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['doc']['general'])) echo "active" ?>"><a href="<?php echo site_url("documentation") ?>"> <?php echo lang("ctn_1531") ?></a></li>
                      <li class="<?php if(isset($activeLink['doc']['order'])) echo "active" ?>"><a href="<?php echo site_url("documentation/order") ?>"> <?php echo lang("ctn_1532") ?></a></li>
                      <li class="<?php if(isset($activeLink['doc']['projects'])) echo "active" ?>"><a href="<?php echo site_url("documentation/projects") ?>"> <?php echo lang("ctn_689") ?></a></li>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
            <?php if($this->settings->info->enable_reports && $this->common->has_permissions(array("admin", "project_admin", "reports_manage", "reports_worker"), $this->user)) : ?>
              <li id="quote_links">
                  <a data-toggle="collapse" data-parent="#reports_links" href="#reports_links_c" class="collapsed <?php if(isset($activeLink['reports'])) echo "active" ?>" >
                    <span class="glyphicon glyphicon-list-alt sidebar-icon sidebar-icon-green"></span> <?php echo lang("ctn_1141") ?>
                    <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['reports'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                  </a>
                  <div id="reports_links_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['reports'])) echo "in" ?>">
                    <ul class="inner-sidebar-links">
                      <li class="<?php if(isset($activeLink['reports']['general'])) echo "active" ?>"><a href="<?php echo site_url("reports") ?>"> <?php echo lang("ctn_1146") ?></a></li>
                      <?php if($this->settings->info->enable_time) : ?>
                        <li class="<?php if(isset($activeLink['reports']['time'])) echo "active" ?>"><a href="<?php echo site_url("reports/time") ?>"> <?php echo lang("ctn_1147") ?></a></li>
                      <?php endif; ?>
                      <li class="<?php if(isset($activeLink['reports']['finance'])) echo "active" ?>"><a href="<?php echo site_url("reports/finance") ?>"> <?php echo lang("ctn_1148") ?></a></li>
                      <li class="<?php if(isset($activeLink['reports']['invoices'])) echo "active" ?>"><a href="<?php echo site_url("reports/invoices") ?>"> <?php echo lang("ctn_1149") ?></a></li>
                    </ul>
                  </div>
              </li>
            <?php endif; ?>
			-->
			<!--
			<?php if($this->common->has_permissions(array("admin", "project_admin", "project_worker", "project_client"), $this->user)) : ?>
            <li id="tivo">
                <a data-toggle="collapse" data-parent="#tivo" href="#tivo_c" class="collapsed <?php if(isset($activeLink['tivo'])) echo "active" ?>" >
                  <span class="glyphicon glyphicon-folder-open sidebar-icon sidebar-icon-green"></span> <?php echo "Tivo"; ?>
                  <span class="plus-sidebar"><span class="glyphicon <?php if(isset($activeLink['projects'])) : ?>glyphicon-menu-down<?php else : ?>glyphicon-menu-right<?php endif; ?>"></span></span>
                </a>
                <div id="tivo_c" class="panel-collapse collapse sidebar-links-inner <?php if(isset($activeLink['tivo'])) echo "in" ?>">
                  <ul class="inner-sidebar-links">
                    <li class="<?php if(isset($activeLink['tivo']['general'])) echo "active" ?>"><a href="<?php echo site_url("tivo") ?>"> <?php echo "Channels" ?></a></li>
                  </ul>
                </div>
            </li> 
          <?php endif; ?>
			
            <li class="<?php if(isset($activeLink['settings']['general'])) echo "active" ?>"><a href="<?php echo site_url("user_settings") ?>"><span class="glyphicon glyphicon-cog sidebar-icon sidebar-icon-orange"></span> <?php echo lang("ctn_156") ?></a></li>
          
          -->
          </ul>