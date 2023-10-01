<?php
    if (!isset($_GET["id"])) {
        header("location: /users");
        exit();
    }

    include('../php/db.php');
    $link = connectDB();

    $stmt = mysqli_prepare($link, "SELECT * FROM `users` WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

	if ($_GET["confirm"]) {
        $stmt = mysqli_prepare($link, "DELETE FROM usecure.users WHERE `id` = ?");
        mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
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
</div>
<div class="page">
	<div class="content">
        <div class="delete">
            <img src="/images/exclamation-triangle-fill.svg" alt="">
	        <div class="delete_f">
                <span>Вы уверены, что хотите удалить пользователя <strong><?=$user["name"]?> <?=$user["surname"]?></strong>?</span>
                <div class="buttons">
                    <a href="delete.php?confirm=1&id=<?=$user['id']?>" class="button-confirm">Да, удалить!</a>
                    <a href="/users" class="button-back">Вернуться</a>
                </div>
	        </div>
        </div>
	</div>
</div>
</body>
</html>
