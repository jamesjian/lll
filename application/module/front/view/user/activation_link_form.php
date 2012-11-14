<div id="main-content">
    <div id="left-side" class="grid">
        <h4 style="padding-top:10px; padding-left:15px">重发激活邮件</h4>
        <div style="clear:both; height:0;"></div>
        <?php
        if (isset($errors)) {
            echo "<div  class='errormessage'>";
            foreach ($errors as $key => $message) {
                echo $message, BR;
            }
            echo "</div>";
        }
        App_Session::display_message();
        ?>
        <form action="<?php echo HTMLROOT; ?>user/activation_link" method="post" id="forgotten_password_form">
            <div  class="font-12px">
                如果您未收到激活邮件， 请首先检查您的垃圾邮箱中是否有激活邮件，<br />
                如果没有找到， 请输入您的用户名或电子邮箱， 我们将重新发送一封激活邮件到您的电子邮箱中。 
            </div>
            <table>
                <tr>
                    <td class="table_title required">您的用户名或电子邮箱:</td>
                    <td class="table_input">
                        <input type="text" name="name" size="50" value="<?php if (isset($posted['name']))
            echo $posted['name'];
        ?>"  />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type='submit' name='submit' value="submit">发 送</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>


