<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_820") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("tasks/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_821") ?></a>
</div>
</div>

<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<div class="panel panel-default">
<div class="panel-body">

 <?php echo form_open(site_url("tasks/edit_task_pro/" . $task->ID), array("class" => "form-horizontal")) ?>
            <input type="hidden" name="jobid" value="<?php foreach($projects->result() as $r){if($task->projectid == $r->ID){ echo strtolower(substr($r->name, 0, 3)).$task->ID;}} ?>">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_823") ?></label>
                    <div class="col-md-8">
                        <input id="edit_task_name" type="text" class="form-control" name="name" value="<?php echo $task->name ?>">
                    </div>
            </div>
			<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1583") ?></label>
					<div class="col-md-8">
						<input id="edit_isbn" type="text" class="form-control required" name="isbn" value="<?php echo $task->isbn ?>" maxlength="13">
					</div>
			</div>
			<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1584") ?></label>
					<div class="col-md-8">
						<input id="edit_eisbn" type="text" class="form-control required" name="eisbn" value="<?php echo $task->eisbn ?>" maxlength="13">
					</div>
			</div>
			<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1585") ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="volume_issue" value="<?php echo $task->volume_issue ?>">
					</div>
			</div>
            <div class="form-group" style="display:none;">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_824") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="task-desc"><?php echo $task->description ?></textarea>
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1589") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="input" value="<?php echo $task->input ?>">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1590") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="output" value="<?php echo $task->output ?>">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" class="form-control" id="projectid">
                        <option value="-1"><?php echo lang("ctn_826") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($task->projectid == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
                    <div class="col-md-8 ui-front" id="append_input_unit">
                        <select name="unit" id="unit" class="form-control">
							<option value="">Select Unit</option>
							<?php foreach($pro_unit->result() as $r) : ?>
							<option value="<?php echo $r->unit; ?>" <?php if($task->unit == $r->unit) echo "selected" ?>><?php echo ucfirst($r->unit); ?></option>
							<?php endforeach; ?>
						</select>
						<!-- <input style="display:none;" readonly id="geofacets_unit" type="text" class="form-control" name="geofacets_unit" value="<?php echo "figure"; ?>"> -->
					</div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="complexity" id="project_complexity" class="form-control">
							<option value="">Select Complexity</option>
							<option value="simple" <?php if($task->complexity == 'simple') echo "selected" ?>>Simple</option>
							<option value="medium" <?php if($task->complexity == 'medium') echo "selected" ?>>Medium</option>
							<option value="complex" <?php if($task->complexity == 'complex') echo "selected" ?>>Complex</option>
							<option value="heavycomplex" <?php if($task->complexity == 'heavycomplex'){ echo "selected"; }?>>Heavy Complex</option>
						</select>
                    </div>
            </div>
			<div id="page">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1592") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="<?php echo $task->unitPricePerPage ?>" id="unitPricePerPage" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1594") ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="<?php echo $task->totalPages ?>" id="totalPages">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1596") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="<?php echo $task->totalPagesPrice ?>" id="totalPagesPrice" readonly >
                    </div>
            </div>
			</div>
			<div id="article">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1593") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="<?php echo $task->unitPricePerPage ?>" id="unitPricePerArticle" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1595") ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="<?php echo $task->totalPages ?>" id="totalArticles">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1597") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="<?php echo $task->totalPagesPrice ?>" id="totalArticlesPrice" readonly >
                    </div>
            </div>
			</div>
			<div id="table">
			<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Table Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerTableEditable" value="<?php echo $task->unitPricePerTableEditable ?>" id="unitPricePerTableEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalTablesEditable" value="<?php echo $task->totalTablesEditable ?>" id="totalTablesEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalTablesPriceEditable" value="<?php echo $task->totalTablesPriceEditable ?>" id="totalTablesPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Table Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerTableScanned" value="<?php echo $task->unitPricePerTableScanned ?>" id="unitPricePerTableScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalTablesScanned" value="<?php echo $task->totalTablesScanned ?>" id="totalTablesScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalTablesPriceScanned" value="<?php echo $task->totalTablesPriceScanned ?>" id="totalTablesPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="figure">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Figure Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerFigureEditable" value="<?php echo $task->unitPricePerFigureEditable ?>" id="unitPricePerFigureEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalFiguresEditable" value="<?php echo $task->totalFiguresEditable ?>" id="totalFiguresEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalFiguresPriceEditable" value="<?php echo $task->totalFiguresPriceEditable ?>" id="totalFiguresPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Figure Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerFigureScanned" value="<?php echo $task->unitPricePerFigureScanned ?>" id="unitPricePerFigureScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalFiguresScanned" value="<?php echo $task->totalFiguresScanned ?>" id="totalFiguresScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalFiguresPriceScanned" value="<?php echo $task->totalFiguresPriceScanned ?>" id="totalFiguresPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="image">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Image"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="<?php echo $task->unitPricePerPage ?>" id="unitPricePerImage" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Total Images"; ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="<?php echo $task->totalPages ?>" id="totalImages">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Images"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="<?php echo $task->totalPagesPrice ?>" id="totalImagesPrice" readonly >
                    </div>
            </div>
			</div>
			<div id="question">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Question"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="<?php echo $task->unitPricePerPage ?>" id="unitPricePerQuestion" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Total Questions"; ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="<?php echo $task->totalPages ?>" id="totalQuestions">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Questions"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="<?php echo $task->totalPagesPrice ?>" id="totalQuestionsPrice" readonly >
                    </div>
            </div>
			</div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_827") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="start_date" class="form-control datepicker" id="start_date" value="<?php echo date($this->settings->info->date_picker_format,$task->start_date) ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_828") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="due_date" class="form-control datepicker" id="due_date" value="<?php echo date($this->settings->info->date_picker_format,$task->due_date) ?>">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_829") ?></label>
                    <div class="col-md-8">
                        <select name="status" class="form-control">
                        <option value="1"><?php echo lang("ctn_830") ?></option>
                        <option value="2" <?php if($task->status == 2) echo "selected" ?>><?php echo lang("ctn_831") ?></option>
                        <option value="3" <?php if($task->status == 3) echo "selected" ?>><?php echo lang("ctn_832") ?></option>
                        <option value="4" <?php if($task->status == 4) echo "selected" ?>><?php echo lang("ctn_833") ?></option>
                        <option value="5" <?php if($task->status == 5) echo "selected" ?>><?php echo lang("ctn_834") ?></option>
                        <option value="6" <?php if($task->status == 6) echo "selected" ?>><?php echo lang("ctn_1633") ?></option>
                        <option value="7" <?php if($task->status == 7) echo "selected" ?>><?php echo lang("ctn_1634") ?></option>
                        <option value="8" <?php if($task->status == 8) echo "selected" ?>><?php echo lang("ctn_1635") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1380") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="archived" value="1" <?php if($task->archived) echo"checked" ?>>
                        <span class="help-text"><?php echo lang("ctn_1381") ?></span>
                    </div>
            </div>
			
            <hr>
            <h4><?php echo lang("ctn_1487") ?></h4>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1488") ?></label>
                    <div class="col-md-8">
                        <select name="template_option" class="form-control" id="template_option">
                            <option value="0"><?php echo lang("ctn_54") ?></option>
                            <?php if($this->common->has_permissions(array("admin", "project_admin",
         "task_manage"), 
            $this->user)) : ?>
                            <option value="1" <?php if($task->template == 1 && $task->template_projectid == 0) echo "selected" ?>><?php echo lang("ctn_1489") ?></option>
                        <?php endif; ?>
                            <option value="2" <?php if($task->template == 1 && $task->template_projectid > 0) echo "selected" ?>><?php echo lang("ctn_1490") ?></option>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1491") ?></span>
                    </div>
            </div>
			
            <div id="template_fields" style="<?php if($task->template == 0) : ?>display: none<?php endif; ?>">
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1492") ?></label>
                        <div class="col-md-8">
                            <input type="text" name="template_start_days" class="form-control" value="<?php echo $task->template_start_days ?>">
                            <span class="help-block"><?php echo lang("ctn_1493") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1494") ?></label>
                        <div class="col-md-8">
                            <input type="text" name="template_due_days" class="form-control" value="<?php echo $task->template_due_days ?>">
                            <span class="help-block"><?php echo lang("ctn_1495") ?></span>
                        </div>
                </div>
            </div>
			<?php 
			/* $pro_id = [];
			foreach($projects->result() as $pro){
				if($pro->process_name == 'Geofacets Table conversion' || $pro->process_name == 'Geofacets Figure conversion'){
					$pro_id[] = $pro->ID;
				}
			}
			print_r($pro_id); */
			?>
			
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel Process"; ?></label>
				<div class="col-md-8">
				<input type="checkbox" name="parallel" id="parallel" value="1" <?php if($task->parallel) echo "checked" ?> />
				<span class="help-text">Yes</span>
				</div>
            </div>
            <div id="parallel_process">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel Process Name"; ?></label>
                    <div class="col-md-8">
                        <input id="parallel_process_name" type="text" class="form-control" name="parallel_process_name"  value="<?php echo $task->parallel_process_name ?>">
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel Process Team" ?></label>
                    <div class="col-md-8">
                        <!-- <input id="parallel_process_team" type="text" class="form-control" name="parallel_process_team" value="<?php echo $task->parallel_process_team ?>"> -->
						<select name="parallel_process_team" id="parallel_process_team" class="form-control">
								<option value="">Select Parallel Process Team</option>
								<?php foreach($team_list as $r) : ?>
								<option value="<?php echo $r->team_name; ?>" <?php if($task->parallel_process_team == $r->team_name) echo "selected" ?>><?php echo ucfirst($r->team_name); ?></option>
								<?php endforeach; ?>
						</select>
                    </div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Cost Applicable"; ?></label>
					<div class="col-md-8">
					<input type="checkbox" name="cost_applicable" id="cost_applicable" value="1" <?php if($task->cost_applicable) echo "checked" ?> />
					<span class="help-text">Yes</span>
					</div>
				</div>
				<div id="cost">
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
							<div class="col-md-8 ui-front" id="append_parallel_input_unit">
								<select name="parallel_process_unit" id="parallel_process_unit" class="form-control">
									<option value="">Select Unit</option>
									<?php foreach($pro_unit->result() as $r) : ?>
									<option value="<?php echo $r->unit; ?>" <?php if($task->parallel_process_unit == $r->unit) echo "selected" ?>><?php echo ucfirst($r->unit); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
					</div>
					<div id="parallel_process_page">
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1592"); ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerPage" value="<?php echo $task->parallel_process_unitPricePerPage ?>" id="parallel_process_unitPricePerPage" >
							</div>
					</div>
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1594"); ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalPages" value="<?php echo $task->parallel_process_totalPages ?>" id="parallel_process_totalPages">
							</div>
					</div>
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1596"); ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalPagesPrice" value="<?php echo $task->parallel_process_totalPagesPrice ?>" id="parallel_process_totalPagesPrice" readonly >
							</div>
					</div>
					</div>
					<div id="parallel_process_article">
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1593"); ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerArticle" value="<?php echo $task->parallel_process_unitPricePerArticle ?>" id="parallel_process_unitPricePerArticle" >
							</div>
					</div>
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1595"); ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalArticles" value="<?php echo $task->parallel_process_totalArticles ?>" id="parallel_process_totalArticles">
							</div>
					</div>
					
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1597"); ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalArticlesPrice" value="<?php echo $task->parallel_process_totalArticlesPrice ?>" id="parallel_process_totalArticlesPrice" readonly >
							</div>
					</div>
					</div>
					<div id="parallel_process_table">
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Table Editable"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_unitPricePerTableEditable" value="<?php echo $task->parallel_process_unitPricePerTableEditable ?>" id="parallel_process_unitPricePerTableEditable" >
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Tables Editable"; ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="parallel_process_totalTablesEditable" value="<?php echo $task->parallel_process_totalTablesEditable ?>" id="parallel_process_totalTablesEditable">
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Tables Editable"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_totalTablesPriceEditable" value="<?php echo $task->parallel_process_totalTablesPriceEditable ?>" id="parallel_process_totalTablesPriceEditable" readonly >
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Table Scanned"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_unitPricePerTableScanned" value="<?php echo $task->parallel_process_unitPricePerTableScanned ?>" id="parallel_process_unitPricePerTableScanned" >
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Tables Scanned"; ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="parallel_process_totalTablesScanned" value="<?php echo $task->parallel_process_totalTablesScanned ?>" id="parallel_process_totalTablesScanned">
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Tables Scanned"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_totalTablesPriceScanned" value="<?php echo $task->parallel_process_totalTablesPriceScanned ?>" id="parallel_process_totalTablesPriceScanned" readonly >
						</div>
					</div>
					</div>
					<div id="parallel_process_figure">
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Figure Editable"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_unitPricePerFigureEditable" value="<?php echo $task->parallel_process_unitPricePerFigureEditable ?>" id="parallel_process_unitPricePerFigureEditable" >
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Figures Editable"; ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="parallel_process_totalFiguresEditable" value="<?php echo $task->parallel_process_totalFiguresEditable ?>" id="parallel_process_totalFiguresEditable">
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Figures Editable"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_totalFiguresPriceEditable" value="<?php echo $task->parallel_process_totalFiguresPriceEditable ?>" id="parallel_process_totalFiguresPriceEditable" readonly >
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Figure Scanned"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_unitPricePerFigureScanned" value="<?php echo $task->parallel_process_unitPricePerFigureScanned ?>" id="parallel_process_unitPricePerFigureScanned" >
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Figures Scanned"; ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="parallel_process_totalFiguresScanned" value="<?php echo $task->parallel_process_totalFiguresScanned ?>" id="parallel_process_totalFiguresScanned">
						</div>
					</div>
					<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Figures Scanned"; ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="parallel_process_totalFiguresPriceScanned" value="<?php echo $task->parallel_process_totalFiguresPriceScanned ?>" id="parallel_process_totalFiguresPriceScanned" readonly >
						</div>
					</div>
					</div>
					<div id="parallel_process_image">
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Image"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerImage" value="<?php echo $task->parallel_process_unitPricePerImage ?>" id="parallel_process_unitPricePerImage" >
							</div>
					</div>
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Images"; ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalImages" value="<?php echo $task->parallel_process_totalImages ?>" id="parallel_process_totalImages">
							</div>
					</div>
					<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Images"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalImagesPrice" value="<?php echo $task->parallel_process_totalImagesPrice ?>" id="parallel_process_totalImagesPrice" readonly >
							</div>
					</div>
					</div>	
				</div>
			</div>
			
            <div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor assignment"; ?></label>
				<div class="col-md-8">
				<input type="checkbox" name="vendor_assignment" id="vendor_assignment" value="1" <?php if($task->vendor_assignment) echo "checked" ?>/>
				<span class="help-text">Vendor</span>
				</div>
            </div>
            <div id="vendor">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Name"; ?></label>
                    <div class="col-md-8">
                        <input id="vendor_name" type="text" class="form-control" name="vendor_name" value="<?php echo $task->vendor_name ?>">
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Process Name" ?></label>
                    <div class="col-md-8">
                        <input id="vendor_process_name" type="text" class="form-control" name="vendor_process_name" value="<?php echo $task->vendor_process_name ?>">
                    </div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
						<div class="col-md-8 ui-front" id="append_vendor_input_unit">
							<select name="vendor_unit" id="vendor_unit" class="form-control">
								<option value="">Select Unit</option>
								<?php foreach($pro_unit->result() as $r) : ?>
								<option value="<?php echo $r->unit; ?>" <?php if($task->vendor_unit == $r->unit) echo "selected" ?>><?php echo ucfirst($r->unit); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
				</div>
				<div id="vendor_page">
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1592"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_unitPricePerPage" value="<?php echo $task->vendor_unitPricePerPage ?>" id="vendor_unitPricePerPage" >
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1594"); ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="vendor_totalPages" value="<?php echo $task->vendor_totalPages ?>" id="vendor_totalPages">
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1596"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_totalPagesPrice" value="<?php echo $task->vendor_totalPagesPrice ?>" id="vendor_totalPagesPrice" readonly >
						</div>
				</div>
				</div>
				<div id="vendor_article">
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1593"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_unitPricePerArticle" value="<?php echo $task->vendor_unitPricePerArticle ?>" id="vendor_unitPricePerArticle" >
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1595"); ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="vendor_totalArticles" value="<?php echo $task->vendor_totalArticles ?>" id="vendor_totalArticles">
						</div>
				</div>
				
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1597"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_totalArticlesPrice" value="<?php echo $task->vendor_totalArticlesPrice ?>" id="vendor_totalArticlesPrice" readonly >
						</div>
				</div>
				</div>
				<div id="vendor_table">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Table Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerTableEditable" value="<?php echo $task->vendor_unitPricePerTableEditable ?>" id="vendor_unitPricePerTableEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalTablesEditable" value="<?php echo $task->vendor_totalTablesEditable ?>" id="vendor_totalTablesEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalTablesPriceEditable" value="<?php echo $task->vendor_totalTablesPriceEditable ?>" id="vendor_totalTablesPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Table Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerTableScanned" value="<?php echo $task->vendor_unitPricePerTableScanned ?>" id="vendor_unitPricePerTableScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalTablesScanned" value="<?php echo $task->vendor_totalTablesScanned ?>" id="vendor_totalTablesScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalTablesPriceScanned" value="<?php echo $task->vendor_totalTablesPriceScanned ?>" id="vendor_totalTablesPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="vendor_figure">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Figure Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerFigureEditable" value="<?php echo $task->vendor_unitPricePerFigureEditable ?>" id="vendor_unitPricePerFigureEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalFiguresEditable" value="<?php echo $task->vendor_totalFiguresEditable ?>" id="vendor_totalFiguresEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalFiguresPriceEditable" value="<?php echo $task->vendor_totalFiguresPriceEditable ?>" id="vendor_totalFiguresPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Figure Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerFigureScanned" value="<?php echo $task->vendor_unitPricePerFigureScanned ?>" id="vendor_unitPricePerFigureScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalFiguresScanned" value="<?php echo $task->vendor_totalFiguresScanned ?>" id="vendor_totalFiguresScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalFiguresPriceScanned" value="<?php echo $task->vendor_totalFiguresPriceScanned ?>" id="vendor_totalFiguresPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="vendor_image">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Image"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="vendor_unitPricePerImage" value="<?php echo $task->vendor_unitPricePerImage ?>" id="vendor_unitPricePerImage" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Images"; ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="vendor_totalImages" value="<?php echo $task->vendor_totalImages ?>" id="vendor_totalImages">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Images"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="vendor_totalImagesPrice" value="<?php echo $task->vendor_totalImagesPrice ?>" id="vendor_totalImagesPrice" readonly >
                    </div>
            </div>
			</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Allocation Date"; ?></label>
						<div class="col-md-8">
							<input type="text" name="vendor_start_date" class="form-control datepicker" value="<?php if($task->vendor_start_date != 0){echo date($this->settings->info->date_picker_format,$task->vendor_start_date);} ?>" id="vendor_start_date">
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_828") ?></label>
						<div class="col-md-8">
							<input type="text" name="vendor_due_date" class="form-control datepicker" value="<?php  if($task->vendor_due_date != 0){echo date($this->settings->info->date_picker_format,$task->vendor_due_date);} ?>" id="vendor_due_date">
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Status"; ?></label>
						<div class="col-md-8">
							<select name="vendor_status" class="form-control">
							<option value=""><?php echo "Select Vendor status"; ?></option>
							<option value="1" <?php if($task->vendor_status == 1) echo "selected" ?>><?php echo "Vendor In progress"; ?></option>
							<option value="2" <?php if($task->vendor_status == 2) echo "selected" ?>><?php echo "Vendor YTS"; ?></option>
							<option value="3" <?php if($task->vendor_status == 3) echo "selected" ?>><?php echo "Vendor Completed"; ?></option>
							</select>
						</div>
				</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Chapters"; ?></label>
				<div class="col-md-8">
				<input type="checkbox" name="chapterwise" id="chapterwise" value="1" <?php if($task->chapterwise==1) echo "checked" ?>/>
				<span class="help-text">Chapters</span>
				</div>
            </div>
            <div id="chapterlist">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Chapter Name"; ?></label>
                    <div class="col-md-8">
                        <textarea style="width:100%;" id="chaptername" name="chaptername"><?php echo $task->chaptername; ?></textarea>
						<span class="help-text">Enter all the chapters of the title each seperated by <b>comma(,)</b></span>
					</div>
				</div>
			</div>
            <input id="edit_task" type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_843") ?>" />
            <?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#unitPricePerPage, #totalPages').keyup(function(){
		var rateperpage = parseFloat($('#unitPricePerPage').val()) || 0;
		var total_pages = parseFloat($('#totalPages').val()) || 0;
		$('#totalPagesPrice').val(rateperpage * total_pages);    
	});
	$('#unitPricePerArticle, #totalArticles').keyup(function(){
		var rateperarticle = parseFloat($('#unitPricePerArticle').val()) || 0;
		var total_articles = parseFloat($('#totalArticles').val()) || 0;
		$('#totalArticlesPrice').val(rateperarticle * total_articles);    
	});
	$('#unitPricePerTableEditable, #totalTablesEditable').keyup(function(){ 
		var ratepertbleditable = parseFloat($('#unitPricePerTableEditable').val()) || 0;
		var total_tableseditable = parseFloat($('#totalTablesEditable').val()) || 0;
		$('#totalTablesPriceEditable').val(ratepertbleditable * total_tableseditable);    
	});
	$('#unitPricePerTableScanned, #totalTablesScanned').keyup(function(){ 
		var ratepertblscanned = parseFloat($('#unitPricePerTableScanned').val()) || 0;
		var total_tablesscanned = parseFloat($('#totalTablesScanned').val()) || 0;
		$('#totalTablesPriceScanned').val(ratepertblscanned * total_tablesscanned);    
	});
	$('#unitPricePerFigureEditable, #totalFiguresEditable').keyup(function(){ 
		var rateperfigeditable = parseFloat($('#unitPricePerFigureEditable').val()) || 0;
		var total_figureseditable = parseFloat($('#totalFiguresEditable').val()) || 0;
		$('#totalFiguresPriceEditable').val(rateperfigeditable * total_figureseditable);    
	});
	$('#unitPricePerFigureScanned, #totalFiguresScanned').keyup(function(){ 
		var rateperfigscanned = parseFloat($('#unitPricePerFigureScanned').val()) || 0;
		var total_figuresscanned = parseFloat($('#totalFiguresScanned').val()) || 0;
		$('#totalFiguresPriceScanned').val(rateperfigscanned * total_figuresscanned);    
	});	
	$('#unitPricePerImage, #totalImages').keyup(function(){
		var rateperimage = parseFloat($('#unitPricePerImage').val()) || 0;
		var total_images = parseFloat($('#totalImages').val()) || 0;
		$('#totalImagesPrice').val(rateperimage * total_images);    
	});
	$('#unitPricePerQuestion, #totalQuestions').keyup(function(){
		var rateperquestions = parseFloat($('#unitPricePerQuestion').val()) || 0;
		var total_questions = parseFloat($('#totalQuestions').val()) || 0;
		$('#totalQuestionsPrice').val(rateperquestions * total_questions);    
	});
	$('#vendor_unitPricePerPage, #vendor_totalPages').keyup(function(){ 
		var vendorrateperpage = parseFloat($('#vendor_unitPricePerPage').val()) || 0;
		var vendortotal_pages = parseFloat($('#vendor_totalPages').val()) || 0;
		$('#vendor_totalPagesPrice').val(vendorrateperpage * vendortotal_pages);    
	});
	$('#vendor_unitPricePerArticle, #vendor_totalArticles').keyup(function(){ 
		var vendorrateperarticle = parseFloat($('#vendor_unitPricePerArticle').val()) || 0;
		var vendortotal_articles = parseFloat($('#vendor_totalArticles').val()) || 0;
		$('#vendor_totalArticlesPrice').val(vendorrateperarticle * vendortotal_articles);    
	});
	$('#vendor_unitPricePerTableEditable, #vendor_totalTablesEditable').keyup(function(){ 
		var vendorratepertbleditable = parseFloat($('#vendor_unitPricePerTableEditable').val()) || 0;
		var vendortotal_tableseditable = parseFloat($('#vendor_totalTablesEditable').val()) || 0;
		$('#vendor_totalTablesPriceEditable').val(vendorratepertbleditable * vendortotal_tableseditable);    
	});
	$('#vendor_unitPricePerTableScanned, #vendor_totalTablesScanned').keyup(function(){ 
		var vendorratepertblscanned = parseFloat($('#vendor_unitPricePerTableScanned').val()) || 0;
		var vendortotal_tablesscanned = parseFloat($('#vendor_totalTablesScanned').val()) || 0;
		$('#vendor_totalTablesPriceScanned').val(vendorratepertblscanned * vendortotal_tablesscanned);    
	});
	$('#vendor_unitPricePerFigureEditable, #vendor_totalFiguresEditable').keyup(function(){ 
		var vendorrateperfigeditable = parseFloat($('#vendor_unitPricePerFigureEditable').val()) || 0;
		var vendortotal_figureseditable = parseFloat($('#vendor_totalFiguresEditable').val()) || 0;
		$('#vendor_totalFiguresPriceEditable').val(vendorrateperfigeditable * vendortotal_figureseditable);    
	});
	$('#vendor_unitPricePerFigureScanned, #vendor_totalFiguresScanned').keyup(function(){ 
		var vendorrateperfigscanned = parseFloat($('#vendor_unitPricePerFigureScanned').val()) || 0;
		var vendortotal_figuresscanned = parseFloat($('#vendor_totalFiguresScanned').val()) || 0;
		$('#vendor_totalFiguresPriceScanned').val(vendorrateperfigscanned * vendortotal_figuresscanned);    
	});
	
	
	$('#parallel_process_unitPricePerPage, #parallel_process_totalPages').keyup(function(){ 
		var parallel_processrateperpage = parseFloat($('#parallel_process_unitPricePerPage').val()) || 0;
		var parallel_processtotal_pages = parseFloat($('#parallel_process_totalPages').val()) || 0;
		$('#parallel_process_totalPagesPrice').val(parallel_processrateperpage * parallel_processtotal_pages);    
	});
	$('#parallel_process_unitPricePerArticle, #parallel_process_totalArticles').keyup(function(){ 
		var parallel_processrateperarticle = parseFloat($('#parallel_process_unitPricePerArticle').val()) || 0;
		var parallel_processtotal_articles = parseFloat($('#parallel_process_totalArticles').val()) || 0;
		$('#parallel_process_totalArticlesPrice').val(parallel_processrateperarticle * parallel_processtotal_articles);    
	});
	$('#parallel_process_unitPricePerTableEditable, #parallel_process_totalTablesEditable').keyup(function(){ 
		var parallel_processratepertbleditable = parseFloat($('#parallel_process_unitPricePerTableEditable').val()) || 0;
		var parallel_processtotal_tableseditable = parseFloat($('#parallel_process_totalTablesEditable').val()) || 0;
		$('#parallel_process_totalTablesPriceEditable').val(parallel_processratepertbleditable * parallel_processtotal_tableseditable);    
	});
	$('#parallel_process_unitPricePerTableScanned, #parallel_process_totalTablesScanned').keyup(function(){ 
		var parallel_processratepertblscanned = parseFloat($('#parallel_process_unitPricePerTableScanned').val()) || 0;
		var parallel_processtotal_tablesscanned = parseFloat($('#parallel_process_totalTablesScanned').val()) || 0;
		$('#parallel_process_totalTablesPriceScanned').val(parallel_processratepertblscanned * parallel_processtotal_tablesscanned);    
	});
	$('#parallel_process_unitPricePerFigureEditable, #parallel_process_totalFiguresEditable').keyup(function(){ 
		var parallel_processrateperfigeditable = parseFloat($('#parallel_process_unitPricePerFigureEditable').val()) || 0;
		var parallel_processtotal_figureseditable = parseFloat($('#parallel_process_totalFiguresEditable').val()) || 0;
		$('#parallel_process_totalFiguresPriceEditable').val(parallel_processrateperfigeditable * parallel_processtotal_figureseditable);    
	});
	$('#parallel_process_unitPricePerFigureScanned, #parallel_process_totalFiguresScanned').keyup(function(){ 
		var parallel_processrateperfigscanned = parseFloat($('#parallel_process_unitPricePerFigureScanned').val()) || 0;
		var parallel_processtotal_figuresscanned = parseFloat($('#parallel_process_totalFiguresScanned').val()) || 0;
		$('#parallel_process_totalFiguresPriceScanned').val(parallel_processrateperfigscanned * parallel_processtotal_figuresscanned);    
	});
});	
$(function() {
    if($('#unit').val() == 'Page') {
        $('#page').show(); 
		$('#article').hide(); 
		$('#table').hide(); 
		$('#figure').hide(); 
		$('#image').hide(); 
		$('#question').hide(); 
    }
	else if($('#unit').val() == 'Article'){
		$('#page').hide(); 
		$('#article').show(); 
		$('#table').hide(); 
		$('#figure').hide(); 
		$('#image').hide(); 
		$('#question').hide(); 
	}
	else if($('#unit').val() == 'table'){
		$('#page').hide(); 
		$('#article').hide(); 
		$('#table').show(); 
		$('#figure').hide(); 
		$('#image').hide(); 
		$('#question').hide(); 
	}
	else if($('#unit').val() == 'figure'){
		$('#page').hide(); 
		$('#article').hide(); 
		$('#table').hide(); 
		$('#figure').show(); 
		$('#image').hide(); 
		$('#question').hide(); 
	}
	else if($('#unit').val() == 'image'){
		$('#page').hide(); 
		$('#article').hide(); 
		$('#table').hide(); 
		$('#figure').hide(); 
		$('#image').show(); 
		$('#question').hide(); 
	}
	else {
		$('#page').hide();
		$('#article').hide(); 
		$('#table').hide(); 
		$('#figure').hide(); 
		$('#image').hide(); 
		$('#question').show(); 
    } 
   $('#unit').change(function(){
        if($('#unit').val() == 'Page') {
            $('#page').show(); 
        } else {
            $('#page').hide(); 
        } 
   });
   $('#unit').change(function(){
        if($('#unit').val() == 'Article') {
            $('#article').show(); 
        } else {
            $('#article').hide(); 
        } 
   });
   $('#unit').change(function(){
        if($('#unit').val() == 'table') {
            $('#table').show(); 
        } else { 
			$('#table').hide(); 
        } 
    }); 
   $('#unit').change(function(){
        if($('#unit').val() == 'figure') {
			$('#figure').show(); 
        } else {
			$('#figure').hide(); 
        } 
    });    	
	$('#unit').change(function(){
        if($('#unit').val() == 'image') {
            $('#image').show(); 
        } else {
            $('#image').hide(); 
        } 
    }); 
	$('#unit').change(function(){
        if($('#unit').val() == 'Question') {
            $('#question').show(); 
        } else {
            $('#question').hide(); 
        } 
    }); 
});


