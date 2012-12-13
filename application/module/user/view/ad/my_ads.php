<?php
/**
 */
?>

<div class='zx-front-left'>	
    <div class="zx-front-breadcrumb">
        <?php echo \App\Transaction\Session::get_breadcrumb(); ?>
    </div>
    <div class='zx-front-left1'>
        <?php 
        $create_link = USER_HTML_ROOT . 'ad/create';
        ?>
        <a href="<?php echo $create_link;?>">新广告(您还有<?php echo $user['num_of_answers'];?>个广告积分</a>
        <table>
            <tr>
                <td>序号</td>
                <td>标题</td>
                <td>类别</td>
                <td>积分</td>
                <td>时间</td>
                <td>操作</td>
            </tr>
            <?php
            foreach ($ads as $ad) {
                $ad_id = $ad['id'];
                $update_link = USER_HTML_ROOT . 'ad/update/' . $ad_id;
                $update_link = USER_HTML_ROOT . 'ad/delete/' . $ad_id;
                $update_link = USER_HTML_ROOT . 'ad/extend/' . $ad_id;
            ?>
            <tr>
                <td><?php echo $ad_id;?></td>
                <td><?php echo $ad['title'];?></td>
                <td><?php echo $ad['tnames'];?></td>
                <td><?php echo $ad['score'];?></td>
                <td><?php echo $ad['date_created'];?></td>
                <td><a href="<?php echo $update_link;?>">更改</a></td>
                <td><a href="<?php echo $delete_link;?>">删除</a></td>
                <td><a href="<?php echo $delete_link;?>">延长</a></td>
            </tr>
            <?php
            }
            //pagination
            ?>
        </table>
    </div>	
    <div class='zx-front-left2'>
        
    </div>

</div>
<div class='zx-front-right'>
    <div class='zx-front-right1'>
    </div>	
    <div class="zx-front-right2">
    </div>    
    <div class='zx-front-right3'>
    </div>
    <div class='zx-front-right4'>
    </div>
    <div class='zx-front-right5'>
    </div>
</div>
