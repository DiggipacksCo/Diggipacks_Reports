<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>



        <!-- Resources -->
        <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>


    </head>

    <body>

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        <!--style="background-color: red;"-->


                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <?php
                            foreach ($warehouseArr as $key => $val) {
                                echo'<div class="panel-heading">

                                <h1><strong>Storage Report (' . $val->name . ')</strong></h1>

                                <div class="heading-elements">
                                    <ul class="icons-list">

                                    </ul>
                                </div>
                                <hr>
                            </div>

                            <div class="panel-body" >


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <div id="chartdiv_' . $val->id . '"></div>


                                </div>
                               
                                <hr>
                            </div>';

                                $chartArray = GetAllwarehouseChartData($val->id);
                                $main_storageArr = array();
                                foreach ($chartArray as $storage_rows) {
                                    $used_size = GetAllstorageusedSize($val->id, $storage_rows['storage_id']);

                                    if (empty($used_size))
                                        $used_size = 0;
                                    $avalabile = $storage_rows['size'] - $used_size;
                                    $main_storageArr[] = array(
                                        'category' => $storage_rows['storage_type'],
                                        'first' => $storage_rows['size'],
                                        'second' => $used_size,
                                        'third' => $avalabile
                                    );
                                }
                                //  print_r(json_encode($main_storageArr));
                                ?>
                                <style>
                                    #chartdiv_<?= $val->id; ?> {
                                        width: 100%;
                                        height: 500px;
                                    }
                                </style>
                                <script>
                                    am4core.ready(function () {

                                        // Themes begin
                                        am4core.useTheme(am4themes_animated);
                                        // Themes end



                                        var chart = am4core.create('chartdiv_<?= $val->id; ?>', am4charts.XYChart)
                                        chart.colors.step = 3;

                                        chart.legend = new am4charts.Legend()
                                        chart.legend.position = 'top'
                                        chart.legend.paddingBottom = 20
                                        chart.legend.labels.template.maxWidth = 95


                                        var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())

                                        xAxis.dataFields.category = 'category'
                                        xAxis.renderer.cellStartLocation = 0.1
                                        xAxis.renderer.cellEndLocation = 0.9
                                        xAxis.renderer.grid.template.location = 0;

                                        var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
                                        yAxis.min = 0;


                                        function createSeries(value, name) {
                                            var series = chart.series.push(new am4charts.ColumnSeries())
                                            series.dataFields.valueY = value
                                            series.dataFields.categoryX = 'category'
                                            series.name = name
                                            series.tooltipText = "{categoryX}: {valueY}({name})";
                                            chart.cursor = new am4charts.XYCursor();



                                            //series.tooltipText = "{name}:";

                                            series.events.on("hidden", arrangeColumns);
                                            series.events.on("shown", arrangeColumns);

                                            var bullet = series.bullets.push(new am4charts.LabelBullet())
                                            bullet.interactionsEnabled = false
                                            bullet.dy = 5;
                                            bullet.label.text = '{valueY}'
                                            bullet.label.fill = am4core.color('#ffffff')

                                            return series;
                                        }
                                        chart.colors.list = [
                                            am4core.color("#00B2C9"),
                                            am4core.color("#F25320"),
                                            am4core.color("#48A64C"),
                                        ];
                                        chart.data = <?= json_encode($main_storageArr); ?>


                                        createSeries('first', 'Total capacity');
                                        createSeries('second', 'Usage');
                                        createSeries('third', 'Available capacity');


                                        function arrangeColumns() {

                                            var series = chart.series.getIndex(0);

                                            var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
                                            if (series.dataItems.length > 1) {
                                                var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
                                                var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
                                                var delta = ((x1 - x0) / chart.series.length) * w;
                                                if (am4core.isNumber(delta)) {
                                                    var middle = chart.series.length / 2;

                                                    var newIndex = 0;
                                                    chart.series.each(function (series) {
                                                        if (!series.isHidden && !series.isHiding) {
                                                            series.dummyData = newIndex;
                                                            newIndex++;
                                                        } else {
                                                            series.dummyData = chart.series.indexOf(series);
                                                        }
                                                    })
                                                    var visibleCount = newIndex;
                                                    var newMiddle = visibleCount / 2;

                                                    chart.series.each(function (series) {
                                                        var trueIndex = chart.series.indexOf(series);
                                                        var newIndex = series.dummyData;

                                                        var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                                                        series.animate({property: "dx", to: dx}, series.interpolationDuration, series.interpolationEasing);
                                                        series.bulletsContainer.animate({property: "dx", to: dx}, series.interpolationDuration, series.interpolationEasing);
                                                    })
                                                }
                                            }
                                        }

                                    }); // end am4core.ready()
                                </script>

<?php }
?>
                        </div>
                        <!-- /basic responsive table --> 
<?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->


            </div>



        </div>
        <!-- Chart code -->

        <!-- /page container -->

    </body>
</html>
