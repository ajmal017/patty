<div class="row">
    <h1>Playlist Group</h1>
</div>
<!--/.row-->

<table class="table table-bordered">
    <thead>
        <tr>
            <th>회사명</th>
            <th style="width: 160px;">개수</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($playlist_group_list as $playlist_group) { ?>
            <tr>
                <td class="text-left">
                    <?php echo $playlist_group->getName(); ?>
                </td>
                <td class="text-center">
                    <?php echo $playlist_group->getCount()->cnt; ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!--/.table-->
