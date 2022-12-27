<?php
include './models/Permission.php';
include './models/validate.php';
include './models/errorMassage.php';
include './models/checkUser.php';
$adminAccsess = new admin('users');
$checkData = new ValidateRegisrer();
$errors = new ErrorMassage();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $checkbox_Professor = isset($_POST['checkbox_Professor']) && ($_POST['checkbox_Professor'] != '') ? $_POST['checkbox_Professor'] : null;
    $course = isset($_POST['course']) && ($_POST['course'] != '') ? $_POST['course'] : null;
    $courseArray = [
        'course_text' => $course
    ];
    $create = new saveCourse('course');
    $create->createCourse($courseArray, $checkbox_Professor);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'create') {
    var_dump($_GET);
    if (isset($_GET['newUser'])) {
        $user = $checkData->Recive_get($_GET['newUser']);
        $pass = $checkData->Recive_get($_GET['Pass']);
        $role = $_GET['role'];
        $checkusername = new CheckUser();
        $result = $checkusername->check($user);
        if ($result) {
            $errors->set('usernameExists', 'نام کاربری از قبل وجود دارد');
        } elseif (!$result) {
            $arrayNewUser = [
                'username' => $user,
                'password' => $pass,
                'role' => $role
            ];
            $adminAccsess->createUser($arrayNewUser);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $userDelete = [
        'id' => $_GET['id']
    ];
    $adminAccsess->deleteUser($userDelete);
    header("Location: ./admin.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['Nrole'])) {
    $id = $_GET['id'];
    $newUser = $checkData->Recive_get($_GET['Nusername']);
    $newPass = $checkData->Recive_get($_GET['Npassword']);
    $newrole = $_GET['Nrole'];
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
                'role' => $newrole,
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
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['Nrole'])) {
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
            <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'create') : ?>
                <?php if ($errors->has('usernameExists')) { ?>
                    <span><?php echo $errors->get('usernameExists'); ?></span>
                <?php } ?>
                <h3>ایجاد کاربر جدید</h3>
                <form action="./admin.php" method="GET">
                    <label for="newUser">نام کاربری</label>
                    <input type="text" name="newUser" id="newUser">
                    <label for="Pass">رمز عبور</label>
                    <input type="Pass" name="Pass" id="Pass">
                    <label for="role">نقش</label>
                    <select name="role" id="role">
                        <option value="user">دانشجو</option>
                        <option value="admin">مدیر</option>
                        <option value="Professor">استاد</option>
                    </select>
                    <button type="submit">ذخیره</button>
                </form>
            <?php endif; ?>
            <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'edit') : ?>
                <form action="./admin.php" method="GET">
                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                    <label for="Nusername">نام کاربری</label>
                    <input type="text" name="Nusername" id="Nusername">
                    <label for="Npassword">رمز عبور</label>
                    <input type="password" name="Npassword" id="Nusername">
                    <label for="Nrole">نقش</label>
                    <select name="Nrole" id="Nrole">
                        <option value="user">دانشجو</option>
                        <option value="admin">مدیر</option>
                        <option value="Professor">استاد</option>
                    </select>
                    <button type="submit">ذخیره</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="addCourse">
            <form action="./admin.php" method="post">
                <label for="course" class="createcourseLabel">ایجاد دوره</label>
                <div class="Professors">
                    <?= $adminAccsess->showProfessor();
                    ?>
                </div>
                <textarea name="course" id="course" cols="30" rows="10"></textarea>
                <button type="submit">ثبت</button>
            </form>
        </div>
    </div>
</body>

</html>