<div>Date: {{ Date("F j, Y, g:i a") }}</div>
<div>Host: {{ MVC\Classes\App::body()->request->headers['Host'] }}</div>
<div>Unix: {{ time() }}</div>
