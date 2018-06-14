<script type="text/javascript" src="/public/js/vendor/canvasjs.min.js"></script>
<style>.container .content { padding: 40px; }</style>

<div class="row col">
    <div class="col-3" style="padding-right: 10px;">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2">
                        <?php echo $company->getName(); ?>
                    </td>
                </tr>
                <tr>
                    <td>가격</td>
                    <td class="text-right">
                        <?php echo number_format($detail->getPrice()); ?> 원
                    </td>
                </tr>
                <tr>
                    <td>고가</td>
                    <td class="text-right">
                        <?php echo number_format($detail->getHigh()); ?> 원
                    </td>
                </tr>
                <tr>
                    <td>저가</td>
                    <td class="text-right">
                        <?php echo number_format($detail->getHigh()); ?> 원
                    </td>
                </tr>
                <tr>
                    <td>거래량</td>
                    <td class="text-right">
                        <?php echo number_format($detail->getVolume()); ?>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <!--/.col-3-->

    <div class="col-9">
        <div class="graph">
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        </div>
    </div>
    <!--/.col-9-->
</div>
<!--/.row-->

<br /><br /><br />

<div class="row">
    <div class="line-title">
        상위 100 거래량
    </div>
    <!--/.line-title-->

    <table class="table-group">
        <thead>
            <tr>
                <th>날짜</th>
                <th>순서</th>
                <th>변화율</th>
                <th>가격</th>
                <th>차이</th>
                <th>고가</th>
                <th>저가</th>
                <th>거래량</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($top_playlist as $playlist) { ?>
                <tr>
                    <td class="text-center">
                        <?php echo $playlist->getDate(); ?>
                    </td>
                    <td class="text-center">
                        <?php echo $playlist->getRank(); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist->getPercentage(), 2); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist->getPrice()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist->getPrevDiff()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist->getHigh()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist->getLow()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist->getVolume()); ?>
                    </td>
                </tr>
            <?php } ?>
            <?php if (!count($top_playlist)) { ?>
                <tr>
                    <td colspan="8">
                        아직 등록된 playlist 없습니다.
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!--/.table-group-->
</div>
<!--/.row-->

<br /><br /><br />

<div class="row col">
    <div class="col-6">
        <div class="line-title">
            SVM 정확도
        </div>
        <!--/.line-title-->
    </div>
    <!--/.col-6-->

    <div class="col-6">
        <div class="line-title">
            HMM
        </div>
        <!--/.line-title-->
    </div>
    <!--/.col-6-->
</div>
<!--/.row-->

<script>
    $(function() {
        var chart = new CanvasJS.Chart("chartContainer", {
        	animationEnabled: true,
        	theme: "light2", // "light1", "light2", "dark1", "dark2"
        	exportEnabled: true,
            zoomEnabled: true,
        	title:{
        		text: ""
        	},
        	axisX: {
        		valueFormatString: "MMM"
        	},
        	axisY: {
        		includeZero:false,
        		prefix: "$",
        		title: "Price (in USD)"
        	},
        	toolTip: {
        		shared: true
        	},
        	data: [{
        		type: "candlestick",
        		name: "AT&T",
        		yValueFormatString: "$###0.00",
        		xValueFormatString: "MMMM YY",
        		dataPoints: <?php echo str_replace('"', '', json_encode($ohlc_list, true) ); ?>
        	}]
        });
        chart.render();
    });
</script>
