<div class="row">
    <h1>일별 분석 <?php echo $date; ?></h1>

    <div class="form">
        <a href="/playlist/?date=<?php echo $yesterday; ?>">
            <div class="btn" style="margin-right: 10px;">
                << 전달
            </div>
        </a>
        <a href="/playlist/?date=<?php echo $tomorrow; ?>">
            <div class="btn">
                다음 >>
            </div>
        </a>
    </div>
</div>
<!--/.row-->

<div class="row">
    <br /><br />
    <div class="line-title">
        상위 거래량
    </div>
    <!--/.line-title-->

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>순서</th>
                <th>회사</th>
                <th>변화율</th>
                <th>가격</th>
                <th>차이</th>
                <th>고가</th>
                <th>저가</th>
                <th>거래량</th>
                <th>모델</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!count($playlist_list)) { ?>
                <tr>
                    <td colspan="9" class="text-center">
                        등록된 종목이 없습니다.
                    </td>
                </tr>
            <?php } ?>
            <?php foreach($playlist_list as $playlist) { ?>
                <tr>
                    <td class="text-center">
                        <?php echo $playlist->getRank(); ?>
                    </td>
                    <td>
                        <a href="/company/view/<?php echo $playlist->getCompanyIdx(); ?>">
                            <?php echo $playlist->getCompanyName(); ?>
                        </a>
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
                    <td class="text-center">
                        <a href="/company/comparesvm/<?php echo $playlist->getIdx(); ?>/<?php echo $playlist->getCompanyIdx(); ?>">SVM</a>
                        <a href="#">HHM</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!--/.table-group-->
    <br /><br /><br />
</div>
<!--/.row-->
