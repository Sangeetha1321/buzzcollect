<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-tasks"></span> <?php echo lang("ctn_820") ?></div>
    <div class="db-header-extra"> <a href="<?php echo site_url("tasks/add") ?>" class="btn btn-primary btn-sm"><?php echo lang("ctn_821") ?></a>
</div>
</div>
<button class="backbutton btn btn-primary" onclick="history.go(-1);">Back </button>

<p><?php echo lang("ctn_822") ?></p>

<div class="panel panel-default">
<div class="panel-body">

 <?php echo form_open(site_url("tasks/add_task_process"), array("class" => "form-horizontal")) ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_823") ?></label>
                    <div class="col-md-8">
                        <input id="task_name" type="text" class="form-control" name="name" value="">
                    </div>
            </div>
            <div class="form-group" style="display:none;">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_824") ?></label>
                    <div class="col-md-8">
                        <textarea name="description" id="task-desc"></textarea>
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1583") ?></label>
                    <div class="col-md-8">
                        <input id="isbn" type="text" class="form-control required" name="isbn" value="" maxlength="13">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1584") ?></label>
                    <div class="col-md-8">
                        <input id="eisbn" type="text" class="form-control required" name="eisbn" value="" maxlength="13">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1585") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="volume_issue" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1589") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="input" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1590") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="output" value="">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_825") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="projectid" id="projectid" class="form-control">
                        <option value="-1"><?php echo lang("ctn_826") ?></option>
                        <?php foreach($projects->result() as $r) : ?>
                        	<option value="<?php echo $r->ID ?>" <?php if($this->user->info->active_projectid == $r->ID) echo "selected" ?> <?php if(isset($_GET['projectid']) && $_GET['projectid'] == $r->ID) echo "selected" ?>><?php echo $r->name ?></option>
						<?php endforeach; ?>
                        </select>
                    </div>
            </div>
			<input type="hidden" id="hidden_pro_nom" name="project_name" value="">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
                    <div class="col-md-8 ui-front" id="append_input_unit">
                        <select name="unit" id="unit" class="form-control">
							<option value="">Select Unit</option>
							<?php foreach($pro_unit->result() as $r) : ?>
							<option value="<?php echo $r->unit; ?>"><?php echo ucfirst($r->unit); ?></option>
							<?php endforeach; ?>
						</select>
                    	<!-- <input style="display:none;" readonly id="geofacets_unit" type="text" class="form-control" name="geofacets_unit" value=""> -->
					</div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
                    <div class="col-md-8 ui-front">
                        <select name="complexity" id="project_complexity" class="form-control">
                        	<option value="">Select Complexity</option>
							<option value="simple">Simple</option>
							<option value="medium">Medium</option>
							<option value="complex">Complex</option>
							<option value="heavycomplex">Heavy Complex</option>
                        </select>
                    </div>
            </div>
			<div id="page">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1592") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="" id="unitPricePerPage" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1594") ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="" id="totalPages">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1596") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="" id="totalPagesPrice" readonly >
                    </div>
            </div>
			</div>
			<div id="article">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1593") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="" id="unitPricePerArticle" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1595") ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="" value="" id="totalArticles">
                    </div>
            </div>
			
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1597") ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="" id="totalArticlesPrice" readonly >
                    </div>
            </div>
			</div>
			<div id="table">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Table Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerTableEditable" value="" id="unitPricePerTableEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalTablesEditable" value="" id="totalTablesEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalTablesPriceEditable" value="" id="totalTablesPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Table Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerTableScanned" value="" id="unitPricePerTableScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalTablesScanned" value="" id="totalTablesScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalTablesPriceScanned" value="" id="totalTablesPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="figure">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Figure Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerFigureEditable" value="" id="unitPricePerFigureEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalFiguresEditable" value="" id="totalFiguresEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalFiguresPriceEditable" value="" id="totalFiguresPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Figure Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="unitPricePerFigureScanned" value="" id="unitPricePerFigureScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Total Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="totalFiguresScanned" value="" id="totalFiguresScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="totalFiguresPriceScanned" value="" id="totalFiguresPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="image">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Image"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="" id="unitPricePerImage" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Total Images"; ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="" id="totalImages">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Images"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="" id="totalImagesPrice" readonly >
                    </div>
            </div>
			</div>
			<div id="question">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Unit Price per Question"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="unitPricePerPage" value="" id="unitPricePerQuestion" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Total Questions"; ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="totalPages" value="" id="totalQuestions">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Revenue: Questions"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="totalPagesPrice" value="" id="totalQuestionsPrice" readonly >
                    </div>
            </div>
			</div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_827") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="start_date" class="form-control datepicker" id="start_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_828") ?></label>
                    <div class="col-md-8">
                        <input type="text" name="due_date" class="form-control datepicker" id="due_date">
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_829") ?></label>
                    <div class="col-md-8">
                        <select name="status" class="form-control">
                        <option value="1"><?php echo lang("ctn_830") ?></option>
                        <option value="2"><?php echo lang("ctn_831") ?></option>
                        <option value="3"><?php echo lang("ctn_832") ?></option>
                        <option value="4"><?php echo lang("ctn_833") ?></option>
                        <option value="5"><?php echo lang("ctn_834") ?></option>
                        <option value="6"><?php echo lang("ctn_1633") ?></option>
                        <option value="7"><?php echo lang("ctn_1634") ?></option>
                        <option value="8"><?php echo lang("ctn_1635") ?></option>
                        </select>
                    </div>
            </div>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_835") ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="assign" value="1" checked>
                        <span class="help-text"><?php echo lang("ctn_836") ?></span>
                    </div>
            </div>
            <?php if($this->settings->info->enable_calendar) : ?>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1566"); ?></label>
                    <div class="col-md-8">
                        <input type="checkbox" name="calendar_event" value="1" checked>
                        <span class="help-text"><?php echo lang("ctn_1567"); ?></span>
                    </div>
            </div>
        <?php endif; ?>
            <div class="form-group" id="task_members">
            </div>
           <!-- <hr>
            <h4><?php echo lang("ctn_1487") ?></h4>
            <div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1488") ?></label>
                    <div class="col-md-8">
                        <select name="template_option" class="form-control" id="template_option">
                            <option value="0"><?php echo lang("ctn_54") ?></option>
                            <?php if($this->common->has_permissions(array("admin", "project_admin",
         "task_manage"), 
            $this->user)) : ?>
                            <option value="1"><?php echo lang("ctn_1489") ?></option>
                        <?php endif; ?>
                            <option value="2"><?php echo lang("ctn_1490") ?></option>
                        </select>
                        <span class="help-block"><?php echo lang("ctn_1491") ?></span>
                    </div>
            </div>
			-->
            <div id="template_fields" style="display: none">
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1492") ?></label>
                        <div class="col-md-8">
                            <input type="text" name="template_start_days" class="form-control" value="0">
                            <span class="help-block"><?php echo lang("ctn_1493") ?></span>
                        </div>
                </div>
                <div class="form-group">
                        <label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1494") ?></label>
                        <div class="col-md-8">
                            <input type="text" name="template_due_days" class="form-control" value="0">
                            <span class="help-block"><?php echo lang("ctn_1495") ?></span>
                        </div>
                </div>
            </div>
			
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel Process"; ?></label>
				<div class="col-md-8">
				<input type="checkbox" name="parallel" id="parallel" value="1" />
				<span class="help-text">Yes</span>
				</div>
            </div>
            <div id="parallel_process">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel Process Name"; ?></label>
                    <div class="col-md-8">
                        <input id="parallel_process_name" type="text" class="form-control" name="parallel_process_name" value="">
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel Process Team" ?></label>
                    <div class="col-md-8">
						<select name="parallel_process_team" id="parallel_process_team" class="form-control">
								<option value="">Select Parallel Process Team</option>
								<?php foreach($team_list as $r) : ?>
								<option value="<?php echo $r->team_name; ?>"><?php echo ucfirst($r->team_name); ?></option>
								<?php endforeach; ?>
						</select>
                        <!-- <input id="parallel_process_team" type="text" class="form-control" name="parallel_process_team" value=""> -->
                    </div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Cost Applicable"; ?></label>
					<div class="col-md-8">
					<input type="checkbox" name="cost_applicable" id="cost_applicable" value="1" />
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
									<option value="<?php echo $r->unit; ?>"><?php echo ucfirst($r->unit); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
					</div>
					<div id="parallel_process_page">
						<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1592"); ?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="parallel_process_unitPricePerPage" value="" id="parallel_process_unitPricePerPage" >
								</div>
						</div>
						<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1594"); ?></label>
								<div class="col-md-8">
									<input type="number" class="form-control" name="parallel_process_totalPages" value="" id="parallel_process_totalPages">
								</div>
						</div>
						<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1596"); ?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="parallel_process_totalPagesPrice" value="" id="parallel_process_totalPagesPrice" readonly >
								</div>
						</div>
					</div>
					<div id="parallel_process_article">
						<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1593"); ?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="parallel_process_unitPricePerArticle" value="" id="parallel_process_unitPricePerArticle" >
								</div>
						</div>
						<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1595"); ?></label>
								<div class="col-md-8">
									<input type="number" class="form-control" name="parallel_process_totalArticles" value="" id="parallel_process_totalArticles">
								</div>
						</div>
						
						<div class="form-group">
								<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process ".lang("ctn_1597"); ?></label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="parallel_process_totalArticlesPrice" value="" id="parallel_process_totalArticlesPrice" readonly >
								</div>
						</div>
					</div>
					<div id="parallel_process_table">
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Table Editable"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerTableEditable" value="" id="parallel_process_unitPricePerTableEditable" >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Tables Editable"; ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalTablesEditable" value="" id="parallel_process_totalTablesEditable">
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Tables Editable"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalTablesPriceEditable" value="" id="parallel_process_totalTablesPriceEditable" readonly >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Table Scanned"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerTableScanned" value="" id="parallel_process_unitPricePerTableScanned" >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Tables Scanned"; ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalTablesScanned" value="" id="parallel_process_totalTablesScanned">
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Tables Scanned"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalTablesPriceScanned" value="" id="parallel_process_totalTablesPriceScanned" readonly >
							</div>
						</div>
					</div>
					<div id="parallel_process_figure">
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Figure Editable"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerFigureEditable" value="" id="parallel_process_unitPricePerFigureEditable" >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Figures Editable"; ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalFiguresEditable" value="" id="parallel_process_totalFiguresEditable">
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Figures Editable"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalFiguresPriceEditable" value="" id="parallel_process_totalFiguresPriceEditable" readonly >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Figure Scanned"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerFigureScanned" value="" id="parallel_process_unitPricePerFigureScanned" >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Figures Scanned"; ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalFiguresScanned" value="" id="parallel_process_totalFiguresScanned">
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Figures Scanned"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalFiguresPriceScanned" value="" id="parallel_process_totalFiguresPriceScanned" readonly >
							</div>
						</div>
					</div>
					<div id="parallel_process_image">
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Unit Price per Image"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_unitPricePerImage" value="" id="parallel_process_unitPricePerImage" >
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Total Images"; ?></label>
							<div class="col-md-8">
								<input type="number" class="form-control" name="parallel_process_totalImages" value="" id="parallel_process_totalImages">
							</div>
						</div>
						<div class="form-group">
							<label for="p-in" class="col-md-4 label-heading"><?php echo "Parallel process Revenue: Images"; ?></label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="parallel_process_totalImagesPrice" value="" id="parallel_process_totalImagesPrice" readonly >
							</div>
						</div>
					</div>	
				</div>
			</div>
			
            <div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor assignment"; ?></label>
				<div class="col-md-8">
				<input type="checkbox" name="vendor_assignment" id="vendor_assignment" value="1" />
				<span class="help-text">Vendor</span>
				</div>
            </div>
            <div id="vendor">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Name"; ?></label>
                    <div class="col-md-8">
                        <input id="vendor_name" type="text" class="form-control" name="vendor_name" value="">
                    </div>
				</div>
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Process Name" ?></label>
                    <div class="col-md-8">
                        <input id="vendor_process_name" type="text" class="form-control" name="vendor_process_name" value="">
                    </div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
						<div class="col-md-8 ui-front" id="append_vendor_input_unit">
							<select name="vendor_unit" id="vendor_unit" class="form-control">
								<option value="">Select Unit</option>
								<?php foreach($pro_unit->result() as $r) : ?>
								<option value="<?php echo $r->unit; ?>"><?php echo ucfirst($r->unit); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
				</div>
				<div id="vendor_page">
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1592"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_unitPricePerPage" value="" id="vendor_unitPricePerPage" >
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1594"); ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="vendor_totalPages" value="" id="vendor_totalPages">
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1596"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_totalPagesPrice" value="" id="vendor_totalPagesPrice" readonly >
						</div>
				</div>
				</div>
				<div id="vendor_article">
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1593"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_unitPricePerArticle" value="" id="vendor_unitPricePerArticle" >
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1595"); ?></label>
						<div class="col-md-8">
							<input type="number" class="form-control" name="vendor_totalArticles" value="" id="vendor_totalArticles">
						</div>
				</div>
				
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_1597"); ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="vendor_totalArticlesPrice" value="" id="vendor_totalArticlesPrice" readonly >
						</div>
				</div>
				</div>
				<div id="vendor_table">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Table Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerTableEditable" value="" id="vendor_unitPricePerTableEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalTablesEditable" value="" id="vendor_totalTablesEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Tables Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalTablesPriceEditable" value="" id="vendor_totalTablesPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Table Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerTableScanned" value="" id="vendor_unitPricePerTableScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalTablesScanned" value="" id="vendor_totalTablesScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Tables Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalTablesPriceScanned" value="" id="vendor_totalTablesPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="vendor_figure">
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Figure Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerFigureEditable" value="" id="vendor_unitPricePerFigureEditable" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalFiguresEditable" value="" id="vendor_totalFiguresEditable">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Figures Editable"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalFiguresPriceEditable" value="" id="vendor_totalFiguresPriceEditable" readonly >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Figure Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_unitPricePerFigureScanned" value="" id="vendor_unitPricePerFigureScanned" >
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="number" class="form-control" name="vendor_totalFiguresScanned" value="" id="vendor_totalFiguresScanned">
					</div>
				</div>
				<div class="form-group">
					<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Figures Scanned"; ?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" name="vendor_totalFiguresPriceScanned" value="" id="vendor_totalFiguresPriceScanned" readonly >
					</div>
				</div>
			</div>
			<div id="vendor_image">
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Unit Price per Image"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="vendor_unitPricePerImage" value="" id="vendor_unitPricePerImage" >
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Total Images"; ?></label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="vendor_totalImages" value="" id="vendor_totalImages">
                    </div>
            </div>
			<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Revenue: Images"; ?></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="vendor_totalImagesPrice" value="" id="vendor_totalImagesPrice" readonly >
                    </div>
            </div>
			</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Allocation Date"; ?></label>
						<div class="col-md-8">
							<input type="text" name="vendor_start_date" class="form-control datepicker" id="vendor_start_date">
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor ".lang("ctn_828") ?></label>
						<div class="col-md-8">
							<input type="text" name="vendor_due_date" class="form-control datepicker" id="vendor_due_date">
						</div>
				</div>
				<div class="form-group">
						<label for="p-in" class="col-md-4 label-heading"><?php echo "Vendor Status"; ?></label>
						<div class="col-md-8">
							<select name="vendor_status" class="form-control">
							<option value=""><?php echo "Select Vendor status"; ?></option>
							<option value="1"><?php echo "Vendor In progress"; ?></option>
							<option value="2"><?php echo "Vendor YTS"; ?></option>
							<option value="3"><?php echo "Vendor Completed"; ?></option>
							</select>
						</div>
				</div>
			</div>
			<div class="form-group">
				<label for="p-in" class="col-md-4 label-heading"><?php echo "Chapters"; ?></label>
				<div class="col-md-8">
				<input type="checkbox" name="chapterwise" id="chapterwise" value="1" />
				<span class="help-text">Chapters</span>
				</div>
            </div>
            <div id="chapterlist">
				<div class="form-group">
                    <label for="p-in" class="col-md-4 label-heading"><?php echo "Chapter Name"; ?></label>
                    <div class="col-md-8">
                        <textarea style="width:100%;" id="chaptername" name="chaptername"></textarea>
						<span class="help-text">Enter all the chapters of the title seperated by <b>comma(,)</b></span>
					</div>
				</div>
			</div>
            <input id="add_new_task" type="submit" class="btn btn-primary form-control" value="<?php echo lang("ctn_837") ?>" />
            <?php echo form_close() ?>