$(function() {
    var unit_val = $('#unit').val();
	if(typeof(unit_val) != "undefined" && unit_val !== null){
        $('#vendor_unit').val(unit_val);
		$('#vendor_unit').find('option[value="'+unit_val+'"]').attr('selected','selected');
		$('#parallel_process_unit').val(unit_val);
		$('#parallel_process_unit').find('option[value="'+unit_val+'"]').attr('selected','selected');
		/* $('#vendor_unit').prop('disabled', true);
		$("#vendor").append("<input name='vendor_unit' value='"+unit_val+"' type='hidden' id='hidden_input' >"); */
    }
	
	if($('#vendor_assignment').is(':checked')) {
    $('#vendor').show(); 
	} 
	else{
	$('#vendor').hide(); 
	} 
    $('#vendor_assignment').click(function(){
	if(this.checked) {
	    $('#vendor').show(); 
	} else {
		$('#vendor').hide();
	}
    }); 

    if($('#parallel').is(':checked')) {
    $('#parallel_process').show(); 
	} 
	else{
	$('#parallel_process').hide(); 
	}
    $('#parallel').click(function(){
	if(this.checked) {
	    $('#parallel_process').show(); 
	} else {
		$('#parallel_process').hide();
	}
    });
	
	if($('#chapterwise').is(':checked')) {
    $('#chapterlist').show(); 
	} 
	else{
	$('#chapterlist').hide(); 
	}
    $('#chapterwise').click(function(){
	if(this.checked) {
	    $('#chapterlist').show(); 
	} else {
		$('#chapterlist').hide();
	}
    });
});

