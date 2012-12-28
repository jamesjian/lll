<?php
/*question, answer, ad, 
claim
user


drop table ts8wl_ad;
drop table ts8wl_claim;
drop table ts8wl_answer;
drop table cache;
drop table ts8wl_question;
drop table ts8wl_user;
drop table ts8wl_staff;
drop table ts8wl_article;
drop table ts8wl_article_category;
drop table ts8wl_user_to_answer;
drop table ts8wl_page;
drop table ts8wl_page_category;
drop table session;




























房产
    房地产
        买房,房地产
        卖房,房地产
        出租,房地产
        求租,房地产
    室内外装修
        建房 维修,室内外装修
        花园,室内外装修
        门窗橱柜,室内外装修
        水管工,室内外装修
        电工 卫星电视,室内外装修
        空调,室内外装修
        太阳能,室内外装修
 
汽车
    汽车
        新车,汽车
        二手车,汽车
        装饰 修车,汽车
        驾驶培训,汽车
 

 
餐饮娱乐 
    餐饮
        中餐,餐饮
        西餐,餐饮
        咖啡 茶楼,餐饮
        厨房设备

    娱乐休闲运动
        卡拉OK,娱乐休闲
        碟片出售出租,娱乐休闲
        钓鱼,娱乐休闲
        高尔夫,娱乐休闲
        运动（棋牌、球类）,娱乐休闲

    交友征婚
        交友,交友征婚
        征婚,交友征婚 
 
 
服务 
    专业服务
        工业用品,专业服务
        会计 贷款保险证券,专业服务
        律师,专业服务
        印刷 平面设计 网站开发,专业服务
        搬家运输快递,专业服务
        留学移民,专业服务
        翻译,专业服务
        宠物,专业服务
        清洁,专业服务


    售票
        机票,售票
        门票,售票

    健康美容
        西医诊所,健康美容
        中医诊所,健康美容
        药店,健康美容
        牙医,健康美容
        眼镜,健康美容
        美容,健康美容
        健身,健康美容
        按摩,健康美容
        理发,健康美容
        保健品,健康美容
    教育培训
        学校,教育培训
        考试辅导与培训,教育培训
    旅游
        旅行社,旅游
        饭店旅馆,旅游
 
其它
    招聘
        厨师,招聘
        保姆,招聘        
 
活动
 展销,活动
 培训,活动
 活动召集,活动
商品
    商品
        服装,商品
        鞋帽,商品
        化妆品,商品
        日用杂货,商品
        家具,商品
        装修材料
        床上用品,商品
        成人用品,商品
        家用电器,商品
        电脑通讯,商品
        书籍
        工艺品
        工业用品
    
 */
?>
 
 <?php

define('DBNAME', 'test');
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');

$dsn = 'mysql:dbname=' . DBNAME . ';host=' . DBHOST;

$dbh = new PDO($dsn, DBUSER, DBPASS);
//$id = 2;
//$sql = "INSERT INTO session SET session_id= :id";
//$params = array(':id' => $id);
//$sth = $dbh->prepare($sql);
//$sth->execute($params);
//echo $sth->rowCount();
//var_dump($sth->errorInfo());


$handle = fopen("C:/temp/1015/2.csv", "r");
$rows = array();
while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
    $rows[] = $data;
}
//var_dump($rows);
fclose($handle);
$q = "INSERT INTO misc_professions_allianz
 (arpa, name, base_rate, excess, category) values ";
foreach ($rows as $row) {
    $arpa = trim($row[0]);
    $name = trim($row[1]);
    $base_rate = (substr(trim($row[5]), 0, 4)) / 100;
    $excess = intval($row[11]);
    $category = trim($row[12]);
    $q .= "('$arpa','$name','$base_rate','$excess','$category'),";
}
$q = substr($q, 0, -1);
//echo $q;
$dbh->exec($q);

/*

mem
1. user
register/login/forgotten password/activate/reactivate
2. company
crud (only one for each user)
3. info (at most 3 levels)
crud  use radio to choose cat, keyword use @k1@k2@k3@k4@k5@
4. article (only 2 levels)
crud
5. message
crud
front
6. home
7. l1 cat/l2 cat/l3 cat, sort on time or rank
8. detail/comment
9. article
10. tag cloud
11. filter (region, keyword)
admin
1. login
2. user: crud   status
3. category: crud (at most 3 levels)
4. info: crud
5. comment: crud
6. article: crud
7. message: crud 

    

    
    
    1. user related: register, login, logout, forgotten password, activate, resend activation link
    2. Q&A
        Q list with pagination
        one Q, answer list with pagination
        Q by one with pagination
        A by one with pagination
        Q by category
        answer question
    3.     
        
        
        
        
        
        1. create question user
        2. create question under this user id (enter user id)
        3. create answer user
        4. create answer under this question id and answer user id  (enter question id and user id)
        
        */
        
        
/*        
user:
 * status: 1: active, 2: registered, 0: inactive
questions: 
 * status: 1: active, 0: inactive 2. disalbed
 * start before XMas
 * week 1 all code
 * week 2 test
 * week 3 deploy
 * week 4 integration test, add data
 * ad/answer exchange after one year.
 * front end:
 * 1. home
 * 2. user register, activation, forgotten password, 
 * 3. question list, (newest, unanswered, most popular), 
 *    create question, 
 * mark useful, report claim
 * 4. one question and answer list, vote answer, report claim
 * 5. answer a question
 * 6. user list
 * 7. tag list
 * 8. search
 * 9. region
 * 10.ad list of one user, of one tag, of one region, of one question, 
 *    of one user and one tag
 *    and one ad
 * 
 * 
 * user end:
 * 1. user profile, change password
 * 2. question list, create question
 * 3. answer list of one question, of one ad, all answers
 * 4. ad list, crud ad, link ad to answer
 * 
 * back end:
 * 1. user list, change status
 *    num of question, answer, ad
 * 2. question list, change status, edit question(title, content and tags)
 *    examine if anonymous
 * 3. answer list, change status, edit answer(content)
 *     examine if anonymous
 * 4. ad list, edit ad
 * 
 * 5. tag list, create tag, edit tag, move tag
 * 6. region
 * 
 * 
 */
    
/*
 * 
 * 
 * 被举报后的流程：
 * 初始状态为 not sure, 
 * 1. 确认是否违规， 如果不违规， 设定状态为valid, 如果违规， 设定状态为invalid
 * 2. 如果违规， 违规信息被屏蔽， 或以极淡颜色显示部分内容
 * 3. 如果违规， 且分值为n,  首个举报者加n分， 被举报者扣n分， 举报灌水者， 举报者加1分， 被举报者不扣分
 */
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
       
    
    
    