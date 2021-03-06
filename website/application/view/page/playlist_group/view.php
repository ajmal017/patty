<div class="row">
    <h1><?php echo $playlist_group->getName(); ?></h1>
</div>
<!--/.row-->

<table class="table table-bordered">
    <thead>
        <tr>
            <th>회사명</th>
            <th style="width: 180px;">등록일</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!count($watch_list)) { ?>
            <tr>
                <td class="text-center" colspan="3">
                    등록된 종목이 없습니다.
                </td>
            </tr>
        <?php } ?>
        <?php foreach($watch_list as $watch) { ?>
            <tr>
                <td class="text-left">
                    <a href="/company/view/<?php echo $watch->getCompanyIdx(); ?>">
                        <?php echo $watch->getCompanyName(); ?>
                    </a>
                </td>
                <td class="text-center">
                    <?php echo $watch->getCreatedDateTime('Y년 m월 d일'); ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!--/.table-->
