<?php
/**
 * only content, no content1 (for num_of_answers>0)
 */
\Zx\Test\Test::object_log('$posted', $posted, __FILE__, __LINE__, __CLASS__, __METHOD__);
?>
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
<form class="zx-front-form" name="question_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>question/create">
    <fieldset>
        <legend>新问题</legend>
        <table>
            <tr>
                <td class="zx-front-table-title zx-front-required">标题:</td>
                <td class="table_input"> 
                    <input type="text" name="title"  class="form_element"   id="title" size="100"
                           value="<?php if (isset($posted['title'])) echo $posted['title']; ?>"/>
                </td>
            </tr>
            <tr>    
                <td class="zx-front-table-title zx-front-required"> 区域:</td>
                <td class="table_input">
                    <select id="region" name="region">
                        <?php
                        $regions = \App\Model\Region::get_au_states_abbr();
                        foreach ($regions as $index => $region) {
                            ?>
                            <option value="<?php echo $index; ?>"<?php
                        if (isset($_POST['region']) && $_POST['region'] == $region) {
                            echo ' selected';
                        }
                            ?>><?php echo $region; ?></option>
                                    <?php
                                }
                                ?></select>
                </td>               
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">关键词: </td>
                <td>您可以输入最多5个关键词。 <a href="<?php echo FRONT_HTML_ROOT . 'tag/usage'; ?>" class="zx-front-tag-usage">( 点击此处查看关键词使用规则 )</a></td>
            </tr>
            <tr>
                <td></td>
                <td class="table_input">  
                    <input type="text" name="tname1"  class="form_element" value="<?php if (isset($posted['tname1'])) echo $posted['tname1']; ?>"/>
                    <input type="text" name="tname2"  class="form_element" value="<?php if (isset($posted['tname2'])) echo $posted['tname2']; ?>"/>
                    <input type="text" name="tname3"  class="form_element" value="<?php if (isset($posted['tname3'])) echo $posted['tname3']; ?>"/>
                    <br />
                    <input type="text" name="tname4"  class="form_element" value="<?php if (isset($posted['tname4'])) echo $posted['tname4']; ?>"/>
                    <input type="text" name="tname5"  class="form_element" value="<?php if (isset($posted['tname5'])) echo $posted['tname5']; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">内容:</td>
                <td class="table_input"> <textarea cols="10" rows="30" name="content"><?php
                if (isset($posted['content'])) echo $posted['content']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">发布</button>
                    <a href="<?php echo \App\Transaction\Html::get_previous_page(); ?>" />返回</a>
                    <?php
                    if (!\App\Transaction\User::user_has_loggedin()) {
                        ?>
                        ( 注册用户登录后发布新问题可以获得积分。
                        <a href="<?php echo FRONT_HTML_ROOT; ?>user/register">注册新用户</a> 或 <a href="<?php echo FRONT_HTML_ROOT; ?>user/login">现在登录</a> )
                        <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
</form>

<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
