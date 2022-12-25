<?php
include './models/Permission.php';
include './models/validate.php';
include './models/errorMassage.php';
include './models/checkUser.php';
$adminAccsess = new admin('users');
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $userDelete = [
        'id' => $_GET['id']
    ];
    $adminAccsess->deleteUser($userDelete);
    header("Location: ./admin.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['Nroule'])) {
    $id = $_GET['id'];
    $checkData = new ValidateRegisrer();
    $errors = new ErrorMassage();
    $newUser = $checkData->Recive_get($_GET['Nusername']);
    $newPass = $checkData->Recive_get($_GET['Npassword']);
    $newRoule = $_GET['Nroule'];
    if (!$newUser) {
        $errors->set('newusername', 'لطفا نام کاربری خود را وارد کنید');
    }
    if (!$newPass) {
        $errors->set('newpassword', 'لطفا رمزعبور خود را وارد کنید');
    }
    if ($newPass && $newUser) {
        //check username is alredy exists or not
        $checkusername = new CheckUser();
        $result = $checkusername->check($newUser);
        if ($result) {
            $errors->set('usernameExists', 'نام کاربری از قبل وجود دارد');
        } elseif (!$result) {
            $userNewData = [
                'username' => $newUser,
                'password' =>  $newPass,
                'roule' => $newRoule,
                'id' => $id

            ];
            $adminAccsess->updateUser($userNewData);
            header("Location: ./admin.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>page</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <div class="continer">
        <div class="users">
            <?php
            $adminAccsess->getUsers();
            ?>
        </div>
        <div class="edit">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['Nroule'])) {
                if ($errors->has('usernameExists')) { ?>
                    <span class='showError'><?php echo $errors->get('usernameExists'); ?></span>
                <?php } ?>
                <br>
                <?php
                if ($errors->has('newpassword')) { ?>
                    <span class='showError'><?php echo $errors->get('newpassword'); ?></span>
                    <?php
                    if ($errors->has('newusername')) { ?>
                        <span class='showError'><?php echo $errors->get('newusername'); ?></span>
            <?php }
                }
            }
            ?>
            <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'edit') : ?>
                <form action="./admin.php" method="GET">
                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                    <label for="Nusername">نام کاربری</label>
                    <input type="text" name="Nusername" id="Nusername">
                    <label for="Npassword">رمز عبور</label>
                    <input type="password" name="Npassword" id="Nusername">
                    <label for="Nroule">نقش</label>
                    <select name="Nroule" id="Nroule">
                        <option value="user">دانشجو</option>
                        <option value="admin">مدیر</option>
                        <option value="ostad">استاد</option>
                    </select>
                    <button type="submit">ذخیره</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>