</div>
</div>

</div>
<script type="text/javascript">
CKEDITOR.replace('task-desc', { height: '100'});
$(document).ready(function() {
    $('#template_option').change(function() {
        var value = $(this).val();
        if(value > 0) {
            $('#template_fields').css("display", "block");
        } else {
            $('#template_fields').css("display", "none");
        }
    });
    $('#projectid').change(function() {
        var projectid = $('#projectid').val();
        $.ajax({
             type: "GET",
             url: global_base_url + "tasks/get_team_members/" + projectid,
             data: {
             },
             success: function (msg) {
                 $('#task_members').html(msg);
             }
         });
		 
		 var hiddenpronom = $("#projectid option:selected").text();
		 $("#hidden_pro_nom").val(hiddenpronom);
    });
	
/* 	$("#unitPricePerPage, #totalPages").on("input paste change keyup", function() { 
		alert($(this).val()); */
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
    $('#page').hide(); 
	$('#article').hide(); 
	$('#table').hide(); 
	$('#figure').hide(); 
	$('#image').hide(); 
	$('#question').hide(); 
    $('#unit').change(function(){
        if($('#unit').val() == 'Page') {
            $('#page').show(); 
        }
		else {
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
$('#unit').change(function(){
	var unit_val = $('#unit').val();
	if(typeof(unit_val) != "undefined" && unit_val !== null){
        $('#vendor_unit').val(unit_val);
		$('#vendor_unit').find('option[value="'+unit_val+'"]').attr('selected','selected');
		$('#parallel_process_unit').val(unit_val);
		$('#parallel_process_unit').find('option[value="'+unit_val+'"]').attr('selected','selected');
		/* $('#vendor_unit').prop('disabled', true);
		$("#vendor").append("<input name='vendor_unit' value='"+unit_val+"' type='hidden' id='hidden_input' >"); */
    }
});		
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
	$('#unit').change(function(){
		var unit_val = $('#unit').val();
		if(unit_val == 'Page') {
			$('#vendor_page').show(); 
		} else {
			$('#vendor_page').hide(); 
		}
		if(unit_val == 'Article') {
			$('#vendor_article').show(); 
		} else {
			$('#vendor_article').hide(); 
		} 
		if(unit_val == 'table') {
			$('#vendor_table').show();  
		} else {
			$('#vendor_table').hide(); 
		} 
		if(unit_val == 'figure') {
			$('#vendor_figure').show(); 
		} else {
			$('#vendor_figure').hide(); 
		} 
		if(unit_val == 'image') {
			$('#vendor_image').show(); 
		} else {
			$('#vendor_image').hide(); 
		} 
    }); 	

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
	$('#unit').change(function(){
		var unit_val = $('#unit').val();
		if(unit_val == 'Page') {
			$('#parallel_process_page').show(); 
		} else {
			$('#parallel_process_page').hide(); 
		}
		if(unit_val == 'Article') {
			$('#parallel_process_article').show(); 
		} else {
			$('#parallel_process_article').hide(); 
		} 
		if(unit_val == 'table') {
			$('#parallel_process_table').show();  
		} else {
			$('#parallel_process_table').hide(); 
		} 
		if(unit_val == 'figure') {
			$('#parallel_process_figure').show(); 
		} else {
			$('#parallel_process_figure').hide(); 
		} 
		if(unit_val == 'image') {
			$('#parallel_process_image').show(); 
		} else {
			$('#parallel_process_image').hide(); 
		} 
    }); 
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
    });
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
    $('#vendor').hide(); 
    $('#vendor_assignment').click(function(){
	if(this.checked) {
	    $('#vendor').show(); 
	} else {
		$('#vendor').hide();
	}
    });
});

$(function() {
    $('#parallel_process').hide(); 
    $('#parallel').click(function(){
	if(this.checked) {
	    $('#parallel_process').show(); 
	} else {
		$('#parallel_process').hide();
	}
    });
});

$(function() {
    $('#chapterlist').hide(); 
    $('#chapterwise').click(function(){
	if(this.checked) {
	    $('#chapterlist').show(); 
	} else {
		$('#chapterlist').hide();
	}
    });
});

$(function() {
    $('#cost').hide(); 
    $('#cost_applicable').click(function(){
	if(this.checked) {
	    $('#cost').show(); 
	} else {
		$('#cost').hide();
	}
    });
});

$(function () {
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
});
</script>

<script type="text/javascript">
	$('#task_name,#isbn,#eisbn').keyup(function() {
    	$.ajax({
	        url : "<?php echo site_url("tasks/check_task_details") ?>",
	        type : 'GET',
	        data : {
	          taskname : $("#task_name").val(),
	          isbn : $("#isbn").val(),
	          eisbn : $("#eisbn").val(),
	        },
	        dataType: 'JSON',
	        success: function(data) {
	        	if(data.success) {
				if($("#task_name").val() != ''){
	        		$("#task_name").css("border-color", "green");
					$("#task_name").css("box-shadow", "inset 0 1px 1px green");
				}	
				if($("#isbn").val() != ''){
					$("#isbn").css("border-color", "green");
					$("#isbn").css("box-shadow", "inset 0 1px 1px green");
				}
				if($("#eisbn").val() != ''){				
	        		$("#eisbn").css("border-color", "green");
	        		$("#eisbn").css("box-shadow", "inset 0 1px 1px green");
				}	
					$('#add_new_task').prop('disabled', false);
	        	} else {
	        		$("#task_name").css("border-color", "red");
	        		$("#isbn").css("border-color", "red");
	        		$("#eisbn").css("border-color", "red");
					$("#task_name").css("box-shadow", "inset 0 1px 1px red");
					$("#isbn").css("box-shadow", "inset 0 1px 1px red");
					$("#eisbn").css("box-shadow", "inset 0 1px 1px red");
					$('#add_new_task').prop('disabled', true);
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
			/* console.log(data);  */
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
            /* var optionValue = $("#projectid").val();
            if(optionValue==29){
                $("#geofacets_unit").show();
				$("#geofacets_unit").val('figure');
				$("#unit").val('table').trigger('change');
				$('#unit').prop('disabled', true);
            } 
			else{
				$("#geofacets_unit").hide();
				$("#geofacets_unit").val('');
				$('#unit').prop('disabled', false);
            } */ 
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