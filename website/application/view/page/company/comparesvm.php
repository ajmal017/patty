<script type="text/javascript" src="/public/js/vendor/canvasjs.min.js"></script>
<script type="text/javascript" src="/public/js/vendor/highcharts.js"></script>
<style>.container .content { padding: 40px; }</style>

<div class="graph">
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>정확도</th>
            <th>이름</th>
            <th>가격</th>
            <th>고가</th>
            <th>저가</th>
            <th>시가</th>
            <th>옵션</th>
        </tr>
    </thead>
    <tbody id="tbody-sparkline">
        <?php foreach($target_company_list as $target) { ?>
            <tr>
                <td class="text-center sparkline" data-sparkline="<?php echo thinning_stock($target['stock_list']); ?>" style="width: 120px;"></td>
                <td class="text-center" style="width: 140px;">
                    <?php echo number_format($target['model']->getScore()*100, 4); ?>%
                </td>
                <td>
                    <?php echo $target['company']->getName(); ?>
                </td>
                <td class="text-right">
                    <?php echo number_format($target['detail']->getPrice()); ?> 원
                </td>
                <td class="text-right">
                    <?php echo number_format($target['detail']->getHigh()); ?> 원
                </td>
                <td class="text-right">
                    <?php echo $target['detail']->getLow(); ?> 원
                </td>
                <td class="text-right">
                    <?php echo $target['detail']->getOpen(); ?> 원
                </td>
                <td class="text-center" style="padding: 0px;">
                    <div class="btn btn-primary" onclick="onclick_add_company_graph(this, <?php echo $target['model']->getIdx(); ?>);">추가</div>
                    <div class="btn btn-danger" onclick="onclick_remove_company_graph(this, <?php echo $target['model']->getIdx(); ?>);" style="display: none;">삭제</div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    var main_graph_data = [];
    var main_data = [];
    <?php foreach($target_company_list as $target) { ?>
        main_data[<?php echo $target['model']->getIdx(); ?>] = {
            type: "candlestick",
            name: "<?php echo $target['company']->getName(); ?>",
            yValueFormatString: "$###0",
            xValueFormatString: "MMMM YY",
            dataPoints: <?php echo str_replace('"', '', json_encode($target['ohlc_list'], true) ); ?>
        };
    <?php } ?>
    var onclick_add_company_graph = function(element, id) {
        var p = $(element).parent();
        main_graph_data.push(id);
        p.find(".btn-primary").hide();
        p.find(".btn-danger").show();
        onclick_update_graph();
    };
    var onclick_remove_company_graph = function(element, id) {
        var p = $(element).parent();
        var index = main_graph_data.indexOf(id);
        if (index > -1) {
          main_graph_data.splice(index, 1);
        }
        p.find(".btn-primary").show();
        p.find(".btn-danger").hide();
        onclick_update_graph();
    };
    var onclick_update_graph = function() {
        var data = [];
        data.push({
            type: "candlestick",
            name: "<?php echo $company->getName(); ?>",
            yValueFormatString: "$###0",
            xValueFormatString: "MMMM YY",
            dataPoints: <?php echo str_replace('"', '', json_encode($ohlc_list, true) ); ?>
        });
        for(var i = 0; i < main_graph_data.length; i++) {
            data.push(main_data[main_graph_data[i]]);
        }
        CanvasChart.generate("chartContainer", data);
    };
    $(function() {
        onclick_update_graph();
        /**
         * Create a constructor for sparklines that takes some sensible defaults and merges in the individual
         * chart options. This function is also available from the jQuery plugin as $(element).highcharts('SparkLine').
         */
        Highcharts.SparkLine = function (a, b, c) {
            var hasRenderToArg = typeof a === 'string' || a.nodeName,
                options = arguments[hasRenderToArg ? 1 : 0],
                defaultOptions = {
                    chart: {
                        renderTo: (options.chart && options.chart.renderTo) || this,
                        backgroundColor: null,
                        borderWidth: 0,
                        type: 'area',
                        margin: [2, 0, 2, 0],
                        width: 120,
                        height: 37,
                        style: {
                            overflow: 'visible'
                        },

                        // small optimalization, saves 1-2 ms each sparkline
                        skipClone: true
                    },
                    title: {
                        text: ''
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        labels: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        startOnTick: false,
                        endOnTick: false,
                        tickPositions: []
                    },
                    yAxis: {
                        endOnTick: false,
                        startOnTick: false,
                        labels: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        tickPositions: [0]
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        backgroundColor: null,
                        borderWidth: 0,
                        shadow: false,
                        useHTML: true,
                        hideDelay: 0,
                        shared: true,
                        padding: 0,
                        positioner: function (w, h, point) {
                            return { x: point.plotX - w / 2, y: point.plotY - h };
                        }
                    },
                    plotOptions: {
                        series: {
                            animation: false,
                            lineWidth: 1,
                            shadow: false,
                            states: {
                                hover: {
                                    lineWidth: 1
                                }
                            },
                            marker: {
                                radius: 1,
                                states: {
                                    hover: {
                                        radius: 2
                                    }
                                }
                            },
                            fillOpacity: 0.25
                        },
                        column: {
                            negativeColor: '#910000',
                            borderColor: 'silver'
                        }
                    }
                };

            options = Highcharts.merge(defaultOptions, options);

            return hasRenderToArg ?
                new Highcharts.Chart(a, options, c) :
                new Highcharts.Chart(options, b);
        };

        var start = +new Date(),
            $tds = $('td[data-sparkline]'),
            fullLen = $tds.length,
            n = 0;

        // Creating 153 sparkline charts is quite fast in modern browsers, but IE8 and mobile
        // can take some seconds, so we split the input into chunks and apply them in timeouts
        // in order avoid locking up the browser process and allow interaction.
        function doChunk() {
            var time = +new Date(),
                i,
                len = $tds.length,
                $td,
                stringdata,
                arr,
                data,
                chart;

            for (i = 0; i < len; i += 1) {
                $td = $($tds[i]);
                stringdata = $td.data('sparkline');
                arr = stringdata.split('; ');
                data = $.map(arr[0].split(', '), parseFloat);
                chart = {};

                if (arr[1]) {
                    chart.type = arr[1];
                }
                $td.highcharts('SparkLine', {
                    series: [{
                        data: data,
                        pointStart: 1
                    }],
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '{point.y} 원'
                    },
                    chart: chart
                });

                n += 1;

                // If the process takes too much time, run a timeout to allow interaction with the browser
                if (new Date() - time > 500) {
                    $tds.splice(0, i + 1);
                    setTimeout(doChunk, 0);
                    break;
                }

                // Print a feedback on the performance
                if (n === fullLen) {
                    $('#result').html('Generated ' + fullLen + ' sparklines in ' + (new Date() - start) + ' ms');
                }
            }
        }
        doChunk();

    });
</script>
