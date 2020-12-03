<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Adam Eastwood">
    <meta name="generator" content="EastwoodMVC">
    <meta name="framework" content="https://github.com/AEastwood/EastwoodMVCFramework">
    <title>{{ $_ENV['APP_NAME'] }}</title>
    <!--[if IE]><link rel="shortcut icon" href="imgs/favicon.ico"><![endif]-->
    <link rel="icon" href="imgs/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/8bf1f276fe.js" crossorigin="anonymous"></script>
    <script src="js/main.js" crossorigin="anonymous"></script>
    <link href="css/main.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="{{ $_ENV['BASE_URL'] }}">{{ $_ENV['APP_NAME'] }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <a role="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#contactModal">
                    <i class="fas fa-envelope pr-2"></i>
                    Email Me
                </a>
            </form>
        </div>
    </nav>

    <main role="main" class="container">

        <div class="starter-template">
            
            <img src="imgs/me.jpg" class="rounded img-fluid me pb-3">
            
            <h1>Adam Eastwood</h1>
            
            <p class="lead">Just me.</p>
            
            <p class="lead pt-2">
            <a class="clean-link" href="https://www.instagram.com/aeasywood/" target="_blank" title="instagram">
                    <i class="text-dark clean-link fab fa-instagram pr-3 h3"></i>
                </a>
                <a class="clean-link" href="https://twitter.com/adeastwood" target="_blank" title="twitter">
                    <i class="text-dark clean-link fab fa-twitter pr-3 h3"></i>
                </a>
                <a class="clean-link" href="https://github.com/aeastwood" target="_blank" title="Github">
                    <i class="text-dark clean-link fab fa-github pr-3 h3"></i>
                </a>
                <a class="clean-link" href="#" title="Email Me" data-toggle="modal" data-target="#contactModal">
                    <i class="text-dark clean-link fas fa-envelope h3"></i>
                </a>
            </p>

        </div>

    </main>

    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-status">
                    
                </div>

                <form id="messageForm">
                    @csrf
                    <div class="form-group">
                        <label for="sender-name" class="col-form-label">Your name:</label>
                        <input type="text" class="form-control" id="sender-name" minlength="3" maxlength="100" required>
                        <small id="messageHelp" class="form-text text-muted">Min: 3, Max: 100</small>
                    </div>
                    <div class="form-group">
                        <label for="sender-email" class="col-form-label">Your email:</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="sender-email"
                            minlength="5"
                            maxlength="100"
                            required
                        >
                        <small id="messageHelp" class="form-text text-muted">Min: 5, Max: 100</small>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Your message:</label>
                        <textarea class="form-control" id="message-text" rows="6" minlength="5" maxlength="1000" required></textarea>
                        <small id="messageHelp" class="form-text text-muted">Min: 10, Max: 1000</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="sendMessageButton">Send message</button>
            </div>
            </div>
        </div>
    </div>

    <!-- /.container -->
    <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous">
    </script>
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</html>