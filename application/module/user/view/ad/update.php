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
<form id="login_form" name="login_form" method="post" action="<?php echo FRONT_HTML_ROOT; ?>ad/update">
    <fieldset class="fyl_fieldset">
        <table>
            <tr>
                <td class="table_title required">标题:</td>
                <td class="table_input"> 
                    <input type="text" name="title"  class="form_element"   id="title" 
                           value="<?php if (isset($posted['title'])) echo $posted['title']; else echo $ad['title'];?>"/>
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
                        if ((isset($_POST['region']) && $_POST['region'] == $region)
                                || ($question['region'] == $region)) {
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
                    <input type="text" name="tname1"  class="form_element" value="<?php if (isset($posted['tname1'])) echo $posted['tname1']; else echo $question['tname1']; ?>"/>
                    <input type="text" name="tname2"  class="form_element" value="<?php if (isset($posted['tname2'])) echo $posted['tname2']; else echo $question['tname2']; ?>"/>
                    <input type="text" name="tname3"  class="form_element" value="<?php if (isset($posted['tname3'])) echo $posted['tname3']; else echo $question['tname3']; ?>"/>
                    <br />
                    <input type="text" name="tname4"  class="form_element" value="<?php if (isset($posted['tname4'])) echo $posted['tname4']; else echo $question['tname4']; ?>"/>
                    <input type="text" name="tname5"  class="form_element" value="<?php if (isset($posted['tname5'])) echo $posted['tname5']; else echo $question['tname5']; ?>"/>
                </td>
            </tr>            
            <tr>
                <td class="table_title required">内容:</td>
                <td class="table_input"> 
                    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php 
                    if (isset($posted['content'])) echo $posted['content'];
                      else echo $ad['tnames'];
                      ?></textarea>
                </td>
                </tr>
            <tr>
                <td>
                    <input type="hidden" name="id" value="<?php echo $id;?>" />
                </td>
                <td>
                    <button type='submit' name='submit' value="submit">更新</button>
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