$(function() {
	if($('#vendor_unit').val() == 'Page') {
        $('#vendor_page').show(); 
		$('#vendor_article').hide(); 
		$('#vendor_table').hide(); 
		$('#vendor_figure').hide(); 
		$('#vendor_image').hide(); 
    }
	else if($('#vendor_unit').val() == 'Article'){
		$('#vendor_page').hide(); 
		$('#vendor_article').show(); 
		$('#vendor_table').hide(); 
		$('#vendor_figure').hide(); 
		$('#vendor_image').hide(); 
	}
	else if($('#vendor_unit').val() == 'table'){
		$('#vendor_page').hide(); 
		$('#vendor_article').hide(); 
		$('#vendor_table').show(); 
		$('#vendor_figure').hide(); 
		$('#vendor_image').hide(); 
	}
	else if($('#vendor_unit').val() == 'figure'){
		$('#vendor_page').hide(); 
		$('#vendor_article').hide(); 
		$('#vendor_table').hide(); 
		$('#vendor_figure').show(); 
		$('#vendor_image').hide(); 
	}
	else {
		$('#vendor_page').hide();
		$('#vendor_article').hide(); 
		$('#vendor_table').hide(); 
		$('#vendor_figure').hide(); 
		$('#vendor_image').show(); 		
    } 
	
   $('#vendor_unit').change(function(){
        if($('#vendor_unit').val() == 'Page') {
            $('#vendor_page').show(); 
        } else {
            $('#vendor_page').hide(); 
        } 
   });
   $('#vendor_unit').change(function(){
        if($('#vendor_unit').val() == 'Article') {
            $('#vendor_article').show(); 
        } else {
            $('#vendor_article').hide(); 
        } 
   });
   $('#vendor_unit').change(function(){
        if($('#vendor_unit').val() == 'table') {
            $('#vendor_table').show(); 
        } else {
            $('#vendor_table').hide(); 
        } 
    });
   $('#vendor_unit').change(function(){
        if($('#vendor_unit').val() == 'figure') {
			$('#vendor_figure').show(); 
        } else {
			$('#vendor_figure').hide(); 
        } 
    }); 
	$('#vendor_unit').change(function(){
        if($('#vendor_unit').val() == 'image') {
            $('#vendor_image').show(); 
        } else {
            $('#vendor_image').hide(); 
        } 
    });
});

