<form action="<?php echo ADMIN_HTML_ROOT . 'body/create'; ?>" method="post">
    <fieldset>
        <legend>Create tag</legend>
        <dl>
            <dt>En:</dt><dd><input type="text" name="en" size="50" /></dd>
            <dt>Cn:</dt><dd><input type="text" name="cn" size="50" /></dd>
            <dt>Cid:</dt><dd><input type="text" name="cid" size="50" /></dd>
            <dt> </dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
