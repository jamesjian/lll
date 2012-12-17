<form action="<?php echo ADMIN_HTML_ROOT . 'tag/transfer'; ?>" method="post">
    <fieldset>
        <legend>Transfer tag</legend>
        <dl>
            <dt>source (only one):</dt><dd><input type="text" name="tsource" size="50" value="<?php if (isset($posted['tsource'])) echo $posted['tsource'];?>"/></dd>
            <dt>destination(separated by <?php echo TNAME_SEPERATOR;?>):</dt><dd><input type="text" name="tdest" size="50"  value="<?php if (isset($posted['tdest'])) echo $posted['tdest'];?>"/></dd>
            <dt> </dt><dd><input type="submit" name="submit" value="transfer" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>
