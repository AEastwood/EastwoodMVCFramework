<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="The homepage of Adam Eastwood. You can find my social media on here and stuff">
    <meta name="author" content="Adam Eastwood">
    <meta name="keywords" content="AdamEastwood, Adam, Eastwood, AEastwood, AEasywood">
    <meta name="generator" content="EastwoodMVC">
    <meta name="framework" content="https://github.com/AEastwood/EastwoodMVCFramework">
    <title>{{ $_ENV['APP_NAME'] }}</title>
    <!--[if IE]>
    <link rel="shortcut icon" href="imgs/favicon.ico">
    <![endif]-->
    <link rel="icon" href="{{ secure_asset('imgs/favicon.ico') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
</head>

<body
        class="container d-flex justify-content-center align-items-center d-inline-block text-center align-middle"
        style="height:90vh; overflow:hidden;"
>

<main role="main" class="gradient-border">
    <div id="main-content">
        <img
                src="{{ secure_asset('imgs/me.webp') }}"
                class="me rounded img-fluid gradient-border"
                alt="Me"
        >

        <h1 class="mt-3">Adam Eastwood</h1>

        <h2 class="lead py-2">Just me.</h2>

        <div class="d-flex justify-content-evenly">
            <div>
                <a
                        href="https://www.instagram.com/aeasywood/"
                        target="_blank"
                        title="instagram"
                        rel="noreferrer"
                >
                    <i class="text-dark fab fa-instagram pr-3 h3"></i>
                </a>
            </div>

            <div>
                <a
                        href="https://twitter.com/adeastwood"
                        target="_blank"
                        title="Twitter"
                        rel="noreferrer"
                >

                    <i class="text-dark fab fa-twitter pr-3 h3"></i>
                </a>
            </div>

            <div>
                <a
                        href="https://github.com/aeastwood"
                        target="_blank"
                        title="Github"
                        rel="noreferrer"
                >
                    <i class="text-dark fab fa-github pr-3 h3"></i>
                </a>
            </div>

            <div>
                <a
                        href="https://www.linkedin.com/in/adeastwood/"
                        target="_blank"
                        title="LinkedIn"
                        rel="noreferrer"
                >
                    <i class="text-dark fab fa-linkedin pr-3 h3"></i>
                </a>
            </div>

            <div>
                <a
                        href="mailto:adam-3@live.co.uk"
                        title="Email Me"
                        rel="noreferrer"
                >
                    <i class="text-dark fas fa-envelope h3"></i>
                </a>
            </div>
        </div>
    </div>
</main>
</body>

<script src="https://kit.fontawesome.com/8bf1f276fe.js" crossorigin="anonymous"></script>
</html>