$(function() {
	if($('#parallel_process_unit').val() == 'Page') {
        $('#parallel_process_page').show(); 
		$('#parallel_process_article').hide(); 
		$('#parallel_process_table').hide(); 
		$('#parallel_process_figure').hide(); 
		$('#parallel_process_image').hide(); 
    }
	else if($('#parallel_process_unit').val() == 'Article'){
		$('#parallel_process_page').hide(); 
		$('#parallel_process_article').show(); 
		$('#parallel_process_table').hide(); 
		$('#parallel_process_figure').hide(); 
		$('#parallel_process_image').hide(); 
	}
	else if($('#parallel_process_unit').val() == 'table'){
		$('#parallel_process_page').hide(); 
		$('#parallel_process_article').hide(); 
		$('#parallel_process_table').show(); 
		$('#parallel_process_figure').hide(); 
		$('#parallel_process_image').hide(); 
	}
	else if($('#parallel_process_unit').val() == 'figure'){
		$('#parallel_process_page').hide(); 
		$('#parallel_process_article').hide(); 
		$('#parallel_process_table').hide(); 
		$('#parallel_process_figure').show(); 
		$('#parallel_process_image').hide(); 
	}
	else {
		$('#parallel_process_page').hide();
		$('#parallel_process_article').hide(); 
		$('#parallel_process_table').hide(); 
		$('#parallel_process_figure').hide(); 
		$('#parallel_process_image').show(); 		
    } 
    $('#parallel_process_unit').change(function(){
        if($('#parallel_process_unit').val() == 'Page') {
            $('#parallel_process_page').show(); 
        } else {
            $('#parallel_process_page').hide(); 
        } 
    });
    $('#parallel_process_unit').change(function(){
        if($('#parallel_process_unit').val() == 'Article') {
            $('#parallel_process_article').show(); 
        } else {
            $('#parallel_process_article').hide(); 
        } 
    });/* 
	$('#parallel_process_unit').change(function(){
        if($('#parallel_process_unit').val() == 'table' || $('#parallel_process_unit').val() == 'figure') {
            $('#parallel_process_table').show(); 
			$('#parallel_process_figure').show(); 
        } else {
            $('#parallel_process_table').hide(); 
			$('#parallel_process_figure').hide(); 
        } 
    });  */
	$('#parallel_process_unit').change(function(){
        if($('#parallel_process_unit').val() == 'table') {
            $('#parallel_process_table').show(); 
        } else {
            $('#parallel_process_table').hide();  
        } 
    }); 
	$('#parallel_process_unit').change(function(){
        if($('#parallel_process_unit').val() == 'figure') {
			$('#parallel_process_figure').show(); 
        } else {
			$('#parallel_process_figure').hide(); 
        } 
    }); 
	$('#parallel_process_unit').change(function(){
        if($('#parallel_process_unit').val() == 'image') {
            $('#parallel_process_image').show(); 
        } else {
            $('#parallel_process_image').hide(); 
        } 
    }); 
});

