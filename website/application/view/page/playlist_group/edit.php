<div class="row">
    <h1>등록 및 수정</h1>
</div>
<!--/.row-->

<form method="POST" action="/playlist/group/save/">
    <input type="hidden" name="idx" value="<?php echo (isset($playlist_group->idx)) ? $playlist_group->getIdx() : ''; ?>" />
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 140px;">항목</th>
                <th>입력</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">순서</td>
                <td class="input">
                    <input type="number" name="sort_idx" value="<?php echo (isset($playlist_group->idx)) ? $playlist_group->getSortIdx() : ''; ?>" />
                </td>
            </tr>
            <tr>
                <td class="text-center">이름</td>
                <td class="input">
                    <input type="text" name="name" value="<?php echo (isset($playlist_group->idx)) ? $playlist_group->getName() : ''; ?>" />
                </td>
            </tr>
        </tbody>
    </table>
    <!--/.table-->
    <br/>
    <div class="row text-right">
        <button class="btn btn-primary">저장하기</button>
    </div>
    <!--/.row-->
</form>
