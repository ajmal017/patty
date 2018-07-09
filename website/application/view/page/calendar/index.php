<style>.container .content{padding: 20px;}</style>

<div class="row">
    <h1>달력 <?php echo $today_year."년 ". $today_month."월"; ?></h1>

    <div class="form">
        <div class="btn" style="margin-right: 10px;" onclick="hide_no_parents();">
            X 부모
        </div>
        <div class="btn" style="margin-right: 10px;" onclick="highlight_matches();">
            ** 표시
        </div>
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

                                <div class="list-wrapper">
                                    <div class="list-title">
                                        상위
                                    </div>
                                    <!--/.list-title-->
                                    <ul data-date="<?php echo $d->format('Y-m-d'); ?>" class="date-to-load">

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

<script>
    var company_color = { };
    $(function() {
        $(".date-to-load").each(function() {
            var element = $(this);
            var date = element.data("date");
            var query = {
                "type"      : "GET",
                "url"       : "/calendar/date/" + date,
                "data"      : {},
                "dataType"  : "html",
                "success"   : function(result) {
                    element.html(result);
                }
            }
            $.ajax(query);
        });
    });
    var hide_no_parents = function() {
        $(".match-item").each(function() {
            var company_idx = $(this).data("company-idx");
            if (!$(".company-" + company_idx).length) {
                $(this).hide();
            }
        });
    };
    var highlight_matches = function() {
        $(".company-color").each(function() {
            var company = $(this);
            var company_idx = company.data("company");

            var color = get_random_color();
            if (!company_color[company_idx]) {
                company_color[company_idx] = color;
            } else {
                color = company_color[company_idx];
            }

            var child_list = $(".company-color-" + company_idx);
            if (child_list.length) {
                company.css('background-color', color);
                child_list.each(function() {
                    var child = $(this);
                    child.css('background-color', color);
                });
            }
        });
    };
    var get_random_color = function() {
        return Math.floor(Math.random()*16777215).toString(16) + "33";
    }
</script>
