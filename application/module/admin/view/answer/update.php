<form action="<?php echo ADMIN_HTML_ROOT . 'article/update'; ?>" method="post">
    <fieldset>
        <legend>Update article</legend>
        <dl>
            <dt>    
            Title:</dt><dd><input type="text" name="title" size="50" value="<?php echo $article['title']; ?>"/></dd>
            <dt>Title(En):</dt><dd><input type="text" name="title_en" size="50" value="<?php echo $article['title_en']; ?>"/></dd>
            <dt>URL:</dt><dd><input type="text" name="url" size="50" value="<?php echo $article['url']; ?>"/></dd>
            <dt>Abstract:</dt><dd><input type="text" name="abstract" size="50" value="<?php echo $article['abstract']; ?>"/></dd>
            <dt>Keyword:</dt><dd><input type="text" name="keyword" size="50" value="<?php echo $article['keyword']; ?>"/></dd>
            <dt>Keyword(En):</dt><dd><input type="text" name="keyword_en" size="50" value="<?php echo $article['keyword_en']; ?>"/></dd>
            <dt>Rank:</dt><dd><input type="text" name="rank" size="50"  value="<?php echo $article['rank']; ?>"/>        </dd>
            <dt>    Content: </dt><dd><textarea cols="10" rows="30" name="content"><?php echo $article['content']; ?></textarea></dd>
            <dt>    Category:</dt><dd><select name='cat_id'>
                    <?php
                    foreach ($cats as $cat) {
                        echo "<option value='" . $cat['id'] . "'";
                        if ($article['cat_id'] == $cat['id']) {
                            echo " selected";
                        }
                        echo ">" . $cat['title'] . '</option>';
                    }
                    ?>
                </select>
            </dd>
            <dt>    Status:</dt>
            <dd>
                <?php
                if ($article['status'] == '1') {
                    $active_checked = ' checked';
                    $inactive_checked = '';
                } else {
                    $inactive_checked = ' checked';
                    $active_checked = '';
                }
                ?>
                <input type="radio" name="status" value="1" <?php echo $active_checked; ?>/>Active    
                <input type="radio" name="status" value="0"  <?php echo $inactive_checked; ?>/>Inactive     
            </dd>
            <dt> <input type="hidden" name="id" value="<?php echo $article['id']; ?>" /></dt>
            <dd> <input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>
</form>
<a href="<?php echo \App\Transaction\Session::get_previous_admin_page(); ?>" />Cancel</a>
<?php
include_once(PHP_CKEDITOR_PATH . 'j_ckedit.class.php');
echo CKEDITOR::ckHeader();
echo CKEDITOR::ckReplaceEditor_Full('content');
