<div class="white-area-content">

<div class="db-header clearfix">
    <div class="page-header-title"> <span class="glyphicon glyphicon-list-alt"></span> <?php echo lang("ctn_1141") ?></div>
    <div class="db-header-extra form-inline"> 
     
<?php echo form_open(site_url("reports/finance/" . $projectid)) ?>
<div class="btn-group">
    <div class="dropdown">
  <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo lang("ctn_470") ?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
      <li><a href="<?php echo site_url("reports/finance/0") ?>"><?php echo lang("ctn_1000") ?></a></li>
    <?php foreach($projects->result() as $r) : ?>
      <li><a href="<?php echo site_url("reports/finance/" . $r->ID) ?>"><?php echo $r->name ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>
</div>

    <div class="form-group">
      <input type="text" name="start_date" class="input-sm form-control datepicker" value="<?php echo $range1 ?>">
    </div>
    <div class="form-group">
      <input type="text" name="end_date" class="input-sm form-control datepicker" value="<?php echo $range2 ?>">
    </div>
    <div class="form-group">
      <input type="submit" class="btn btn-primary btn-sm" value="<?php echo lang("ctn_1153") ?>" >
    </div>
    <?php echo form_close() ?>
      
</div>
</div>

<?php if($project) : ?>
<p><?php echo lang("ctn_1154") ?> <b><?php echo $project->name ?></b></p>
<?php endif; ?>


<hr>

<div class="panel panel-default">
<div class="panel-body">
<h4 class="home-label"><?php echo lang("ctn_1155") ?></h4>
<div class="finance-blob">
<p class="finance-blob-unit"><?php echo $this->settings->info->fp_currency_symbol ?><span id="num1"><?php echo number_format($total_revenue) ?></span></p>
<?php echo lang("ctn_1156") ?>
</div>
<div class="finance-blob">
<p class="finance-blob-unit"><?php echo $this->settings->info->fp_currency_symbol ?><span id="num2"><?php echo number_format($total_expense) ?></span></p>
<?php echo lang("ctn_1157") ?>
</div>
<div class="finance-blob">
<p class="finance-blob-unit"><?php echo $this->settings->info->fp_currency_symbol ?><span id="num3"><?php echo number_format($total_revenue + $total_expense) ?></span></p>
<?php echo lang("ctn_1158") ?>
</div>
<canvas 
<canvas id="myChart" class="graph-height"></canvas>
</div>
</div>

</div>
<script type="text/javascript">
var ctx = $("#myChart");
    var data = {
        labels: [<?php foreach($dates as $i) : ?>
        "<?php echo $i['date'] ?>",
        <?php endforeach; ?>],
        datasets: [
            <?php if($results2) : ?>
            {
                label: "<?php echo lang("ctn_579") ?>",
                fill: true,
                lineTension: 0.2,
                backgroundColor: "rgba(32,113,210,0.4)",
                borderColor: "rgba(32,113,210,0.9)",
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
                data: [<?php foreach($results2 as $i) : ?>
                    <?php echo $i['count'] ?>,
                <?php endforeach; ?>],
                spanGaps: false,
            },
            <?php endif; ?>
            {
                label: "<?php echo lang("ctn_1159") ?>",
                fill: true,
                lineTension: 0.2,
                backgroundColor: "rgba(29,210,142,0.5)",
                borderColor: "rgba(29,210,142,0.9)",
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
                data: [<?php foreach($results as $i) : ?>
                    <?php echo $i['count'] ?>,
                <?php endforeach; ?>],
                spanGaps: false,
            },
        ]
    };
    Chart.defaults.global.defaultFontFamily = "'Open Sans'";
    Chart.defaults.global.defaultFontSize = 8;
    var options = { title : { text: "" }};
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) { 
                        return "<?php echo $this->settings->info->fp_currency_symbol ?>" + tooltipItems.yLabel.toLocaleString("en");
                       
                    }
                }
            },
            defaultFontSize: 8,
            responsive: true,
            hover : {
                mode : "single"
            },
            legend : {
                display : false,
                labels : {
                    boxWidth: 15,
                    padding: 10,
                    fontSize: 11,
                    usePointStyle : false
                }
            },
            animation : {
                duration: 2000,
                easing: "easeOutElastic"
            },
            scales : {
                yAxes : [{
                    display: true,
                    title : {
                        fontSize: 11
                    },
                    gridLines : {
                        display : true
                    }
                }],
                xAxes : [{
                    display : true,
                    scaleLabel : {
                        display : false
                    },
                    ticks : {
                        display : true
                    },
                    gridLines : {
                        display : true,
                        drawTicks : false,
                        tickMarkLength: 5,
                        zeroLineWidth: 0,
                    }
                }]
            }
        }
    });
</script>