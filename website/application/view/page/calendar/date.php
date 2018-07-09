<?php foreach($top_list as $top) { ?>
    <li>
        <a href="/company/view/<?php echo $top->getCompanyidx(); ?>" class="company-color company-<?php echo $top->getCompanyIdx(); ?>" data-company="<?php echo $top->getCompanyIdx(); ?>">
            <?php echo $top->getCompanyName(); ?>(<?php echo number_format($top->getPercentage(), 2); ?>%)
        </a>
        <span><a href="/company/comparesvm/<?php echo $top->getIdx(); ?>/<?php echo $top->getCompanyidx(); ?>">SVM</a></span>

        <div class="svm-match">
            <?php foreach($top->top_list as $result) { ?>
                <div class="match-item company-color-<?php echo $result->company->getIdx(); ?>" data-company-idx="<?php echo $result->company->getIdx(); ?>">
                    <a href="/company/view/<?php echo $result->company->getIdx(); ?>">
                        <?php echo $result->company->getName(); ?>
                    </a>
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
