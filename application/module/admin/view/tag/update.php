<form action="<?php echo ADMIN_HTML_ROOT . 'tag/update'; ?>" method="post">
    <fieldset>
        <legend>Create tag</legend>
        <dl>
            <dt>Name:</dt>
            <dd><input type="text" name="name" size="50" 
                        value="<?php if (isset($_POST['name'])) echo $_POST['name']; else echo $tag['name'];?>"/>
            </dd>
            <dt><input type="hidden" name="id" value="<?php echo $tag['id'];?>"  </dt>
            <dd><input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
