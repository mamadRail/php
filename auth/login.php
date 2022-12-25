<?php
include '../models/db.php';
include '../models/validate.php';
include '../models/errorMassage.php';
include '../models/checkUser.php';
// if (!empty($_SESSION)) {
//     // header("Location: /auth/register.php");
// }
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
        $checkU = new CheckUser();
        $user = [
            'username' => $_POST['username']
        ];
        if ($checkU->check($_POST['username'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $getData = new DB('users');
            $storageData = $getData->get('username', $user);
            $checked = $checkU->CheckLoged(array_shift($storageData), $username, $password);
            if ($checked) {
                $_SESSION = ['username' => $_POST['username']];
                var_dump($_SESSION);
                $errors->set('sucsess', 'شما با موفقیت وارد شدید');
            } else {
                $errors->set('wrongData', 'نام کاربری یا رمز عبور اشتباه است');
            }
        } else {
            $errors->set('wrongData', 'نام کاربری یا رمز عبور اشتباه است');
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
    <title>ورود</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="register-page">
    <div class="form-container">
        <h1>ورود</h1>
        <?php if ($_SERVER['REQUEST_METHOD'] == "POST") : ?>
            <?php if ($errors->has('sucsess')) : ?>
                <div class='sucsess'>
                    <span><?= $errors->get('sucsess'); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($_SERVER['REQUEST_METHOD'] == "POST") : ?>
            <?php if ($errors->has('wrongData')) : ?>
                <div class='wrongData'>
                    <span><?= $errors->get('wrongData'); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="/auth/login.php" method="post">
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
            <button type="submit">ورود</button>
        </form>
    </div>
</body>

</html>