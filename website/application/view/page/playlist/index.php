<div class="row">
    <h1>상위 100 거래량</h1>
</div>
<!--/.row-->

<div class="row">
    <?php foreach($playlist_list as $playlist) { ?>
        <?php
        if (!count($playlist['list'])) {
            continue;
        }
        ?>
        <table class="table-group">
            <thead>
                <tr>
                    <th>날짜</th>
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
                <tr>
                    <td class="text-center" rowspan="<?php echo count($playlist['list']); ?>">
                        <?php echo $playlist['date']; ?>
                    </td>
                    <td class="text-center">
                        <?php echo $playlist['list'][0]->getRank(); ?>
                    </td>
                    <td>
                        <a href="/company/view/<?php echo $playlist['list'][0]->getCompanyIdx(); ?>">
                            <?php echo $playlist['list'][0]->getCompanyName(); ?>
                        </a>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist['list'][0]->getPercentage(), 2); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist['list'][0]->getPrice()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist['list'][0]->getPrevDiff()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist['list'][0]->getHigh()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist['list'][0]->getLow()); ?>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($playlist['list'][0]->getVolume()); ?>
                    </td>
                    <td class="text-center">
                        <a href="/company/comparesvm/<?php echo $playlist['list'][0]->getIdx(); ?>/<?php echo $playlist['list'][0]->getCompanyIdx(); ?>">SVM</a>
                        <a href="#">HHM</a>
                    </td>
                </tr>
                <?php foreach(array_slice($playlist['list'], 1) as $item) { ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $item->getRank(); ?>
                        </td>
                        <td>
                            <a href="/company/view/<?php echo $item->getCompanyIdx(); ?>">
                                <?php echo $item->getCompanyName(); ?>
                            </a>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($item->getPercentage(), 2); ?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($item->getPrice()); ?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($item->getPrevDiff()); ?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($item->getHigh()); ?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($item->getLow()); ?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($item->getVolume()); ?>
                        </td>
                        <td class="text-center">
                            <a href="/company/comparesvm/<?php echo $item->getIdx(); ?>/<?php echo $item->getCompanyIdx(); ?>">SVM</a>
                            <a href="#">HHM</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!--/.table-group-->

        <br /><br /><br />
    <?php } ?>
</div>
<!--/.row-->
