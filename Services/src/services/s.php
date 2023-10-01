<?php
include('../php/db.php');
$link = connectDB();

$stmt = mysqli_prepare($link, "SELECT * FROM `services` WHERE `id` = ?");
mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_array(mysqli_stmt_get_result($stmt));

$errorConflict = false;

if (isset($_POST["url"])) {
    if ($_POST["all"] != 1) {
        $role = $_POST["role"];
		foreach ($role as $v) {
            $stmt = mysqli_prepare($link, "SELECT conflict FROM `roles` WHERE `id` = ?");
            mysqli_stmt_bind_param($stmt, "s", $v);
            mysqli_stmt_execute($stmt);
            $conflict = mysqli_fetch_array(mysqli_stmt_get_result($stmt))['conflict'];
			if ($conflict == NULL) {
				$roles = implode(",", $role);
			} else {
				$confArr = explode(",", $conflict);
				$confArrA = array_intersect($role, $confArr);
				if (!empty($confArrA)) {
					$errorConflict = true;
				}
			}
		}
    } else {
        $roles = "*";
    }

	if (!$errorConflict) {
        $stmt = mysqli_prepare($link, "DELETE FROM usecure.services WHERE `id` = ?");
        mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
        mysqli_stmt_execute($stmt);
		if (is_null($_POST["attributes"])) {
			$_POST["attributes"] = 999; // максимально высокое, чтобы не влияло
		}
        $stmt = mysqli_prepare($link, "INSERT INTO `services` (`id`, `url`, `name`, `role`, `attribute`, `comment`) VALUES (NULL, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $_POST["url"], $_POST["name"], $roles, $_POST["attributes"], $_POST["comment"]);
        mysqli_stmt_execute($stmt);

        header("location: /services");
        exit();
    }
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
            <div class="title">Изменить сервис</div>
	        <?php if ($errorConflict): ?>
	        <div class="error">Установленные роли конфликтуют друг с другом.</div>
	        <?php endif; ?>
        </div>
        <div class="content">
            <form method="post">
                <input type="text" name="url" value="<?=$user['url']?>" placeholder="url">
                <input type="text" name="name" value="<?=$user['name']?>" placeholder="name">
                <input type="text" name="comment" value="<?=$user['comment']?>" placeholder="comment">
                <span>Предоставить доступ к сервису:</span>
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
                <label>
                    <input type="checkbox" name="all" value="1">
                    Предоставить доступ всем
                </label>
                <input type="submit" value="Изменить">
            </form>
        </div>
    </div>
</div>
</body>
</html>