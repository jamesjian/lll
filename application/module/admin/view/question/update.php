<form action="<?php echo ADMIN_HTML_ROOT . 'question/update'; ?>" method="post">
    <fieldset>
        <legend>Update question</legend>
        <dl>
            <dt> Title:</dt>
            <dd><input type="text" name="title" size="50" value="<?php if (isset($_POST['title'])) echo $_POST['title']; else echo $question['title']; ?>"/></dd>
            <dt>    State:</dt><dd><select name='state'>
                    <?php
                    foreach ($states as $state) {
                        echo "<option value='" . $state . "'";
                        if ($question['state'] == $state) {
                            echo " selected";
                        }
                        echo ">" . $state . '</option>';
                    }
                    ?>
                </select>
            </dd>            
            <dt>
                关键词: </dt>
                <dd>您可以输入最多5个关键词。 <a href="<?php echo FRONT_HTML_ROOT . 'tag/usage'; ?>" class="zx-front-tag-usage">( 点击此处查看关键词使用规则 )</a></dd>
                <?php 
                $tnames = explode(TNAME_SEPERATOR, substr($question['tnames'], 1, -1));
                
                ?>
                   <dt></dt><dd> <input type="text" name="tname1"  class="form_element" value="<?php if (isset($posted['tname1'])) echo $posted['tname1']; elseif (isset($tnames[0])) echo $tnames[0]; ?>"/></dd>
                  <dt></dt> <dd> <input type="text" name="tname2"  class="form_element" value="<?php if (isset($posted['tname2'])) echo $posted['tname2']; elseif (isset($tnames[1])) echo $tnames[1]; ?>"/></dd>
                   <dt></dt><dd> <input type="text" name="tname3"  class="form_element" value="<?php if (isset($posted['tname3'])) echo $posted['tname3']; elseif (isset($tnames[2])) echo $tnames[2]; ?>"/></dd>
                   <dt></dt><dd> <input type="text" name="tname4"  class="form_element" value="<?php if (isset($posted['tname4'])) echo $posted['tname4']; elseif (isset($tnames[3])) echo $tnames[3]; ?>"/></dd>
                   <dt></dt> <dd><input type="text" name="tname5"  class="form_element" value="<?php if (isset($posted['tname5'])) echo $posted['tname5']; elseif (isset($tnames[4])) echo $tnames[4]; ?>"/></dd>
            </tr>            
            <dt>Votes:</dt><dd><input type="text" name="num_of_votes" size="50"  value="<?php if (isset($_POST['num_of_votes'])) echo $_POST['num_of_votes']; else echo $question['num_of_votes']; ?>"/>        </dd>
            <dt>Views:</dt><dd><input type="text" name="num_of_votes" size="50"  value="<?php if (isset($_POST['num_of_views'])) echo $_POST['num_of_views']; else echo $question['num_of_views']; ?>"/>        </dd>
            <dt>Answers:</dt><dd><input type="text" name="num_of_answers" size="50"  value="<?php if (isset($_POST['num_of_answers'])) echo $_POST['num_of_answers']; else echo $question['num_of_answers']; ?>"/>        </dd>
            <dt>    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php if (isset($_POST['content'])) echo $_POST['content']; else echo $question['content']; ?></textarea></dd>
            <dt>    Status:</dt>
            <dd>
                <select name='status'>
                    <?php
                    foreach ($statuses as $val=>$text) {
                        echo "<option value='" . $val . "'";
                        if ($question['status'] == $val) {
                            echo " selected";
                        }
                        echo ">" . $text . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt> <input type="hidden" name="id" value="<?php echo $question['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
