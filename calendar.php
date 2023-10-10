<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
    <meta charset="UTF-8">
    <title>calendar</title>
    <link rel="stylesheet" type="text/css" href="calendar.css">
     <style>
        html, body {
            background-color: #3cb371;
            display: flex;
        }
    </style>
<body>
<?php
//ログイン情報のセッションを使用
session_start();
//タイムゾーンの設定
date_default_timezone_set("Asia/Tokyo");
//閲覧時の年月を取得
$current_year =date("Y");
$current_month =date("m");
//一つ先の月を設定
$year =$current_year;
$month =$current_month +1;
//年を越す場合
if($month >12){
    $year++;
    $month =1;
}

//曜日
$week =["日","月","火","水","木","金","土"];

//月末日　（20xx-xx-xx)
$last_day = date('t', strtotime($year. "-". sprintf("%02d", $month). "-01"));
//初日の曜日
$first_week =date('w', strtotime($year. "-". sprintf("%02d", $month). "-01"));
//月末日の曜日
$last_week =date('w', strtotime($year. "-". sprintf("%02d", $month). "-". $last_day));
//カレンダーの箱
$calendar =[];
//一週目を指定
$j =0;


//1日目まで空白の追加をループ
for($i =0; $i <$first_week; $i++){
    // [$j]行目の[]に空白を追加
    $calendar[$j][] ="";
}
//1日目から月末日まで1日づつループ
for($i =1; $i <=$last_day; $i++){
    //次の週へ改行、行に要素が７つある場合
    if(isset($calendar[$j]) &&count($calendar[$j]) ==7){
        //行がひとつ増える
        $j++;
    }
    
    //日付を入れる
    $calendar[$j][] =$i;
}
//月末日から空白の追加をループ、行に要素が７つになるまで繰り返す
for($i =count($calendar[$j]);$i <7; $i++){
    // [$j]行目の[]に空白を追加
    $calendar[$j][] ="";
}
?>
<!--カレンダー表示部分-->
<table class=calendar>
    
    <!--年月の表示-->
    <tr>
        <th colspan ="7">
            <?php echo $year. "年". $month. "月";?>
        </th>
    </tr>
    <!-- 曜日の表示-->
    <tr>
        <?php foreach($week as $the_week){?>
            <th>
                <?php echo $the_week ?>
            </th>
  <?php } ?>
    </tr>
    <!--日数の表示、カレンダーから一行ずつ取得-->
    <?php foreach($calendar as $seven_days){?>
    <tr><!--一行から一日ずつ取得-->
        <?php foreach($seven_days as $one_day){?>
            <td>
                <?php echo $one_day;
                if($one_day !=""){
                    //格納された一日を元に曜日を計算
                    $calcu_week = date('w', strtotime($year . '-' . sprintf("%02d", $month) . '-' . $one_day));
                    $day_week = $week[$calcu_week];?>
                    <form action ="" method ="POST">
                        <input type ="hidden" name ="month_day" value ="<?php echo $month."月".$one_day."日"."(".$day_week.")"; ?>">
                        <input type ="time" name ="start_time" min ="10:00" max ="22:00">
                        <input type ="time" name ="finish_time" min ="17:00" max ="22:00">
                        <p><input type ="submit" name ="teisyutu"></p>
                    </form>
          <?php }?>
            </td>
        <?php }?>
    </tr>
    <?php } ?>
</table>    

<?php
//取得した時間を要素に変換
if(isset($_POST["month_day"]) && isset($_POST["start_time"]) && isset($_POST["finish_time"])){
    $M_D =$_POST["month_day"];
    $s_time =$_POST["start_time"];
    $f_time =$_POST["finish_time"];
    $sihuto =$M_D.$s_time."~".$f_time;
//データベースに、日付、時間の情報を格納
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "INSERT INTO techbase(name, password, mail, syukkin) VALUES (:name, :password, :mail, :syukkin)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $_SESSION["name"]);
        $stmt->bindValue(':password', $_SESSION["password"]);
        $stmt->bindValue(':mail', $_SESSION["mail"]);
        $stmt->bindValue(':syukkin', $sihuto);
        $stmt->execute(); 
}
?>
<!--日時の表示の処理-->
<table class ="kakunin">
    <form action ="" method ="POST">
    <?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $mail =$_SESSION["mail"]; //ユーザー別に処理
    $sql = 'SELECT * FROM techbase WHERE mail =:mail';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mail', $mail);
    $stmt->execute(); 
    $results = $stmt->fetchAll();
    $all_syukkin ="";
    foreach ($results as $row){
        $result =$row["syukkin"];
        $all_syukkin .=$result."\n"; //連結代入演算子で全ての日時を要素に入れる
    }
 ?>
    <textarea name ="syukkin"> <?php echo $all_syukkin; ?> </textarea>
    <input type ="submit" name ="del_sub" value ="リセット(2回押す）">
</form>
<!--リセット処理-->
<?php
if(isset($_POST["del_sub"])){
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $mail =$_SESSION["mail"]; //ユーザー別に処理
    $sql = 'delete from techbase WHERE syukkin IS NOT NULL AND mail =:mail';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mail', $mail);
    $stmt->execute();
}
?>

</body>
</html>
