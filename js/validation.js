/* 
 * validation.js for all the form
 */

vali= {

    check_caps_on:function(e){
        var s = String.fromCharCode( e.which );
        if ( s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey ) {
            $('#caps_on').css({
                visibility: 'visible'
            });
        } else {
            $('#caps_on').css({
                visibility: 'hidden'
            });
        }
    },    
validate_create_form : function() {
        // console.log('start');
        var cat2_ids = $.trim($('input#cat2_ids').val());
        var suburb_id = $.trim($('input#suburb_id').val());
        var city_id = $.trim($('input#city_id').val());
        var error_str = '';
        var success = true;
        if (title == '') {
            error_str = error_str + '请输入标题' + "\n";
            success = false;
        }
        if (keyword == '') {
            error_str = error_str + '请输入关键字， 便于搜索' + "\n";
            success = false;
        }
        if (cat2_ids == '') {
            error_str = error_str + '请选择类别' + "\n";
            success = false;
        }
        
        if (suburb_id == '') {
            error_str = error_str + '请输入所在区(suburb)， 然后务必在下拉框中选择你所在的区' + "\n";
            success = false;
        }
        if (city_id == '') {
            error_str = error_str + '请输入所在区(suburb)， 然后务必在下拉框中选择你所在的城市' + "\n";
            success = false;
        }
        if (!success) {
            alert(error_str);
        } else {
            $("form#create_thread_form").submit();
        }
        return false;
    },    
    all_validation: function(){
        if ($('#register_form').length > 0) {
            $('#register_form').validate({
                rules: {
                    user_name: {
                        required: true
                    },
                    password1: {
                        required: true
                    },
                    password2: {
                        required: true,
                        equalTo: '#password1'
                    },
                    email: {
                        required:true,
                        email: true
                    },
                    vcode:{
                        required: true
                    }
                },
                messages: {
                    user_name: "请输入用户名",
                    password1: "请输入密码",
                    password2:{ 
                        required: "请确认密码",
                        equalTo: "请输入相同的密码"
                    },
                    email: {
                        required: "请输入电子邮箱",
                        email:   "请输入有效的电子邮箱"
                    },
                    vcode: "请输入验证码"
                }
            });
        }
        if ($('#contactus_form').length > 0) {
            $('#contactus_form').validate({
                rules: {
                    sender_name: {
                        required: true
                    },
                    sender_email: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                },
                messages: {
                    sender_name: "请输入您的姓名",
                    sender_email: "请至少提供一个电子邮箱或一个电话号码",
                    title: "请输入标题",
                    description: "请输入详细内容"
                }
            });
        }
        if ($('#login_form').length > 0) {
            $('#login_form').validate({
                rules: {
                    user_name: {
                        required: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    user_name: "请输入用户名",
                    password: "请输入密码"
                }
            });
        }
        if ($('#update_basic_form').length > 0) {
            $('#update_basic_form').validate({
                rules: {
                },
                messages: {
            }
            });
        }
        if ($('#update_email_form').length > 0) {
            $('#update_email_form').validate({
                rules: {
                    email: {
                        required:true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "请输入电子邮箱",
                        email:   "请输入有效的电子邮箱"
                    }
                }
            });
        }
        if ($('#update_password_form').length > 0) {
            $('#update_password_form').validate({
                rules: {
                    old_password: {
                        required: true
                    },
                    password1: {
                        required: true
                    },
                    password2: {
                        required: true,
                        equalTo: '#password1'
                    }
                },
                messages: {
                    old_password: "请输入现在使用的密码",
                    password1: "请输入密码",
                    password2:{ 
                        required: "请确认密码",
                        equalTo: "请输入相同的密码"
                    }
                }
            });
        }
        if ($('#forgotten_password_form').length > 0) {
            $('#forgotten_password_form').validate({
                rules: {
                    email: {
                        required: true
                    }
                },
                messages: {
                    email: "请输入用户名或电子邮箱"
                }
            });     
        }
        if ($('#blog_form').length > 0) {
            $('#blog_form').validate({
                rules: {
                    title: {
                        required: true
                    },
                    'abstract': {
                        required: true
                    },
                    description: {
                        required: true
                    }
                },
                messages: {
                    title: "请输入标题",
                    'abstract': "请输入内容提要",
                    description: "请输入详细内容"
                }
            });     
        }
        if ($('#new_thread_form').length > 0) {
            $('#new_thread_form').validate({
                rules: {
                    contact: {
                        required: true
                    },
                    postcode: {
                        required: true
                    },
                    email: {
                        required:true,
                        email: true
                    },
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                },
                messages: {
                   contact: '请输入联系人',
                   postcode: '请输入邮政编码',
                   email: {
                        required: "请输入电子邮箱",
                        email:   "请输入有效的电子邮箱"
                    },
                    title: "请输入标题",
                    description: "请输入详细内容"
                }
            });     
        }
    },
    submit_login_form: function() {
        $('form#login_form').submit();
        return false;
    },
    bind_events: function() {
        vali.unbind_events();
        
        if ($('#login_form').length > 0) {
            $('#login_form #password').keypress(vali.check_caps_on);
            $('#login_form #password').bind('change', vali.submit_login_form);//when enter password, then "enter", it will submit login form
        }
        vali.all_validation();
    },
    unbind_events: function() {
    },
    
    init: function(){
        vali.bind_events();
    }
};
$(document).ready(vali.init)


