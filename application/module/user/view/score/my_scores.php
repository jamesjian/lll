<?php
/**
 */
?>

<div class="zx-front-breadcrumb">
    <?php 
    //echo \App\Transaction\Session::get_breadcrumb(); 
    ?>
</div>
<div class='zx-front-left1'>
    <?php include FRONT_VIEW_PATH . 'templates/left_google_ads.php'; ?>
</div>	
<div class='zx-front-left2'>
    <table>
        <tr>
            <td>原有分数</td>
            <td>原因</td>
            <td>变化</td>
            <td>当前分数</td>
            <td>时间</td>
        </tr>
        <?php
        foreach ($scores as $score) {
            ?>
            <tr>
                <td><a href='<?php echo $link; ?>'><?php echo $score['previous_score']; ?></a></td>
                <td><a href='<?php echo $link; ?>'><?php echo $score['operation']; ?></a></td>
                <td><a href='<?php echo $link; ?>'><?php echo $score['difference']; ?></a></td>
                <td><a href='<?php echo $link; ?>'><?php echo $score['current_score']; ?></a></td>
                <td><a href='<?php echo $link; ?>'><?php echo $score['date_created']; ?></a></td>
            </tr>
            <?php
        }//foreach
        ?>
    </table>                   
</div>
