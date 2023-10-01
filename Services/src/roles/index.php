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
            <div class="title">Политики</div>
        </div>
        <div class="content c1">
            <div class="row">
                <span class="subtitle">Роли и конфликты интересов</span>
                <div class="table">
                    <table class="users">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
	                        <th>Конфликты</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = mysqli_query($link, "SELECT * FROM `roles`");
                        while ($u = mysqli_fetch_array($query)) {
                            echo '
		                <tr>
			                <td>'.$u['id'].'</td>
			                <td>'.$u['name'].'</td>
			                <td>'.$u['conflict'].'</td>
			                <td>
			                    <a href="r.php?id='.$u['id'].'" class="button-edit">Редактировать</a>
						    </td>
		                </tr>'
                            ;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <span class="subtitle">Атрибуты</span>
                <div class="table">
                    <table class="users">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = mysqli_query($link, "SELECT * FROM `attributes`");
                        while ($u = mysqli_fetch_array($query)) {
                            echo '
		                <tr>
			                <td>'.$u['id'].'</td>
			                <td>'.$u['name'].'</td>
			                <td>
			                    <a href="a.php?id='.$u['id'].'" class="button-edit">Редактировать</a>
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
</div>
</body>
</html>