<?php if(isset($project)) : ?>
<div class="project-area content-area-padding">
<p><a href="<?php echo site_url("client/view_docs/" . $project->ID) ?>"><img src="<?php echo base_url() ?>/<?php echo $this->settings->info->upload_path_relative ?>/<?php echo $project->image ?>" /></a></p>
<p><?php echo $project->name ?></p>
</div>
<?php endif; ?>

<div class="content-area-padding">
<?php if(isset($project)) : ?>
<h4><strong><?php echo lang("ctn_1528") ?></strong></h4>
<ul class="table-of-contents">
<?php foreach($documents->result() as $r) : ?>
	<?php if($r->link_documentid > 0) {
		$r->title = $r->link_title;
	}
	?>
<li><a href="<?php echo site_url("client/view_doc/" . $r->ID) ?>"><?php echo $r->title ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

</div>