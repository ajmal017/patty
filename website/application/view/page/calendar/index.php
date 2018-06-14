<style>.container .content{padding: 20px;}</style>

<div class="row">
    <h1>Report</h1>
</div>
<!--/.row-->

<div class="row">
    <table class="calender-table">
        <thead>
            <tr>
                <th>일요일</th>
                <th>월요일</th>
                <th>화요일</th>
                <th>수요일</th>
                <th>목요일</th>
                <th>금요일</th>
                <th>토요일</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($calender_list as $calender) { ?>
                <tr>
                    <?php foreach($calender as $day) { ?>
                        <?php
                            $d = new DateTime($day['date']);
                        ?>
                        <td class="<?php echo ($day['current_month'])?'':'off'; ?>">
                            <div class="day-wrapper">
                                <div class="date">
                                    <?php echo $d->format('d'); ?>
                                </div>
                                <!--/.date-->

                                <div class="list-wrapper">
                                    <div class="list-title">
                                        상위 100개
                                    </div>
                                    <!--/.list-title-->
                                    <ul>
                                        <?php foreach($day['top_list'] as $top) { ?>
                                            <li>
                                                <a href="/company/view/<?php echo $top->getCompanyidx(); ?>">
                                                    <?php echo $top->getCompanyName(); ?>
                                                </a>
                                                <span><?php echo number_format($top->getPercentage(), 2); ?>%</span>
                                                <span>
                                                    <a href="/company/comparesvm/<?php echo $top->getIdx(); ?>/<?php echo $top->getCompanyidx(); ?>">SVM</a>
                                                </span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!--/.list-wrapper-->

                                <div class="list-wrapper">
                                    <div class="list-title">
                                        와치
                                    </div>
                                    <!--/.list-title-->
                                    <ul>
                                        <?php foreach($day['top_list'] as $top) { ?>
                                            <li>
                                                <?php echo $top->getCompanyName(); ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!--/.list-wrapper-->
                            </div>
                            <!--/.day-wrapper-->
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!--/.row-->
