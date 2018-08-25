<script type="text/javascript" src="/public/js/vendor/jquery.easypiechart.min.js"></script>
<script type="text/javascript" src="/public/js/vendor/google.chart.js"></script>

<div class="row">
    <h1>처리 상태</h1>
</div>
<!--/.row-->

<div class="row col">
    <div class="col-3">
        <div class="line-title">매일 업데이트</div>
        <!--/.line-title-->
        <div class="pie-graph" data-percent="<?php echo $company_daily_update_waiting_count; ?>"><span class="pie-value text-thin text-2x"></span></div>
        <!--/.pie-graph-->
    </div>
    <!--/.col-3-->

    <div class="col-3">
        <div class="line-title">신규등록 처리 대기</div>
        <!--/.line-title-->
        <div class="pie-graph" data-percent="<?php echo $company_history_count; ?>"><span class="pie-value text-thin text-2x"></span></div>
        <!--/.pie-graph-->
    </div>
    <!--/.col-3-->

    <div class="col-3">
        <div class="line-title">Slow Query 로그</div>
        <!--/.line-title-->
        <br />
        <a href="/cron/download_slowquery/">
            <div class="btn btn-primary">
                다운로드
            </div>
        </a>
    </div>
    <!--/.col-3-->

    <div class="col-3">
        <div class="line-title">SVM 프로세스</div>
        <!--/.line-title-->
        <div id="svm_pie"></div>
        <div class="">
            <?php echo $playlist_svm[1][0]; ?>: <?php echo $playlist_svm[1][1]; ?>
            <?php echo $playlist_svm[2][0]; ?>: <?php echo $playlist_svm[2][1]; ?>
            <?php echo $playlist_svm[3][0]; ?>: <?php echo $playlist_svm[3][1]; ?>
        </div>
    </div>
    <!--/.col-3-->

    <div class="col-3">
        <div class="line-title">초기화 ML</div>
        <!--/.line-title-->
        <br />
        <a href="/cron/clear_ml/" onclick="return confirm('확실합니까?');">
            <div class="btn btn-primary">
                리셋팅
            </div>
        </a>
    </div>
    <!--/.col-3-->

    <div class="col-3">
        <div class="line-title">Company Status</div>
        <!--/.line-title-->
        <br />
        <b>Total: </b> <?php echo number_format($company_total_count); ?><br />
        <b>Exclude Learn: </b> <?php echo number_format($company_exclude_learn_count); ?><br />
    </div>
    <!--/.col-3-->
</div>
<!--/.row-->

<script>
    $(function() {
        $(".pie-graph").each(function() {
            $(this).easyPieChart({
                barColor :'#efb239',
                scaleColor: '#969696',
                trackColor : 'rgba(0,0,0,.1)',
                lineWidth : 7,
                size : 200,
                onStep: function(from, to, percent) {
                    $(this.el).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
        });

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(function() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($playlist_svm); ?>);
            var chart = new google.visualization.PieChart(document.getElementById('svm_pie'));
            chart.draw(data, { title: '', pieHole: 0.4 });
        });
    });
</script>