$(function() {
	if($('#cost_applicable').is(':checked')) {
    $('#cost').show(); 
	} 
	else{
	$('#cost').hide(); 
	}
    $('#cost_applicable').click(function(){
	if(this.checked) {
	    $('#cost').show(); 
	} else {
		$('#cost').hide();
	}
    });
});

/* $(function () {
	$("input[id*='unitPricePerPage'],input[id*='unitPricePerArticle']").keydown(function (event) {
		if (event.shiftKey == true) {
			event.preventDefault();
		}
		if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
		} else {
			event.preventDefault();
		}
		if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
			event.preventDefault();
	});
}); */
</script>

<script type="text/javascript">
$('#edit_task_name,#edit_isbn,#edit_eisbn').keyup(function() {
	$.ajax({
		url : "<?php echo site_url("tasks/check_task_details") ?>",
		type : 'GET',
		data : {
		  taskname : $("#edit_task_name").val(),
		  isbn : $("#edit_isbn").val(),
		  eisbn : $("#edit_eisbn").val(),
		},
		dataType: 'JSON',
		success: function(data) {
			if(data.success) {
			if($("#edit_task_name").val() != ''){
				$("#edit_task_name").css("border-color", "green");
				$("#edit_task_name").css("box-shadow", "inset 0 1px 1px green");
			}	
			if($("#edit_isbn").val() != ''){
				$("#edit_isbn").css("border-color", "green");
				$("#edit_isbn").css("box-shadow", "inset 0 1px 1px green");
			}
			if($("#edit_eisbn").val() != ''){				
				$("#edit_eisbn").css("border-color", "green");
				$("#edit_eisbn").css("box-shadow", "inset 0 1px 1px green");
			}	
				$('#edit_task').prop('disabled', false);
			} else {
				$("#edit_task_name").css("border-color", "red");
				$("#edit_isbn").css("border-color", "red");
				$("#edit_eisbn").css("border-color", "red");
				$("#edit_task_name").css("box-shadow", "inset 0 1px 1px red");
				$("#edit_isbn").css("box-shadow", "inset 0 1px 1px red");
				$("#edit_eisbn").css("box-shadow", "inset 0 1px 1px red");
				$('#edit_task').prop('disabled', true);
				if(data.field_errors) {
					var errors = data.fieldErrors;
					for (var property in errors) {
						if (errors.hasOwnProperty(property)) {
							// Find form name
							var field_name = '#' + form + ' input[name="'+property+'"]';
							$(field_name).addClass("errorField");
							if(errors[property]) {
								// Get input group of field
								$(field_name).parent().closest('.form-group').after('<div class="form-error-no-margin">'+errors[property]+'</div>');
							}
						}
					}
				}
			}
		}
	});
}); 
</script>
<script type="text/javascript">
$(document).ready(function() {
	CKEDITOR.replace('task-desc', { height: '100'});
});
</script>
<script type="text/javascript">
	$('#project_complexity').change(function() {
    	$.ajax({
	        url : "<?php echo site_url("tasks/ajaxgetunitprice") ?>",
	        type : 'GET',
	        data : {
	          complexity : $("#project_complexity").val(),
	          unit : $("#unit").val(),
	          geofacets_unit : $("#geofacets_unit").val(),
	          projectid : $("#projectid").val(),
	        },
	        dataType: 'JSON',
	        success: function(data) {
			/* console.log(data); */
			if(data.unitval == 'Page'){
					$("#unitPricePerPage").val(data.unitprice);
					$("#unitPricePerPage").click();
				}else if(data.unitval == 'Article'){
					$("#unitPricePerArticle").val(data.unitprice);
					$("#unitPricePerArticle").click();
				}else if(data.unitval == 'table'){
					$("#unitPricePerTableEditable").val(data.unitprice_geo_tbl_editable);
					$("#unitPricePerTableScanned").val(data.unitprice_geo_tbl_scanned);
					$("#unitPricePerTableEditable").click();
					$("#unitPricePerTableScanned").click();
				}else if(data.unitval == 'figure'){
					$("#unitPricePerFigureEditable").val(data.unitprice_geo_fig_editable);
					$("#unitPricePerFigureScanned").val(data.unitprice_geo_fig_scanned);
					$("#unitPricePerFigureEditable").click();
					$("#unitPricePerFigureScanned").click();
				}else if(data.unitval == 'image'){
					$("#unitPricePerImage").val(data.unitprice);
					$("#unitPricePerImage").click();
				}else if(data.unitval == 'Question'){
					$("#unitPricePerQuestion").val(data.unitprice);
					$("#unitPricePerQuestion").click();
				}else{
					$("#unitPricePerPage").val(data.unitprice);
					$("#unitPricePerPage").click();
				}
	        },
			error : function(data) {
				console.log('error');
			}
	    });
    });  
