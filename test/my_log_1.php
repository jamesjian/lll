drop table article;
drop table article_category;
drop table page;
drop table page_category;
drop table staff;
drop table session;
drop table cache;


房地产
 买房,房地产
 卖房,房地产
 出租,房地产
 求租,房地产
 建房 维修,房地产
 装修,房地产
 花园,房地产
 门窗橱柜,房地产
 水管工,房地产
 电工 卫星电视,房地产
 空调,房地产
 太阳能,房地产
 汽车
 新车,汽车
 二手车,汽车
 修车,汽车
 驾驶培训,汽车
 招聘
 厨师,招聘
 保姆,招聘
 
 餐饮
 中餐,餐饮
 西餐,餐饮
 咖啡馆 茶楼,餐饮
 厨房设备
 
 娱乐休闲
 卡拉OK,娱乐休闲
 碟片出售出租,娱乐休闲
 钓鱼,娱乐休闲
 高尔夫,娱乐休闲
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
 交友征婚
 交友,交友征婚
 征婚,交友征婚
 健康美容
 西医诊所,健康美容
 中医诊所,健康美容
 药店,健康美容
 牙医,健康美容
 眼镜,健康美容
 美容,健康美容
 健身,健康美容
 按摩
 理发,健康美容
 保健品,健康美容
 教育培训
 学校,教育培训
 考试辅导与培训,教育培训
 旅游
 旅行社,旅游
 饭店旅馆,旅游
 活动
 展销,活动
 培训,活动
 活动召集,活动
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

只有一种用户， 每个用户最多可以创建一个公司， 也可以不创建
 帖子包括“是用户帖子还是是公司帖子”的属性， 如二手车可以私人卖也可以公司卖， 有点击量信息
 * 提供回帖功能
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
