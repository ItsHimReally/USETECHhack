<?php
include('../php/db.php');
$link = connectDB();

$stmt = mysqli_prepare($link, "SELECT * FROM `attributes` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

if (isset($_POST["name"])) {
    $roles = implode(",", $_POST["role"]);

    $stmt = mysqli_prepare($link, "UPDATE `roles` SET `name` = ? WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "ss", $_POST["name"], $_GET["id"]);
    mysqli_stmt_execute($stmt);

    header("location: /roles");
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
	        <a href="/services"><img class="icon" src="/images/box-fill.svg" alt="Сервисы"></a>
	        <a href="/roles"><img class="icon" src="/images/person-lines-fill.svg" alt="Роли"></a>
        </div>
    </div>
    <div class="mheader">
	    <a href="/"><img class="icon" src="/images/bar-chart-fill.svg" alt="Главная"></a>
	    <a href="/users"><img class="icon" src="/images/person-fill.svg" alt="Пользователи"></a>
	    <a href="/services"><img class="icon" src="/images/box-fill.svg" alt="Сервисы"></a>
	    <a href="/roles"><img class="icon" src="/images/person-lines-fill.svg" alt="Роли"></a>
    </div>
    <div class="page">
        <div class="titleFlex">
            <div class="title">Изменить роль</div>
        </div>
        <div class="content">
            <form method="post">
                <span>ID: <?=$user["id"]?></span>
                <input type="text" name="name" value="<?=$user['name']?>" placeholder="name">
                <input type="submit" value="Изменить">
            </form>
        </div>
    </div>
</div>
</body>
</html>