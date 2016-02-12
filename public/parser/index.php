<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Парсинг текста</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script type="text/javascript" src="js/jquery2.2.0.js"></script>
    <script type="text/javascript" src="js/parsing.js"></script>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <br>
    <p>
        <textarea class="form-control" rows="5" id="text">пошел   Петров     П.     П.  гулять и позвонил по телефону +7(905)123-45-67. Он купил хлеб.</textarea>
    </p>

    <button class="btn btn-info btn-block" id="parse">Распарсить</button>
    <hr/>
    <div id="res"></div>
</div>



</body>
</html>