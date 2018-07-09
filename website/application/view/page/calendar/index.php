<style>.container .content{padding: 20px;}</style>

<div class="row">
    <h1>달력 <?php echo $today_year."년 ". $today_month."월"; ?></h1>

    <div class="form">
        <a href="/calendar/?month=<?php echo $pre_month; ?>&year=<?php echo $pre_year; ?>">
            <div class="btn" style="margin-right: 10px;">
                << 전달
            </div>
        </a>
        <a href="/calendar/?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>">
            <div class="btn">
                다음 >>
            </div>
        </a>
    </div>
</div>
<!--/.row-->

<div class="row">
    <table class="calender-table">
        <thead>
            <tr>
                <th>월요일</th>
                <th>화요일</th>
                <th>수요일</th>
                <th>목요일</th>
                <th>금요일</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($calender_list as $calender) { ?>
                <tr>
                    <?php foreach($calender as $key => $day) { ?>
                        <?php
                            // 주말안 빼지 너무 자리 읍다.
                            if ($key == 0 || $key == 6)
                                continue;

                            $d = new DateTime($day['date']);
                        ?>
                        <td class="<?php echo ($day['current_month'])?'':'off'; ?>">
                            <div class="day-wrapper">
                                <div class="date">
                                    <a href="/playlist/?date=<?php echo $day['date']; ?>">
                                        <?php echo $d->format('d'); ?>
                                    </a>
                                </div>
                                <!--/.date-->

                                <?php if (count($day['top_list']) > 0) { ?>
                                    <div class="list-wrapper">
                                        <div class="list-title">
                                            상위
                                        </div>
                                        <!--/.list-title-->
                                        <ul>
                                            <?php foreach($day['top_list'] as $top) { ?>
                                                <li>
                                                    <a href="/company/view/<?php echo $top->getCompanyidx(); ?>">
                                                        <?php echo $top->getCompanyName(); ?>(<?php echo number_format($top->getPercentage(), 2); ?>%)
                                                    </a>
                                                    <span><a href="/company/comparesvm/<?php echo $top->getIdx(); ?>/<?php echo $top->getCompanyidx(); ?>">SVM</a></span>

                                                    <div class="svm-match">
                                                        <?php foreach($top->top_list as $result) { ?>
                                                            <div class="match-item">
                                                                <?php echo $result->company->getName(); ?>
                                                                <div class="score">
                                                                    <?php echo number_format($result->getScore()*100, 2); ?>%
                                                                </div>
                                                                <!--/.score-->
                                                            </div>
                                                            <!--/.match-item-->
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <!--/.list-wrapper-->
                                <?php } ?>
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
