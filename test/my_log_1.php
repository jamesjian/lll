question, answer, ad, 
abuse
user


drop table ts8wl_ad;
drop table ts8wl_abuse;
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
博文
    特色产品和服务
    公司介绍
    经验推广
    
主菜单采用猪八戒风格8个（房产， 汽车， 餐饮娱乐， 服务， 活动， 商品， 博文， 其它），
首页（15个模块， 可以长短不同， 但每行并排的两个长度必须相同， 平均5条至10条记录， 列出前5名子类）， 内文采用论坛风格
只有一种用户， 每个用户最多可以创建一个公司， 也可以不创建
 帖子包括“是用户帖子还是是公司帖子”的属性， 如二手车可以私人卖也可以公司卖， 有点击量信息
 必须提供回帖功能
 只有8个州的信息， city\suburb\postcode\address由用户填入
 前端提供过滤功能， 过滤州和时间
 举报链接
 查询关键词由用户提供， 显示tag cloud
 提供排序链接(时间和点击量）
 
商业经验÷产品介绍÷公司宣传（软文）
商业经验
 产品介绍
 公司宣传
 
 
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

    
    我可以问哪些问题？
    huarendian网站是为广大华人了解澳洲而设立的。 我们欢迎读者提出与澳洲华人的日常生活有关的问题。 
    以下类型的问题欢迎提问：
    1. 只与澳洲有关的问题。比如“陆克文先生的夫人叫什么名字？”可以在这里提问， 而“奥巴马总统的夫人叫什么名字？” 不适宜在这里提问
    2. 不包含任何色情\暴力\种族歧视\宗教歧视\诽谤或侮辱他人的问题
    3. 不包含新闻， 聊天， 灌水， 评论等内容的问题， 比如“carbon tax好不好？”不适宜在这里提问
    4. 具有时间持久性的问题， 比如“Ashfield区有哪些华人超市？”适宜在这里提问， 而““今天ASHFIELD青菜多少钱一公斤？” 不适宜在这里提问
    5. 具体明确而不是真伪无法确定的问题， 比如“澳洲买自住房的步骤有哪些？”适宜在这里提问， 而“大家怎么看明年的澳洲房产市场？”不适宜在这里提问
    6. 非专业性过强的问题， 比如“澳洲哪种编程语言用得较普遍？”适宜在这里提问， 而“怎样制作一个网站？”不适宜在这里提问
    7. 对公司或个人进行评价的问题， 比如“墨尔本有哪些中餐馆？”适宜在这里提问， 而“墨尔本某某饭店怎么样？”不适宜在这里提问
    宣扬色情\暴力\种族歧视\宗教歧视\诽谤或侮辱他人的问题一经发现会被立刻删除， 其他不适宜提问的问题将被管理员关闭， 不进入问题列表， 也不接受任何回答。
    
    我需要注册和登录才能使用本网站吗？
    提问题和回答问题都不需要用户注册或登录。 
    
    注册用户有什么好处？
    1. 注册用户可以获得积分， 并可以进行积分交易。
    2. 注册用户可以举报违规问题和回答。 
    3. 注册用户可以发布广告信息。
    
    使用条款：
    1. 本网站中的所有问题、答案和广告信息由网站用户提供， 但本网站对所有内容的真实和准确性不负法律责任
    2. 本网站有权根据自己的判断修改， 删除用户提交的任何信息。原始信息有用户自己保存， 本网站不负责保存用户提交的任何原始信息。 
    
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
 * mark useful, report abuse
 * 4. one question and answer list, vote answer, report abuse
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
    

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        


　　　　　　　　　　　　　　　　　　
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
       
    
    
    