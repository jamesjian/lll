您可以直接发送邮件到 <?php echo ENQUIRY_EMAIL_USER; ?>或填妥以下表格后提交给我们， 我们会在24小时以内和您联系。 


<form  class="zx-front-contact-us-form" action="<?php echo HTML_ROOT . ''; ?>" method="POST">
    <fieldset>
        <table>
            <legend>联系我们</legend>
            <tr>
                <td class="zx-front-table-title zx-front-required">您的电子邮箱:</td>
                <td><input type="text" name="email" /></td>
            </tr>
            <tr>
                <td class="zx-front-table-title">您的姓名:</td>
                <td><input type="text" name="name" /></td>
            </tr>
            <tr>
                <td class="zx-front-table-title zx-front-required">您关心的问题：</td>
                <td><textarea name="content" cols="30" rows="10"></textarea></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="发送" /></td>
                <td><input type="reset" name="reset" value="清空" /></td>
            </tr>
        </table>
    </fieldset>
</form>
<script type="text/javascript" src="<?php echo HTML_ROOT . 'js/jquery/jquery.validate.js'; ?>"></script>
