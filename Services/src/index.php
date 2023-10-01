<?php
    include('php/db.php');
    $link = connectDB();

	$countAll = mysqli_fetch_row(mysqli_query($link, "SELECT COUNT(*) FROM logs"))[0];
	$countUsers = mysqli_fetch_row(mysqli_query($link, "SELECT COUNT(*) FROM users"))[0];
	$countIncidents = mysqli_fetch_row(mysqli_query($link, "SELECT COUNT(*) FROM logs WHERE client_id LIKE 'error'"))[0];

	$query = mysqli_query($link, "SELECT * FROM logs ORDER BY `id` DESC LIMIT 5;");
?>

<html>
    <head>
        <title>USEcure</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style.css" media="all">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="USEcure">
        <meta http-equiv="Content-language" content="ru-RU">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700;900&family=Roboto&display=swap" rel="stylesheet">
    </head>
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
                <div class="title">USEcure</div>
	        </div>
            <div class="content">
				<div class="row">
					<span class="subtitle">Глобальная статистика</span>
					<div class="toplist">
						<div class="blockList">
							<div class="list">
								<div class="place"><?=$countAll?></div>
								<div class="sublist">
									<div class="name">Входов</div>
									<div class="subname">всего</div>
								</div>
							</div>
						</div>
						<div class="blockList">
							<div class="list">
								<div class="place"><?=$countUsers?></div>
								<div class="sublist">
									<div class="name">Пользователей</div>
									<div class="subname">всего</div>
								</div>
							</div>
						</div>
						<div class="blockList">
							<div class="list">
								<div class="place"><?=$countIncidents?></div>
								<div class="sublist">
									<div class="name">Инцидентов</div>
									<div class="subname">всего</div>
								</div>
							</div>
						</div>
					</div>
				</div>
	            <div class="row">
		            <span class="subtitle">Последние события</span>
		            <div class="toplist">
			            <?php
			            $i = 1;
			            while ($stat = mysqli_fetch_array($query)) {
							if ($stat['errorCode'] == 200) {
								$nameCode = "New entry";
							} else {
								$nameCode = "Failed entry";
							}
                            echo '<div class="blockList">
				            <div class="list">
					            <div class="place">'.$i.'</div>
					            <div class="sublist">
						            <div class="name">'.$nameCode.' <strong>'.$stat['client_id'].'</strong></div>
						            <div class="subname">'.$stat['datetime'].'</div>
					            </div>
				            </div>
			            </div>';
							$i++;
                        }
			            ?>
		            </div>
	            </div>
            </div>
        </div>
    </div>
    <script>
    function toggle(el) {
        el.style.display = (el.style.display == 'block') ? '' : 'block'
    }
    </script>
</html>
