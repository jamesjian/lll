<ul>
    <li><a href="<?php echo HTML_ROOT; ?>" title="homepage">Home</a></li>
    <?php
    if ($cat_groups) {
        $current_cat_group = \App\Transaction\Session::get_front_current_cat_group();
        foreach ($cat_groups as $group) {
            $link = HTML_ROOT . 'front/catgroup/content/' . $group['title'];
            if ($current_cat_group == $group['title']) {
                $active_class = ' class="zx-front-active-menu"';
            } else {
                $active_class = '';
            }
            ?>
            <li <?php echo $active_class; ?>><a href="<?php echo $link; ?>" title="<?php echo $group['title']; ?>"><?php echo $group['title']; ?></a>
                <div>
                    <?php
                    //list categories within this group
                    foreach ($group['cat1s'] as $cat1) {
                        $link = HTML_ROOT . 'front/category/l1/' . $cat1['title'];
                        ?>
                        <dt><a href="<?php echo $link; ?>" title="<?php echo $cat1['title']; ?>"><?php echo $cat1['title']; ?></a></dt>
                        <dd>
                            <?php
                            foreach ($cat1['cat2s'] as $cat2) {
                                $link = HTML_ROOT . 'front/category/l2/' . $cat1['title'];
                                ?>

                                <a href="<?php echo $link; ?>" title="<?php echo $cat1['title']; ?>"><?php echo $cat1['title']; ?></a>
                                <?php
                            }//cat2s
                            ?>
                        </dd>

                        <?php
                    } //cat1s
                    ?>
                </div>
            </li>
        <?php
    }//groups
}//if cat_groups
?>

</ul>