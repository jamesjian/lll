    <div class="my_account_right">
        <div class="my_account_head">
            <span class="my_account_title_left">更新帐户</span>
        </div>
        <div class="my_account_body">
            <table class="update_profile">
                <tr>
                    <td>更新基本信息</td>
                    <td></td>
                </tr> 
            </table>
            <form action="<?php echo MEMHTMLROOT; ?>user/change_profile" method="post" name="update_basic_form" id="update_basic_form">
                <table class="update_profile">
                    <tr>
                        <td><span class="title">First Name(名）:</span>
                            <input type="text" name="first_name" value="<?php echo $user->first_name; ?>" />
                        </td>
                        <td><span class="title">Last Name(姓):</span>
                            <input type="text" name="last_name" value="<?php echo $user->last_name; ?>"  />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2"><span class="title">省/市/区:</span>
                            <select name="state" id="state">
                                <?php
                                foreach ($state_list as $value => $state) {
                                    echo "<option value='$value'";
                                    if ($user->state == $value) {
                                        echo " selected ";
                                    }
                                    echo ">$state</option>";
                                }
                                ?>  
                            </select>
                            <select name='city' id="city">
                                <?php
                                foreach ($city_list as $value => $city) {
                                    echo "<option value='$value'";
                                    if ($user->city_id == $value) {
                                        echo " selected";
                                    }
                                    echo ">$city</option>";
                                }
                                ?>                    
                            </select>     
                            <select name='suburb' id="suburb">
                                <?php
                                foreach ($suburb_list as $suburb) {
                                    echo "<option value='{$suburb->id}'";
                                    if ($user->suburb_id == $suburb->id) {
                                        echo " selected";
                                    }
                                    echo ">{$suburb->region_name_en} ( {$suburb->postcode} )</option>";
                                }
                                ?>                    
                            </select>              
                        </td>
                    </tr>
                    <tr>
                        <td><span class="title">电话号码:</span>
                            <input type="text" name="phone" value="<?php echo $user->phone; ?>"  id="phone"/>
                        </td>
                        <td>
                            <input type='hidden' name='sess' value="<?php echo $sess; ?>" />
                            <button type='submit' name='submit' value="submit">提 交</button>
                        </td>
                    </tr>
                </table>
            </form>
            <table class="update_profile">
                <tr>
                    <td colspan="2"><div class="seperator"></div></td>
                </tr>
            </table>
            <form action="<?php echo MEMHTMLROOT; ?>user/change_email" method="post" name="update_email_form" id="update_email_form">
                <table class="update_profile">
                    <tr>
                        <td colspan="2">更新邮箱地址(您的账户将通过新的邮箱地址重新激活后， 才能再次登录）</td>
                    </tr>
                    <tr>
                        <td colspan="2">您目前的邮箱是：<?php echo $user->email; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="title left_element">新的邮箱地址:</div>
                            <div><input type="text" name="email" id="email" /></div>
                        </td>
                        <td>
                            <input type='hidden' name='sess' value="<?php echo $sess; ?>" />
                            <button type='submit' name='submit' value="submit">提 交</button>
                        </td>
                    </tr>
                </table>
            </form>
            <table class="update_profile">  
                <tr>
                    <td colspan="2"><div class="seperator"></div></td>
                </tr>
            </table>
            <form action="<?php echo MEMHTMLROOT; ?>user/change_password" method="post" name="update_password_form" id="update_password_form">      
                <table class="update_profile">
                    <tr>
                        <td>
                            <span class="title">请输入您现在的密码：</span>
                        </td>
                        <td>
                            <input type="password" name="old_password" value="" id="old_password"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="title">请输入您新的账户密码：</span>
                        </td>
                        <td>
                            <input type="password" name="password1" value="" id="password1"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="title">请确认您的新密码：</span>
                        </td>
                        <td>
                            <input type="password" name="password2" value="" size="20" id="password2"/>
                            <input type='hidden' name='sess' value="<?php echo $sess; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>       
                            <div class="form_dbl_bt"><?php App_Http::back_link('返回'); ?></div>
                        </td>
                        <td>
                            <button type='submit' name='submit' value="submit">提 交</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
