<?php
include '../models/db.php';
include '../models/validate.php';
include '../models/errorMassage.php';
include '../models/checkUser.php';
if (!empty($_SESSION)) {
    // header("Location: /auth/register.php");
    // die;
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $validate = new ValidateRegisrer();
    $errors = new ErrorMassage();
    if ($validate->Recive_post() == false) {
        if ($_POST['username']  == '' || (ctype_space($_POST['username']))) {
            $errors->set('username', 'لطفا نام کاربری خود را وارد کنید');
        }

        if ($_POST['password']  == '' || (ctype_space($_POST['password']))) {
            $errors->set('password', 'لطفا رمز عبور خود را وارد کنید');
        }
    } else {
        $checkUsername = new CheckUser();
        $checkUsername->check($_POST['username']);
        if (!$checkUsername->check($_POST['username'])) {
            $createUser = new DB('users');
            $createUser->create($validate->Recive_post());
            session_start();
            $_SESSION = ['username' => $validate->Recive_post()['username']];
        } else {
            $errors->set('newUser', 'نام کاربری از قبل وجود دارد لطفا نام کاربری جدیدی وارد کنید');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="register-page">
    <div class="form-container">
        <h1>ثبت نام</h1>
        <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $validate->Recive_post() == true) : ?>
            <?php if ($errors->has('newUser')) : ?>
                <div class='newUser'>
                    <span><?= $errors->get('newUser'); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="/auth/register.php" method="post">
            <label for="username">نام کاربری</label>
            <input type="text" name="username" id="username">
            <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $validate->Recive_post() == false) : ?>
                <?php if ($errors->has('username')) : ?>
                    <span><?= $errors->get('username'); ?></span>
                <?php endif; ?>
            <?php endif; ?>
            <label for="password">رمز عبور</label>
            <input type="password" name="password" id="password">

            <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && $validate->Recive_post() == false) : ?>
                <?php if ($errors->has('password')) : ?>
                    <span><?= $errors->get('password'); ?></span>
                <?php endif; ?>
            <?php endif; ?>
            <button type="submit">ثبت نام</button>
        </form>
    </div>
</body>

</html>