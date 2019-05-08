<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>MG-CMS</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/">Монтаж групп CMS</a>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        @if (Route::currentRouteName()==="start")
                            <a class="nav-link active" href="/">
                        @else
                                    <a class="nav-link" href="/">
                        @endif
                            <span data-feather="home"></span>
                            Информация
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::currentRouteName()==="distributions")
                            <a class="nav-link active" href="distributions">
                                @else
                                    <a class="nav-link" href="distributions">
                                        @endif
                            <span data-feather="file"></span>
                            Формирование рассылок
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::currentRouteName()==="listDistributions")
                            <a class="nav-link active" href="listDistributions">
                                @else
                                    <a class="nav-link" href="listDistributions">
                                        @endif
                                        <span data-feather="file"></span>
                                        Список рассылок
                                    </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::currentRouteName()==="statistics")
                            <a class="nav-link active" href="statistics">
                                @else
                                    <a class="nav-link" href="statistics">
                                        @endif
                            <span data-feather="bar-chart-2"></span>
                            Статистика
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::currentRouteName()==="feedback")
                            <a class="nav-link active" href="feedback">
                                @else
                                    <a class="nav-link" href="feedback">
                                        @endif
                            <span data-feather="users"></span>
                            Обратная связь
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (Route::currentRouteName()==="tokens")
                            <a class="nav-link active" href="tokens">
                                @else
                                    <a class="nav-link" href="tokens">
                                        @endif
                            <span data-feather="ft-edit"></span>
                            Ключи доступа
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>