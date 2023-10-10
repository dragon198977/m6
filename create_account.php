<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
    <meta charset="UTF-8">
    <title>create_account</title>
</head>
<body>
    <?php
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

    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $pass = $_POST["pass"];
    
    $sql = "SELECT * FROM techbase WHERE mail = :mail";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':mail', $mail);
    $stmt->execute();
    $user = $stmt->fetch();

    if(isset($name) && !empty($name) && isset($mail) && !empty($mail) && isset($pass) && !empty($pass)){
        
        if($user["mail"] == $mail && isset($mail)){
        echo "このメールアドレスは既に登録済みです。<hr> <a href='create_account_form.php'>戻る</a>";
        }else{
            $sql = "INSERT INTO techbase(name, mail, password) VALUES (:name, :mail, :pass)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':mail', $mail);
            $stmt->bindValue(':pass', $pass);
            $stmt->execute();
            echo "会員登録が完了しました <hr> <a href='login_form.php'>ログインページ</a>";
        }
    }else{
        echo "名前、メールアドレス、パスワードの三つ全てを入力して下さい。<hr> <a href='create_account_form.php'>戻る</a>";
    }
    ?>
</body>
</html>