<form action="<?php echo ADMIN_HTML_ROOT . 'body/update'; ?>" method="post">
    <fieldset>
        <legend>Create body</legend>
        <dl>
            <dt>En:</dt>
            <dd><input type="text" name="en" size="50" 
                        value="<?php if (isset($_POST['en'])) echo $_POST['en']; else echo $body['en'];?>"/>
            </dd>
            <dt>Cn:</dt>
            <dd><input type="text" name="cn" size="50" 
                        value="<?php if (isset($_POST['cn'])) echo $_POST['cn']; else echo $body['cn'];?>"/>
            </dd>
            <dt>Cid:</dt>
            <dd><input type="text" name="cid" size="50" 
                        value="<?php if (isset($_POST['cid'])) echo $_POST['cid']; else echo $body['cid'];?>"/>
            </dd>
            <dt><input type="hidden" name="id" value="<?php echo $body['id'];?>"  </dt>
            <dd><input type="submit" name="submit" value="update" /></dd>
        </dl>
    </fieldset>    
</form>
<a href="<?php echo \App\Transaction\Html::get_previous_admin_page(); ?>" />Cancel</a>