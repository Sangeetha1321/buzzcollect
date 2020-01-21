<div id="responsive-menu-links">
          <select name='link' OnChange="window.location.href=$(this).val();" class="form-control">
          <option value='<?php echo site_url() ?>'><?php echo lang("ctn_154") ?></option>
          <option value='<?php echo site_url("members") ?>'><?php echo lang("ctn_155") ?></option>
          <option value='<?php echo site_url("user_settings") ?>'><?php echo lang("ctn_156") ?></option>
          <?php if($this->user->loggedin && isset($this->user->info->user_role_id) && 
           ($this->user->info->admin || $this->user->info->admin_settings || $this->user->info->admin_members || $this->user->info->admin_payment)

           ) : ?>
           <?php if($this->user->info->admin || $this->user->info->admin_settings) : ?>
            <option value='<?php echo site_url("admin/settings") ?>'><?php echo lang("ctn_158") ?></option>
            <option value='<?php echo site_url("admin/social_settings") ?>'><?php echo lang("ctn_159") ?></option>
            <option value='<?php echo site_url("admin/calendar_settings") ?>'><?php echo lang("ctn_683") ?></option>
            <option value='<?php echo site_url("admin/section_settings") ?>'><?php echo lang("ctn_684") ?></option>
            <option value='<?php echo site_url("admin/date_settings") ?>'><?php echo lang("ctn_1134") ?></option>
            <option value='<?php echo site_url("admin/chat_settings") ?>'><?php echo lang("ctn_1338") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/members") ?>'><?php echo lang("ctn_160") ?></option>
            <option value='<?php echo site_url("admin/custom_fields") ?>'><?php echo lang("ctn_714") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin) : ?>
            <option value='<?php echo site_url("admin/user_roles") ?>'><?php echo lang("ctn_316") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/user_groups") ?>'><?php echo lang("ctn_161") ?></option>
            <option value='<?php echo site_url("admin/ipblock") ?>'><?php echo lang("ctn_162") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin) : ?>
            <option value='<?php echo site_url("admin/tickets") ?>'><?php echo lang("ctn_685") ?></option>
            <option value='<?php echo site_url("admin/email_templates") ?>'><?php echo lang("ctn_163") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_members) : ?>
            <option value='<?php echo site_url("admin/email_members") ?>'><?php echo lang("ctn_164") ?></option>
            <?php endif; ?>
            <?php if($this->user->info->admin || $this->user->info->admin_payment) : ?>
            <option value='<?php echo site_url("admin/currencies") ?>'><?php echo lang("ctn_686") ?></option>
            <option value='<?php echo site_url("admin/payment_logs") ?>'><?php echo lang("ctn_288") ?></option>
            <option value='<?php echo site_url("admin/invoice") ?>'><?php echo lang("ctn_687") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          <option value='<?php echo site_url("projects") ?>'><?php echo lang("ctn_689") ?></option>
          <?php if($this->common->has_permissions(array("admin", "project_admin"), $this->user)) : ?>
            <option value='<?php echo site_url("projects/all") ?>'><?php echo lang("ctn_691") ?></option>
            <option value='<?php echo site_url("projects/cats") ?>'><?php echo lang("ctn_730") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_calendar && $this->common->has_permissions(array("admin", "project_admin", "calendar_worker", "calendar_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("calendar") ?>'><?php echo lang("ctn_694") ?></option>
            <option value='<?php echo site_url("calendar/all") ?>'><?php echo lang("ctn_695") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_tasks && $this->common->has_permissions(array("admin", "project_admin", "task_worker", "task_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("tasks") ?>'><?php echo lang("ctn_697") ?></option>
            <option value='<?php echo site_url("tasks/assigned") ?>'><?php echo lang("ctn_698") ?></option>
            <option value='<?php echo site_url("tasks/templates") ?>'><?php echo lang("ctn_723") ?></option>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "task_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("tasks/all") ?>'><?php echo lang("ctn_699") ?></option>
              <option value='<?php echo site_url("tasks/archived") ?>'><?php echo lang("ctn_1339") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($this->common->has_permissions(array("task_client"), $this->user)) : ?>
            <option value='<?php echo site_url("tasks/client") ?>'><?php echo lang("ctn_1340") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_files && $this->common->has_permissions(array("admin", "project_admin", "file_worker", "file_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("files") ?>'><?php echo lang("ctn_701") ?></option>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "file_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("files/all") ?>'><?php echo lang("ctn_702") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($this->settings->info->enable_team && $this->common->has_permissions(array("admin", "project_admin", "team_worker", "team_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("team") ?>'><?php echo lang("ctn_704") ?></option>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "team_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("team/all") ?>'><?php echo lang("ctn_705") ?></option>
              <option value='<?php echo site_url("team/roles") ?>'><?php echo lang("ctn_706") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($this->settings->info->enable_time && $this->common->has_permissions(array("admin", "project_admin", "time_worker", "time_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("time") ?>'><?php echo lang("ctn_708") ?></option>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "time_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("time/all") ?>'><?php echo lang("ctn_709") ?></option>
            <?php endif; ?>
            <option value='<?php echo site_url("time/stats") ?>'><?php echo lang("ctn_710") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_tickets && $this->common->has_permissions(array("admin", "project_admin", "ticket_worker", "ticket_manage", "ticket_client"), $this->user)) : ?>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_worker"), $this->user)) : ?>
              <option value='<?php echo site_url("tickets") ?>'><?php echo lang("ctn_711") ?></option>
              <option value='<?php echo site_url("tickets/your") ?>'><?php echo lang("ctn_712") ?></option>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("tickets/departments") ?>'><?php echo lang("ctn_713") ?></option>
              <option value='<?php echo site_url("tickets/custom_fields") ?>'><?php echo lang("ctn_714") ?></option>
            <?php endif; ?>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "ticket_manage", "ticket_client"), $this->user)) : ?>
              <option value='<?php echo site_url("tickets/client") ?>'><?php echo lang("ctn_715") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($this->settings->info->enable_finance && $this->common->has_permissions(array("admin", "project_admin", "finance_worker", "finance_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("finance") ?>'><?php echo lang("ctn_717") ?></option>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "finance_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("finance/all") ?>'><?php echo lang("ctn_718") ?></option>
              <option value='<?php echo site_url("finance/categories") ?>'><?php echo lang("ctn_719") ?></option>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($this->settings->info->enable_invoices && $this->common->has_permissions(array("admin", "project_admin", "invoice_manage", "invoice_client", "invoice_worker"), $this->user)) : ?>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "invoice_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("invoices") ?>'><?php echo lang("ctn_721") ?></option>
              <option value='<?php echo site_url("invoices/reoccuring") ?>'><?php echo lang("ctn_722") ?></option>
              <option value='<?php echo site_url("invoices/templates") ?>'><?php echo lang("ctn_723") ?></option>
              <option value='<?php echo site_url("invoices/paying_accounts") ?>'><?php echo lang("ctn_1341") ?></option>
            <?php endif; ?>
            <option value='<?php echo site_url("invoices/client") ?>'><?php echo lang("ctn_724") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_notes && $this->common->has_permissions(array("admin", "project_admin", "notes_manage", "notes_worker"), $this->user)) : ?>
            <?php if($this->common->has_permissions(array("admin", "project_admin", "notes_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("notes") ?>'><?php echo lang("ctn_726") ?></option>
            <?php endif; ?>
            <option value='<?php echo site_url("notes/personal") ?>'><?php echo lang("ctn_1471") ?></option>
            <option value='<?php echo site_url("notes/your") ?>'><?php echo lang("ctn_727") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_leads && $this->common->has_permissions(array("admin", "project_admin", "lead_manage"), $this->user)) : ?>
              <option value='<?php echo site_url("leads") ?>'><?php echo lang("ctn_728") ?></option>
              <option value='<?php echo site_url("leads/your") ?>'><?php echo lang("ctn_1342") ?></option>
              <option value='<?php echo site_url("leads/forms") ?>'><?php echo lang("ctn_729") ?></option>
              <option value='<?php echo site_url("leads/manage") ?>'><?php echo lang("ctn_1343") ?></option>
          <?php endif; ?>
          <?php if($this->settings->info->enable_services && $this->common->has_permissions(array("admin", "project_admin", "services_manage"), $this->user)) : ?>
            <option value='<?php echo site_url("services") ?>'><?php echo lang("ctn_1215") ?></option>
            <option value='<?php echo site_url("services/orders") ?>'><?php echo lang("ctn_1216") ?></option>
          <?php endif; ?>
           <?php if($this->settings->info->enable_docs && $this->common->has_permissions(array("admin", "project_admin", "doc_manage", "doc_worker"), $this->user)) : ?>
            <option value='<?php echo site_url("documentation") ?>'><?php echo lang("ctn_1531") ?></option>
            <option value='<?php echo site_url("documentation/order") ?>'><?php echo lang("ctn_1532") ?></option>
            <option value='<?php echo site_url("documentation/projects") ?>'><?php echo lang("ctn_689") ?></option>
          <?php endif; ?>
          
          <?php if($this->settings->info->enable_reports && $this->common->has_permissions(array("admin", "project_admin", "report_manage", "reports_worker"), $this->user)) : ?>
            <option value='<?php echo site_url("reports") ?>'><?php echo lang("ctn_1146") ?></option>
            <option value='<?php echo site_url("reports/time") ?>'><?php echo lang("ctn_1147") ?></option>
            <option value='<?php echo site_url("reports/finance") ?>'><?php echo lang("ctn_1148") ?></option>
            <option value='<?php echo site_url("reports/invoices") ?>'><?php echo lang("ctn_1149") ?></option>
          <?php endif; ?>
          </select> 
</div>