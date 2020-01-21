<div class="row">
<div class="col-md-3">
<?php echo $sidebar ?>
</div><div class="col-md-9 documentation-area">
<h1 class="home-label"><?php echo $project->name ?></h1>

    	<ol class="breadcrumb">
  <li><a href="<?php echo site_url() ?>"><?php echo lang("ctn_2") ?></a></li>
   <li><a href="<?php echo site_url("client/documentation") ?>"><?php echo lang("ctn_1527") ?></a></li>
  <li class="active"><?php echo $project->name ?>'s <?php echo lang("ctn_1527") ?></li>
</ol>


<?php echo $project->description ?>
</div>
</div>