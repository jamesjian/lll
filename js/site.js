
var hostname = window.location.hostname;

if (hostname == 'www.huarendian.com' || hostname =='huarendian.com') {
    HTML_ROOT = 'http://'+hostname+'/lll/';
} else {
    HTML_ROOT = '/lll/';
}
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
    get_vote_dialog : function(e) {
        // m=m+1; //for checking bind/unbind
        // console.log('get dialog'+m);
        var url = $(this).attr('href');
        // console.log(url);
        var title = e.data.title;
        $.ajax({
            type : "POST",
            url : url,
            data : {},
            dataType : 'html',
            success : function(data) {
                site.open_message_dialog(data, title)
            }
        });
        return false;
    },
    /*
         **/
    open_vote_dialog : function(data, title) {
        // m=m+1; //for checking bind/unbind
        // console.log('open dialog'+m);
        $('#dialog').dialog({
            title : title
        });
        $('#dialog').html(data);
        $('#dialog').dialog('open');
        //$('#region_select_state').bind('change', region.region_change_state);
        return false;
    },    
    get_abuse_dialog : function(e) {
        // m=m+1; //for checking bind/unbind
        // console.log('get dialog'+m);
        var url = $(this).attr('href');
        // console.log(url);
        var title = e.data.title;
        $.ajax({
            type : "POST",
            url : url,
            data : {},
            dataType : 'html',
            success : function(data) {
                site.open_message_dialog(data, title)
            }
        });
        return false;
    },
    /*
         **/
    open_abuse_dialog : function(data, title) {
        // m=m+1; //for checking bind/unbind
        // console.log('open dialog'+m);
        $('#dialog').dialog({
            title : title
        });
        $('#dialog').html(data);
        $('#dialog').dialog('open');
        //$('#region_select_state').bind('change', region.region_change_state);
        return false;
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
                    user_name:user_name, 
                    email: email
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
        $('.zx-front-vote-link').bind('click', {
            title : '值得关注'
        }, message.get_vote_dialog);        
        $('.zx-front-abuse-link').bind('click', {
            title : '举报'
        }, message.get_abuse_dialog);                  
    },
    unbind_events: function(){
        
    },
    init: function(){
        //console.log('aaa');
        site.bind_events();
    }
}

$(document).ready(site.init);
