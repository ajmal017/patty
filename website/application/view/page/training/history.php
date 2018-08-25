<table class="table table-bordered">
    <thead>
        <tr>
            <th>idx</th>
            <th>시작</th>
            <th>끝</th>
            <th>걸린 시간</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($history_list as $history) { ?>
            <tr>
                <td class="text-center">
                    <?php echo $history->getIdx(); ?>
                </td>
                <td class="text-center">
                    <?php echo $history->getStartt(); ?>
                </td>
                <td class="text-center">
                    <?php if ($history->getEndt() != '0000-00-00 00:00:00') { ?>
                        <?php echo $history->getEndt(); ?>
                    <?php } ?>
                </td>
                <td class="text-center">
                    <?php if ($history->getEndt() != '0000-00-00 00:00:00') { ?>
                        <?php
                            $date1 = date_create($history->getStartt());
                            $date2 = date_create($history->getEndt());
                            $diff = $date1->diff($date2);
                            echo $diff->format('%a days %H hr %i min  %s sec');
                        ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
