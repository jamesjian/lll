<?php
/**
 * left ads                     tag cloud
 *  article                        right ads
 * related articles                latest
 *                              hottest
 */
?>

<div class='zx-front-left'>	
    <div class="zx-front-breadcrumb">
        <?php echo \App\Transaction\Session::get_breadcrumb(); ?>
    </div>
    <div class='zx-front-left1'>
        <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
    </div>	
    <div class='zx-front-left2'>
        <?php //find tag ?>
        <form action="<?php echo FRONT_HTML_ROOT;?>tag/search" method="post">
            查找问题类别<input name="search" size="50" />
            <input type="submit" value="查找" />
        </form>
        <?php
        if ($tags) {
        ?>
        <table><tr>
        <?php
            $n = count($tags);
            $k = 5;
            for ($i = 0; $i < $n; $i++) {
                $tag = $tags[$i];
                $link = FRONT_HTML_ROOT . 'question/tag/' . $tag['id'] . '/page/1/' . $tag['name'];
                ?>
                <td>
                    <a href="<?php echo $link; ?>" class="zx-front-tag"><?php echo $tag['name']; ?></a>
                    x <?php echo $tag['num_of_questions'];?>
                </td>
                <?php
                if (($i + 1) % 5 == 0) {
                   echo "</tr><tr>";
                }//if
            }//for tag
        }//if tags
        ?>
            </tr>
        </table>
    </div>
    <?php
    //might have pagination if too many tags
    ?>
</div>

