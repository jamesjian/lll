<?php
use \Zx\Message\Message as Zx_Message;
Zx_Message::show_message();
?>
<form action="<?php echo ADMIN_HTML_ROOT . 'question/create_by_admin'; ?>" method="post">
    <fieldset>
        <legend>Create question</legend>
        <dl>
            <dt>Title:</dt><dd><input type="text" name="title" size="50" value="<?php
            if (isset($_POST['title'])) echo $_POST['title'];?>"/></dd>
            <dt> Tags:</dt><dd><input type="text" name="tag_names" size="50"  value="<?php
            if (isset($_POST['tag_names'])) echo $_POST['tag_names'];?>"/></dd>
            <dt>Region</dt>
            <dd><select id="region" name="region">
<?php $regions = \App\Model\Region::get_au_states_abbr();            
foreach ($regions as $region) {
    ?>
                    <option value="<?php echo $region;?>"
                    <?php if (isset($_POST['region']) && $_POST['region'] == $region) {
                        echo ' selected';
                    }
                    ?>><?php echo $region;?>             
                    </option>
                    <?php
}
?></select>
            </dd>
            <dt> Status:</dt>
            <dd><input type="radio" name="status" value="1" />Active    
                <input type="radio" name="status" value="0" />Inactive    </dd>
            <dt> Content: </dt><dd><textarea cols="10" rows="30" name="q_content"><?php
            if (isset($_POST['q_content'])) echo $_POST['q_content'];?></textarea></dd>
            <dt> Answer: </dt><dd><textarea cols="10" rows="30" name="a_content"><?php
            if (isset($_POST['a_content'])) echo $_POST['a_content'];?></textarea></dd>
            <dt> </dt>
            <dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('q_content');
echo CKEDITOR::ckReplaceEditor_Full('a_content');
