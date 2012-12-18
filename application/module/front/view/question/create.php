<?php
/**
 * only content, no content1 (for num_of_answers>0)
 */
?>
<h4>新问题</h4>
<div class="clear-both"></div>
<?php
if (isset($errors)) {
    echo "<div  class='errormessage'>";
    foreach ($errors as $key => $message) {
        echo $message, BR;
    }
    echo "</div>";
}
\Zx\Message\Message::show_message();
?>
<form id="login_form" name="login_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>question/create">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="table_title required">标题:</td>
                <td class="table_input"> 
                    <input type="text" name="title"  class="form_element"   id="title" 
                           value="<?php if (isset($posted['title'])) echo $posted['title']; ?>"/>
                </td>
            </tr>
            <tr>    
                <td class="table_title required"> State:</td>
                <td class="table_input">
                    <select id="region" name="region">
                        <?php
                        $regions = \App\Model\Region::get_au_states_abbr();
                        foreach ($regions as $region) {
                            ?>
                            <option value="<?php echo $region; ?>"
                            <?php
                            if (isset($_POST['region']) && $_POST['region'] == $region) {
                                echo ' selected';
                            }
                            ?>><?php echo $region; ?>             
                            </option>
                            <?php
                        }
                        ?></select>
                </td>               
            </tr>
            <tr>
                <td class="table_title required">关键词: <a href="<?php echo FRONT_HTML_ROOT . 'tag/usage';?>" class="zx-front-tag-usage">（点击此处查看关键词使用规则)</a>
                </td>
                <td class="table_input">  
                    <input type="text" name="tname1"  class="form_element" value="<?php if (isset($posted['tname1'])) echo $posted['tname1']; ?>"/>
                    <input type="text" name="tname2"  class="form_element" value="<?php if (isset($posted['tname2'])) echo $posted['tname2']; ?>"/>
                    <input type="text" name="tname3"  class="form_element" value="<?php if (isset($posted['tname3'])) echo $posted['tname3']; ?>"/>
                    <input type="text" name="tname4"  class="form_element" value="<?php if (isset($posted['tname4'])) echo $posted['tname4']; ?>"/>
                    <input type="text" name="tname5"  class="form_element" value="<?php if (isset($posted['tname5'])) echo $posted['tname5']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="table_title required">内容:</td>
                <td class="table_input"> 
                    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php if (isset($posted['content'])) echo $posted['content']; ?></textarea>
                </td>
                </tr>
            <tr>
                <td>
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">发布</button>
                    (如果你已登录， 新问题将会立即被发布， 如果你尚未登录， 新问题审核通过后才会显示。）
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/login">现在登录</a>或
                    <a href="<?php echo FRONT_HTML_ROOT; ?>user/register">注册新用户</a>

                </td>
            </tr>
        </table>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
