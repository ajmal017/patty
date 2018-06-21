<script type="text/javascript" src="/public/js/vendor/canvasjs.min.js"></script>
<style>.container .content { padding: 40px; }</style>

<div class="row col">
    <div class="col-3" style="padding-right: 10px;">
        <div class="line-title">
            기본 정보
        </div>
        <!--/.line-title-->

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2" style="font-size: 22px;">
                        <b><?php echo $company->getName(); ?></b>
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

        <br /><br />

        <div class="line-title">
            그룹 목록
        </div>
        <!--/.line-title-->

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>이름</th>
                    <th style="width: 70px;">방식</th>
                    <th style="width: 120px;">날짜</th>
                    <th style="width: 40px;">옵션</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!count($linked_group_list)) { ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            등록된 그룹이 없습니다.
                        </td>
                    </tr>
                <?php } ?>
                <?php foreach($linked_group_list as $group) { ?>
                    <tr>
                        <td style="padding: 10px 0px 10px 10px;">
                            <?php echo $group->getGroupName(); ?>
                        </td>
                        <td class="text-center" style="padding: 10px 0px;">
                            <?php if ($group->getType() == WatchType::DAILY) { ?>
                                매일
                            <?php } else if ($group->getType() == WatchType::ONCE) { ?>
                                한번만
                            <?php } ?>
                        </td>
                        <td class="text-center" style="padding: 10px 0px;">
                            <?php echo $group->getCreatedDateTime('Y년 m월 d일'); ?>
                        </td>
                        <td class="text-center" style="padding: 10px 0px;">
                            <a href="/company/remove_group/<?php echo $company->getIdx(); ?>/<?php echo $group->getIdx(); ?>" onclick="return confirm('삭제하시겠습니까?');" style="cursor: pointer;">
                                삭제
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <br /><br />

        <div class="line-title">
            그룹 등록
        </div>
        <!--/.line-title-->

        <form method="POST" action="/company/add_group/<?php echo $company->getIdx(); ?>">
            <input type="hidden" name="company_stock_idx" value="<?php echo $detail->getIdx(); ?>" />
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="padding: 0px;">
                            <select style="padding: 5px; width: 100%;" name="type">
                                <option value="<?php echo WatchType::DAILY; ?>">매일반복</option>
                                <option value="<?php echo WatchType::ONCE; ?>">하루만</option>
                            </select>
                        </td>
                        <td class="text-center" style="width: 80px;" rowspan="2">
                            <button class="btn btn-primary" type="submit">등록</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px;">
                            <select style="padding: 5px; width: 100%;" name="group_idx">
                                <?php foreach($group_list as $group) { ?>
                                    <option value="<?php echo $group->getIdx(); ?>">
                                        <?php echo $group->getName(); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <!--/.col-3-->

    <div class="col-9">
        <div class="graph">
            <div id="chartContainer" style="height: 490px; width: 100%;"></div>
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
    <div class="col-6" style="padding-right: 10px;">
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
