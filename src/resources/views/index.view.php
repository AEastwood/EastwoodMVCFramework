<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="The homepage of Adam Eastwood. You can find my social media on here and stuff">
    <meta name="author" content="Adam Eastwood">
    <meta name="generator" content="EastwoodMVC">
    <meta name="framework" content="https://github.com/AEastwood/EastwoodMVCFramework">
    <title>{{ $_ENV['APP_NAME'] }}</title>
    <!--[if IE]>
    <link rel="shortcut icon" href="imgs/favicon.ico"><![endif]-->
    <link rel="icon" href="{{ asset('imgs/favicon.ico') }}">
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
            integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
            crossorigin="anonymous"
    >
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ redirect('/') }}">
        {{ $_ENV['APP_NAME'] }}
    </a>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">

        </ul>
        <form class="form-inline my-2 my-lg-0">
            <a role="button" class="btn btn-sm btn-danger">
                <i class="fas fa-envelope pr-2"></i>
                Email Me
            </a>
        </form>
    </div>
</nav>

<main role="main" class="container">

    <div class="portfolio">

        <img
                src="{{ asset('imgs/me.jpg') }}"
                class="rounded img-fluid me pb-3"
                alt="Me"
                width="230px"
                height="240px"
        >

        <h1>Adam Eastwood</h1>

        <p class="lead">Just me.</p>

        <p class="lead pt-2">
            <a
                    class="clean-link"
                    href="https://www.instagram.com/aeasywood/"
                    target="_blank"
                    title="instagram"
                    rel="noreferrer"
            >
                <i class="text-dark clean-link fab fa-instagram pr-3 h3"></i>
            </a>
            <a
                    class="clean-link"
                    href="https://twitter.com/adeastwood"
                    target="_blank"
                    title="twitter"
                    rel="noreferrer"
            >

                <i class="text-dark clean-link fab fa-twitter pr-3 h3"></i>
            </a>
            <a
                    class="clean-link"
                    href="https://github.com/aeastwood"
                    target="_blank"
                    title="Github"
                    rel="noreferrer"
            >
                <i class="text-dark clean-link fab fa-github pr-3 h3"></i>
            </a>
            <a
                    class="clean-link"
                    href="mailto:adam-3@live.co.uk"
                    title="Email Me"
                    rel="noreferrer"
            >
                <i class="text-dark clean-link fas fa-envelope h3"></i>
            </a>
        </p>

    </div>

</main>

<script src="https://kit.fontawesome.com/8bf1f276fe.js" crossorigin="anonymous"></script>
<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"
>
</script>

</html>