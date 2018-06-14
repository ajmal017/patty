<form class="searchbox" action="/company/search/" method="POST">
    <div class="search-icon">
        <div class="circle"></div>
        <!--/.circle-->
        <div class="bar"></div>
        <!--/.bar-->
    </div>
    <!--/.search-icon-->
    <input type="text" name="search" value="<?php echo (isset($search)) ? $search : ''; ?>" placeholder="Name"/>
</form>

<?php if (isset($company_list)) { ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>회사명</th>
                <th>코드</th>
                <th>역사</th>
                <th>금일업데이트</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($company_list as $company) { ?>
                <tr>
                    <td >
                        <a href="/company/view/<?php echo $company->getIdx(); ?>">
                            <?php echo $company->getName(); ?>
                        </a>
                    </td>
                    <td class="text-center">
                        <?php echo $company->getCode(); ?>
                    </td>
                    <td class="text-center">
                        <?php echo ($company->getNeedHistory() == 2) ? '대기' : '완료'; ?>
                    </td>
                    <td class="text-center">
                        <?php echo ($company->getLastUpdated() == date('Y-m-d')) ? '완료' : '대기'; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
