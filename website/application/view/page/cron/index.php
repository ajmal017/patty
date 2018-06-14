<script type="text/javascript" src="/public/js/vendor/jquery.easypiechart.min.js"></script>

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
    });
</script>
