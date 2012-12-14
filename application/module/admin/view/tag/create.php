<form action="<?php echo ADMIN_HTML_ROOT . 'tag/create'; ?>" method="post">
    <fieldset>
        <legend>Create tag</legend>
        <dl>
            <dt>Name:</dt><dd><input type="text" name="name" size="50" /></dd>
            <dt> </dt><dd><input type="submit" name="submit" value="create" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
