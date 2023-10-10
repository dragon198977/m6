<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>create_account_form</title>
    <table class=login>
</head>
<body>
    <link rel="stylesheet" type="text/css" href="login.css">
<div class ="login_form">
<p class ="section1">新規登録</p>
    <form action="create_account.php" method="post">
        <input type ="text" name ="name" placeholder="名前"><br>
        <input type ="text" name ="mail" placeholder="メールアドレス"><br>
        <input type ="password" name ="pass" placeholder="パスワード"><br>
        <input type ="submit" name ="log_sub" value ="新規登録">
    </form>
    <p class =section2>登録済みの方は<a href="login_form.php">こちら</a></p>
</div>
</body>
</html>