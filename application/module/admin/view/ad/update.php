<form action="<?php echo ADMIN_HTML_ROOT . 'ad/update'; ?>" method="post">
    <fieldset>
        <legend>Update ad</legend>
        <dl>
            <dt> Title:</dt>
            <dd><input type="text" name="title" size="50" value="<?php if (isset($_POST['title'])) echo $_POST['title']; else echo $ad['title']; ?>"/></dd>
            <dt>    State:</dt><dd><select name='state'>
                    <?php
                    foreach ($states as $state) {
                        echo "<option value='" . $state . "'";
                        if ($ad['state'] == $state) {
                            echo " selected";
                        }
                        echo ">" . $state . '</option>';
                    }
                    ?>
                </select>
            </dd>            
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
            <dt>Views:</dt><dd><input type="text" name="num_of_views" size="50"  value="<?php if (isset($_POST['num_of_views'])) echo $_POST['num_of_views']; else echo $ad['num_of_views']; ?>"/>        </dd>
            <dt>    Content: </dt><dd>
                <textarea cols="10" rows="30" name="content"><?php if (isset($_POST['content'])) echo $_POST['content']; else echo $ad['content']; ?></textarea></dd>
            <dt> <input type="hidden" name="id" value="<?php echo $ad['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
