HTML_ROOT = '/lll/';
site = {
    
    test: function(){
        var url = '/z2/public/admin/article/show';
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: 111
            },
            dataType:  'html',
            success: function(data){
                // index.open_action_dialog(data,title)
                $('#test_div').html(data);
            }
        });

    },
    hover_submenu: function(){
        var submenu = $(this).children('ul').first();
        if (submenu.length>0) {
            if (submenu.css('display') == 'none') {
                submenu.css({
                    display: 'block', 
                    visibility: 'visible'
                });
            } else {
                submenu.css({
                    display: 'none', 
                    visibility: 'hidden'
                });
            }
        }
    },
    refresh_vcode: function(){

        $('#vcode_img').attr('src', HTML_ROOT+'front/user/vcode/' + (new Date().getTime()));
        return false;
    },
    check_account: function(){
        var user_name = $.trim($('input[name="user_name"]').val());
        var email = $.trim($('input[name="email"]').val());
        if (user_name != ''  && email !='') {
            url = $(this).attr('href');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    user_name:user_name, email: email
                },
                dataType:  'json',
                success: function(data){
                    $('#account_message').text(data.message);
                }
            });   
        } else {
            alert('用户名和邮箱不能为空');
        }
        return false;
    },    
    bind_events: function(){
        site.unbind_events();  
        $('#test').click(site.test);
        $('ul.mainmenu>li').hover(site.hover_submenu);
        $('#refresh_vcode').click(site.refresh_vcode);
        $('#check_account').click(site.check_account);          
    },
    unbind_events: function(){
        
    },
    init: function(){
        //console.log('aaa');
        site.bind_events();
    }
}

$(document).ready(site.init);
