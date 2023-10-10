<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
    <meta charset="UTF-8">
    <title>login</title>
</head>
<body>
    <?php
    session_start();
    
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS techbase"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "password VARCHAR(255),"
    . "mail VARCHAR(255),"
    . "syukkin VARCHAR(255)"
    . ");";
    $stmt = $pdo->query($sql);
    
    $name =$_POST["name"]; 
    $pass =$_POST["pass"];
    $mail =$_SESSION["mail"];
    
    $sql = "SELECT * FROM techbase WHERE name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->execute();
    $user = $stmt->fetch();
    if (!empty($name) && !empty($pass) && $name ==$user["name"] && $pass ==$user["password"]){
        $_SESSION["name"] = $user["name"];
        $_SESSION["password"] = $user["password"];
        $_SESSION["mail"] = $user["mail"];
        
        header("Location: https://tech-base.net/tb-250323/mission6/m6/calendar.php");
        exit();
    }else{
        echo "ユーザー名、またはパスワードが正しくありません<br> <p><a href='login_form.php'>ログイン画面に戻る</a></p>";
    }
    ?>
</body>
</html>