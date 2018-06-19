<div class="row">
    <h1>그룹 관리</h1>

    <div class="form">
        <a href="/playlist/group/create/" class="btn">등록</a>
    </div>
    <!--/.form-->
</div>
<!--/.row-->

<table class="table table-bordered">
    <thead>
        <tr>
            <th>그룹</th>
            <th style="width: 100px;">개수</th>
            <th colspan="2">관리</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!count($playlist_group_list)) { ?>
            <tr>
                <td class="text-center" colspan="4">
                    등록된 그룹이 없습니다.
                </td>
            </tr>
        <?php } ?>
        <?php foreach($playlist_group_list as $playlist_group) { ?>
            <tr>
                <td class="text-left">
                    <?php echo $playlist_group->getName(); ?>
                </td>
                <td class="text-center">
                    <?php echo $playlist_group->getCount()->cnt; ?>
                </td>
                <td class="text-center" style="width: 70px;">
                    <a href="/playlist/group/edit/<?php echo $playlist_group->getIdx(); ?>">수정</a>
                </td>
                <td class="text-center" style="width: 70px;">
                    <a href="/playlist/group/delete/<?php echo $playlist_group->getIdx(); ?>" onclick="return confirm('삭제하시겠습니까?');">
                        삭제
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!--/.table-->