</script>
<script type="text/javascript">
$(document).ready(function () {
	$('#projectid').change(function() {
		$.ajax({
	        url : "<?php echo site_url("tasks/ajaxgetdetails") ?>",
	        type : 'GET',
	        data : {
	          projectid : $("#projectid").val()
	        },
	        dataType: 'JSON',
	        success: function(data) {
				if((data.publisher=="Elsevier") && (data.process_name=="Geofacets Table conversion" || data.process_name=="Geofacets Figure conversion")){
					console.log(data);
					$("#unit").val('table').trigger('change');
					$('#unit').prop('disabled', true);
					$("#append_input_unit").append("<input readonly class='form-control' name='geofacets_unit' value='figure' type='text' id='geofacets_unit' >");
					$("#append_input_unit").append("<input name='unit' value='table' type='hidden' id='hid_input' >");
					$('#table').show(); 
					$('#figure').show(); 
					
					$("#parallel_process_unit").val('table').trigger('change');
					$('#parallel_process_unit').prop('disabled', true);
					$("#append_parallel_input_unit").append("<input readonly class='form-control' name='geofacets_parallel_process_unit' value='figure' type='text' id='geofacets_parallel_process_unit' >");
					$("#append_parallel_input_unit").append("<input name='parallel_process_unit' value='table' type='hidden' id='hid_parallel_input' >");
					$('#parallel_process_table').show(); 
					$('#parallel_process_figure').show(); 
					
					$("#vendor_unit").val('table').trigger('change');
					$('#vendor_unit').prop('disabled', true);
					$("#append_vendor_input_unit").append("<input readonly class='form-control' name='geofacets_vendor_unit' value='figure' type='text' id='geofacets_vendor_unit' >");
					$("#append_vendor_input_unit").append("<input name='vendor_unit' value='table' type='hidden' id='hid_vendor_input' >");
					$('#vendor_table').show(); 
					$('#vendor_figure').show(); 
				}
				else{
					$('#unit').prop('disabled', false);
					$("#geofacets_unit").remove();
					$("#hid_input").remove();
					
					$('#parallel_process_unit').prop('disabled', false);
					$("#geofacets_parallel_unit").remove();
					$("#hid_parallel_input").remove();
					
					$('#vendor_unit').prop('disabled', false);
					$("#geofacets_vendor_unit").remove();
					$("#hid_vendor_input").remove();
					
					$('#table').hide(); 
					$('#figure').hide(); 
					$('#parallel_process_table').hide(); 
					$('#parallel_process_figure').hide(); 
					$('#vendor_table').hide(); 
					$('#vendor_figure').hide(); 
				} 
	        },
			error : function(data) {
				console.log('error');
				$('#unit').prop('disabled', false);
				$("#geofacets_unit").remove();
				$("#hid_input").remove();
				
				$('#parallel_process_unit').prop('disabled', false);
				$("#geofacets_parallel_unit").remove();
				$("#hid_parallel_input").remove();
				
				$('#vendor_unit').prop('disabled', false);
				$("#geofacets_vendor_unit").remove();
				$("#hid_vendor_input").remove();
				
				$('#table').hide(); 
				$('#figure').hide(); 
				$('#parallel_process_table').hide(); 
				$('#parallel_process_figure').hide(); 
				$('#vendor_table').hide(); 
				$('#vendor_figure').hide(); 
			}
	    }); 
    });
});
</script>	