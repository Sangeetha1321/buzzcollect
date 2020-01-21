<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['gantt']});
    google.charts.setOnLoadCallback(drawChart);

    function daysToMilliseconds(days) {
      return days * 24 * 60 * 60 * 1000;
    }

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Task ID');
      data.addColumn('string', '<?php echo lang("ctn_823"); ?>');
      data.addColumn('date', '<?php echo lang("ctn_827"); ?>');
      data.addColumn('date', '<?php echo lang("ctn_645"); ?>');
      data.addColumn('number', '<?php echo lang("ctn_1564"); ?>');
      data.addColumn('number', '<?php echo lang("ctn_1564"); ?>');
      data.addColumn('string', '<?php echo lang("ctn_1565"); ?>');


      <?php foreach($tasks->result() as $task) : ?>
        <?php if($task->template == 0) : ?>
      	<?php
      	$start_year = date("Y", $task->start_date);
      	$start_month = date("m", $task->start_date);
      	$start_day = date("d", $task->start_date);

      	$end_year = date("Y", $task->due_date);
      	$end_month = date("m", $task->due_date);
      	$end_day = date("d", $task->due_date);


        $dependencies = $this->task_model->get_dependencies($task->ID);
        $task_dependencies = "";
        foreach($dependencies->result() as $r) {
          $task_dependencies .= $r->taskid_secondary . ',';
        }

      	?>
      	 data.addRows([
      	 	 ['<?php echo $task->ID ?>', '<?php echo $task->name ?>',
         		new Date(<?php echo $start_year ?>, <?php echo $start_month ?>, <?php echo $start_day ?>), new Date(<?php echo $end_year ?>, <?php echo $end_month ?>, <?php echo $end_day ?>),null, <?php echo $task->complete ?>,  <?php if(!empty($task_dependencies)) : ?>"<?php echo $task_dependencies ?>" <?php else : ?>null<?php endif; ?>],
      	 	]);
        <?php endif; ?>
      <?php endforeach; ?>

      var trackHeight = 40;

      var options = {
        height: data.getNumberOfRows() * trackHeight + 100,
        width: "100%",
        hAxis: {
            textStyle: {
                fontName: ["Open+Sans"]
            }
        },
        gantt: {
            labelStyle: {
            fontName: ["Verdana"],
            fontSize: 14,
            color: '#757575',
            },
            trackHeight: trackHeight
        },
      };

      var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

      chart.draw(data, options);
    }

</script>


<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-simple-title"><?php echo $project->name ?></div>
    <div class="db-header-extra form-inline"> 

      <?php if( $this->common->has_permissions(array("admin", "project_admin", "doc_manage", "doc_worker"), $this->user) || ($this->common->has_team_permissions(array("doc"), $team_member)) ) : ?>
      <a href="<?php echo site_url("client/view_docs/" . $project->ID) ?>" class="btn btn-round btn-sm"><?php echo lang("ctn_1527") ?></a> 
    <?php endif; ?>

    <?php if( $this->common->has_permissions(array("admin", "project_admin"), $this->user) || ($this->common->has_team_permissions(array("admin"), $team_member)) ) : ?>
           <a href="<?php echo site_url("projects/edit_project/" . $project->ID) ?>" class="btn btn-round btn-sm" title="<?php echo lang("ctn_55") ?>" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="<?php echo site_url("projects/delete_project/" . $project->ID . "/" . $this->security->get_csrf_hash()) ?>" class="btn btn-round btn-sm" onclick="return confirm(\'<?php echo lang("ctn_789") ?>')" title="<?php echo lang("ctn_57") ?>"  data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>
    <?php endif; ?>

</div>
</div>

 <div id="chart_div"></div>

</div>
 <style type="text/css">
text {
  font-family: sans-serif !important;
}
</style>