<?php
include('../php/db.php');
$link = connectDB();

$stmt = mysqli_prepare($link, "SELECT * FROM `users` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

if (isset($_POST["client_id"])) {
	$roles = implode(",", $_POST["role"]);

    $stmt = mysqli_prepare($link, "DELETE FROM usecure.users WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
    mysqli_stmt_execute($stmt);
	$stmt = mysqli_prepare($link, "INSERT INTO `users` (`id`, `client_id`, `user_name`, `name`, `surname`, `role`, `attribute`, `comment`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");
	mysqli_stmt_bind_param($stmt, "sssssss", $_POST["client_id"], $_POST["user_name"], $_POST["name"], $_POST["surname"], $roles, $_POST["attributes"], $_POST["comment"]);
    mysqli_stmt_execute($stmt);

    header("location: /users");
	exit();
}
?>
<html>
<head>
    <title>USEcure</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/style.css" media="all">
    <link rel="stylesheet" href="/css/users.css" media="all">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Description" content="USEcure">
    <meta http-equiv="Content-language" content="ru-RU">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <div class="sideBar">
        <div class="menu">
            <a href="/"><img class="icon" src="/images/bar-chart-fill.svg" alt="Главная"></a>
            <a href="/users"><img class="icon" src="/images/person-fill.svg" alt="Пользователи"></a>
            <a href="/roles"><img class="icon" src="/images/person-lines-fill.svg" alt="Роли"></a>
            <a href="/services"><img class="icon" src="/images/box-fill.svg" alt="Сервисы"></a>
        </div>
    </div>
    <div class="mheader">
        <a href="/"><img class="icon" src="/images/bar-chart-fill.svg" alt="Главная"></a>
        <a href="/users"><img class="icon" src="/images/person-fill.svg" alt="Пользователи"></a>
        <a href="/roles"><img class="icon" src="/images/person-lines-fill.svg" alt="Роли"></a>
        <a href="/services"><img class="icon" src="/images/box-fill.svg" alt="Сервисы"></a>
    </div>
    <div class="page">
        <div class="titleFlex">
            <div class="title">Изменить пользователя</div>
        </div>
        <div class="content">
            <form method="post">
                <input type="text" name="client_id" value="<?=$user['client_id']?>" placeholder="client_id in jwt">
                <input type="text" name="user_name" value="<?=$user['user_name']?>" placeholder="nickname">
                <input type="text" name="name" value="<?=$user['name']?>" placeholder="name">
                <input type="text" name="surname" value="<?=$user['surname']?>" placeholder="surname">
	            <input type="text" name="comment" value="<?=$user['comment']?>" placeholder="comment">
                <div class="roles">
                    <?php
                    $q = mysqli_query($link, "SELECT * FROM roles");
                    while ($r = mysqli_fetch_array($q)) {
						$roles = explode(",", $user["role"]); $c = "";
						if (array_search($r, $roles)) {
							$c = "checked";
						}
                        echo '
                    <label>
                        <input type="checkbox" name="role[]" value="'.$r["id"].'" '.$c.'>
                        '.$r["name"].'
                    </label>';
                    }
                    ?>
                </div>
                <div class="roles">
                    <?php
                    $q = mysqli_query($link, "SELECT * FROM attributes");
                    while ($r = mysqli_fetch_array($q)) {
                        echo '
                    <label>
                        <input type="radio" name="attributes" value="'.$r["id"].'">
                        '.$r["name"].'
                    </label>';
                    }
                    ?>
                </div>
                <input type="submit" value="Изменить">
            </form>
        </div>
    </div>
</div>
</body>
</html>