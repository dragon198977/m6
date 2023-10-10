<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>login_form</title>
    <table class=login>
</head>
<body>
    <link rel="stylesheet" type="text/css" href="login.css">
<div class ="login_form">
<p class ="section1">ログイン</p>
    <form action="login.php" method="post">
        <input type ="text" name ="name" placeholder="名前"><br>
        <input type ="password" name ="pass" placeholder="パスワード"><br>
        <input type ="submit" name ="log_sub" value ="ログイン">
    </form>
    <p class =section2>未登録の方は<a href="create_account_form.php">こちら</a></p>
</div>
</body>
</html>