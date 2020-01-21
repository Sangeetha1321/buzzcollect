<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-folder-open"></span> 
	<?php echo "Edit"; ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open_multipart(site_url("admin/edit_unitprice_pro/" . $pro_unitprice->ID), array("class" => "form-horizontal")) ?>
<div class="form-group">
		<label for="publisher" class="col-md-4 label-heading"><?php echo "Publisher"; ?></label>
		<div class="col-md-8 ui-front">
			<input readonly type="text" class="form-control" name="publisher" value="<?php echo $pro_unitprice->publisher; ?>">
		</div>
</div>
<div class="form-group">
		<label for="process" class="col-md-4 label-heading"><?php echo "Process"; ?></label>
		<div class="col-md-8 ui-front">
			<input readonly type="text" class="form-control" name="process" value="<?php echo $pro_unitprice->process; ?>">
		</div>
</div>
<div class="form-group">
		<label for="stage" class="col-md-4 label-heading"><?php echo "Stage"; ?></label>
		<div class="col-md-8 ui-front">
			<!-- <select name="stage" id="stage" class="form-control">
				<option value="">Select Stage</option>
				<option value="Fulltext" <?php if($pro_unitprice->stage == 'Fulltext'){ echo "selected"; }?>>Fulltext</option>
				<option value="Head and Tail" <?php if($pro_unitprice->stage == 'Head and Tail'){ echo "selected"; }?>>Head and Tail</option>
				<option value="Ultralite" <?php if($pro_unitprice->stage == 'Ultralite'){ echo "selected"; }?>>Ultralite</option>
			</select> -->
			<input readonly type="text" class="form-control" name="stage" id="stage" value="<?php echo $pro_unitprice->stage; ?>">
		</div>
</div>
<div class="form-group">
		<label for="pdfType" class="col-md-4 label-heading"><?php echo "PDF Type"; ?></label>
		<div class="col-md-8 ui-front">
			<!-- <select name="pdfType" id="pdfType" class="form-control">
				<option value="">Select PDF Type</option>
				<option value="Editable" <?php if($pro_unitprice->pdfType == 'Editable'){ echo "selected"; }?>>Editable</option>
				<option value="Scanned" <?php if($pro_unitprice->pdfType == 'Scanned'){ echo "selected"; }?>>Scanned</option>
			</select> -->
			<input readonly type="text" class="form-control" name="pdfType" id="pdfType" value="<?php echo $pro_unitprice->pdfType; ?>">
		</div>
</div>
<div class="form-group">
		<label for="complexity" class="col-md-4 label-heading"><?php echo lang("ctn_1591") ?></label>
		<div class="col-md-8 ui-front">
			<select name="complexity" id="title_complexity" class="form-control">
				<option value="">Select Complexity</option>
				<option value="simple" <?php if($pro_unitprice->complexity == 'simple'){ echo "selected"; }?>>Simple</option>
				<option value="medium" <?php if($pro_unitprice->complexity == 'medium'){ echo "selected"; }?>>Medium</option>
				<option value="complex" <?php if($pro_unitprice->complexity == 'complex'){ echo "selected"; }?>>Complex</option>
				<option value="heavycomplex" <?php if($pro_unitprice->complexity == 'heavycomplex'){ echo "selected"; }?>>Heavy Complex</option>
			</select>
		</div>
</div>
<div class="form-group">
		<label for="unit" class="col-md-4 label-heading"><?php echo lang("ctn_1621") ?></label>
		<div class="col-md-8 ui-front">
			<!-- <select name="unit" id="title_unit" class="form-control">
				<option value="">Select Unit</option>
				<option value="Page" <?php if($pro_unitprice->unit == 'Page'){ echo "selected"; }?>>Page</option>
				<option value="Article" <?php if($pro_unitprice->unit == 'Article'){ echo "selected"; }?>>Article</option>
				<option value="table" <?php if($pro_unitprice->unit == 'table'){ echo "selected"; }?>>Table</option>
				<option value="figure" <?php if($pro_unitprice->unit == 'figure'){ echo "selected"; }?>>Figure</option>
				<option value="image" <?php if($pro_unitprice->unit == 'image'){ echo "selected"; }?>>Image</option>
			</select> -->
			<input readonly type="text" class="form-control" name="unit" id="title_unit" value="<?php echo $pro_unitprice->unit; ?>">
		</div>
</div>
<div class="form-group">
		<label for="unitprice" class="col-md-4 label-heading"><?php echo "Unitprice"; ?></label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="unitprice" value="<?php echo $pro_unitprice->unitprice; ?>" id="unitprice" >
		</div>
</div>
<input type="submit" class="btn btn-primary form-control" value="<?php echo "Update"; ?>">
<?php echo form_close() ?>
</div>
</div>


</div>