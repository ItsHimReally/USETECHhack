<?php
include('../php/db.php');
$link = connectDB();
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
            <div class="title">Пользователи</div>
        </div>
        <div class="content">
            <div class="table">
	            <table class="users">
		            <thead>
		            <tr>
			            <th>ID</th>
			            <th>Имя</th>
			            <th>Фамилия</th>
			            <th>Роль</th>
			            <th>Атрибуты</th>
			            <th>Комментарий</th>
			            <th>Действия</th>
		            </tr>
		            </thead>
		            <tbody>
		            <?php
    $query = mysqli_query($link, "SELECT * FROM `users`");
	while ($u = mysqli_fetch_array($query)) {
        $roles = explode(",", $u['role']); $htmlRoles = "";
		foreach ($roles as $v) {
            if ($stmt = mysqli_prepare($link, "SELECT `name` FROM `roles` WHERE `id` LIKE ?")) {
                mysqli_stmt_bind_param($stmt, "s", $v);
                mysqli_stmt_execute($stmt);
                $name = mysqli_fetch_array(mysqli_stmt_get_result($stmt))['name'];
                $htmlRoles .= '<span class="tag">' . $name . '</span>';
            }
		}
        $attribute = explode(",", $u['attribute']); $htmlAtr = "";
        foreach ($attribute as $w) {
            if ($stmt = mysqli_prepare($link, "SELECT `name` FROM `attributes` WHERE `id` LIKE ?")) {
                mysqli_stmt_bind_param($stmt, "s", $w);
                mysqli_stmt_execute($stmt);
                $name = mysqli_fetch_array(mysqli_stmt_get_result($stmt))['name'];
                $htmlAtr .= '<span class="tag">' . $name . '</span>';
            }
        }
		echo '
		            <tr>
			            <td>'.substr($u["client_id"], 0, 4).'...</td>
			            <td>'.$u["name"].'</td>
			            <td>'.$u["surname"].'</td>
			            <td>'.$htmlRoles.'</td>
			            <td>'.$htmlAtr.'</td>
			            <td>'.$u["comment"].'</td>
			            <td>
			                <a href="user.php?id='.$u['id'].'" class="button-edit">Редактировать</a>
			                <a href="delete.php?id='.$u['id'].'" class="button-delete">Удалить</a>
						</td>
		            </tr>'
		;
    }
		            ?>
		            </tbody>
	            </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>