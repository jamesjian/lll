    <?php
    $php_image_dir = PHPUPLOADROOT . 'user/';  //there is year info in image file name in database
    $html_image_dir = HTMLUPLOADROOT . 'user/'; //there is year info in image file name in database
    ?>
    <div class="my_account_right">
        <div class="my_account_head">
            <span class="my_account_title_left">更新用户头像</span>
        </div>
        <div class="my_account_body">
            <form  enctype='multipart/form-data' action="<?php echo MEMHTMLROOT; ?>user/change_portrait" method="post" id="user-form">
                <fieldset>
                    <table>
                        <tr>
                            <td class="title">我现在的头像:</td>
                            <td>
                                <?php
                                if (!empty($user->image) AND file_exists($php_image_dir . $user->image)) {
                                    ?>
                                    <img id="image" src="<?php echo $html_image_dir . $user->image; ?>"/>
                                    <?php
                                } else {
                                    ?>  
                                    <img id="image" src="<?php echo $html_image_dir . 'default_user_portrait.jpg'; ?>"  title="风云list默认头像"/>

                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <tr><td class="title">我的新头像:</td>
                            <td>   <input type="file" name="image" />  </td>
                        </tr>
                        <tr>
                            <td>
                                <input type='hidden' name='sess' value="<?php echo $sess; ?>" />
                            </td>
                            <td>
                                <button type='submit' name='submit' value="submit">提 交</button>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </form>
        </div>
    </div>
    <!--End Right Side1-->

