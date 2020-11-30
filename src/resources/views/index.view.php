<?php
echo time();
$test = 'abv';
?>


<div>Unix: {!! time() !!}</div>
<div>Unix: {{ time() }}</div>
<div>Age: {{ $age }}</div>
<div>Age: {{ $test }}</div>
<div>Name: {{ strtoupper($name) }}</div>