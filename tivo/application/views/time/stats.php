<script type="text/javascript">
$(document).ready(function() {
	var myChart = new Chart($("#myChart"), {
    type: 'line',
    data: {
        labels: [
        <?php foreach($last_dates as $d) : ?>
        "<?php echo $d['date'] ?>",
        <?php endforeach; ?>
        ],
        datasets: [{
            label: "Time",
            lineTension: 0.1,
            backgroundColor: "rgba(75,192,192,0.4)",
            borderColor: "rgba(75,192,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [
            <?php foreach($last_dates as $d) : ?>
            <?php echo $d['hours'] ?>,
            <?php endforeach; ?>
            ]
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

    var data = {
    labels: [
        <?php foreach($projects as $project) : ?>
        "<?php echo $project['title'] ?>",
        <?php endforeach; ?>
    ],
    datasets: [
        {
            data: [
            <?php 
                $max = 0;
                $max_title = "";
            ?>
            <?php foreach($projects as $project) : ?>
            <?php if($project['hours'] > $max) {
                $max = $project['hours'];
                $max_title = $project['title'];
            }
            ?>
            <?php echo round($project['hours'],2) ?>,
            <?php endforeach; ?>
            ],
            backgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#55f225",
                "#f24c25",
                "#f225b1",
                "#8125f2",
                "#39ded8",
                "#4e7f39",
                "#7f3941",
                "#7f3965",
                "#62397f",
                "#39507f",
                "#397f6f",
                "#397f44",
                "#607f39",
                "#7f6c39",
                "#7f3939",
                "#321a20",
                "#3c3a3c",
                "#7e777d",
                "#b5ba9e",
                "#84d588",
                "#84ced5",
                "#2c57b9",
                "#dbc6ec",
                "#ecc6eb",
            ],
            hoverBackgroundColor: [
                "#FF6384",
                "#36A2EB",
                "#FFCE56",
                "#55f225",
                "#f24c25",
                "#f225b1",
                "#8125f2",
                "#39ded8",
                "#4e7f39",
                "#7f3941",
                "#7f3965",
                "#62397f",
                "#39507f",
                "#397f6f",
                "#397f44",
                "#607f39",
                "#7f6c39",
                "#7f3939",
                "#321a20",
                "#3c3a3c",
                "#7e777d",
                "#b5ba9e",
                "#84d588",
                "#84ced5",
                "#2c57b9",
                "#dbc6ec",
                "#ecc6eb",
            ]
        }]
};
	
var myPieChart = new Chart($("#projectTypesChart"),{
    type: 'pie',
    data: data,
});


});
</script>
<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-time"></span> <?php echo lang("ctn_1007") ?></div>
    <div class="db-header-extra"> 
</div>
</div>

<p><?php echo lang("ctn_1008") ?></p>

<hr>

<div class="row">
<div class="col-md-8">

<p><div class="form-inline">
<a href="<?php echo site_url("time/stats/0") ?>" class="btn btn-default btn-sm"><?php echo lang("ctn_1009") ?></a> <a href="<?php echo site_url("time/stats/1") ?>" class="btn btn-default btn-sm"><?php echo lang("ctn_1010") ?></a> <a href="<?php echo site_url("time/stats/2") ?>" class="btn btn-default btn-sm"><?php echo lang("ctn_1011") ?></a>

<div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_1109") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_567")) ?>"><?php echo lang("ctn_567") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_568")) ?>"><?php echo lang("ctn_568") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_569")) ?>"><?php echo lang("ctn_569") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_570")) ?>"><?php echo lang("ctn_570") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_571")) ?>"><?php echo lang("ctn_571") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_572")) ?>"><?php echo lang("ctn_572") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_573")) ?>"><?php echo lang("ctn_573") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_574")) ?>"><?php echo lang("ctn_574") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_575")) ?>"><?php echo lang("ctn_575") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_576")) ?>"><?php echo lang("ctn_576") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_577")) ?>"><?php echo lang("ctn_577") ?></a></li>
      <li><a href="<?php echo site_url("time/stats/3/" . lang("ctn_578")) ?>"><?php echo lang("ctn_578") ?></a></li>
  </ul>
</div>
</div> 

</div></p>

<h4 class="home-label">
<?php if($type == 0) : ?>
    <?php echo lang("ctn_1009") ?>
<?php elseif($type == 1) : ?>
    <?php echo lang("ctn_1010") ?>
<?php elseif($type == 2) : ?>
    <?php echo lang("ctn_1011") ?>
<?php elseif($type == 3) : ?>
    <?php echo lang("ctn_1109") ?> <?php echo $month ?>
<?php endif ?>
    </h4>
<canvas id="myChart" class="graph-height"></canvas>
</div>
<div class="col-md-4">
<div class="panel panel-default">
<div class="panel-body">
<p class="summary-title"><?php echo lang("ctn_1012") ?></p>

<table class="table table-bordered">
<tr><td><?php echo lang("ctn_1013") ?></td><td><?php echo number_format($total_hours,2) ?> <?php echo lang("ctn_278") ?></td></tr>
<tr><td><?php echo lang("ctn_1014") ?></td><td>$<?php echo number_format($total_earnt, 2) ?></td></tr>
<tr><td><?php echo lang("ctn_1015") ?></td><td><?php echo $max_title ?> (<?php echo number_format($max,2) ?> <?php echo lang("ctn_278") ?>)</td></tr>
<tr><td><?php echo lang("ctn_1016") ?></td><td><?php echo number_format($total_timers) ?></td></tr>
</table>
</div>
</div>

<div class="panel panel-default">
<div class="panel-body" id="projectTypesChartArea">
<h4 class="home-label"><?php echo lang("ctn_1017") ?></h4>
<canvas id="projectTypesChart"></canvas>
</div>
</div>

</div>
</div>
</div>