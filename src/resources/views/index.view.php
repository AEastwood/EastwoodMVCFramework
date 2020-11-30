<?php
$test = '<marquee>test</marquee>';
?>

<div>Unix: {!! time() !!}</div>
<div>Unix: {{ time() }}</div>
<div>Unix: {{ $age }}</div>
<div>Unix: {{ strtoupper($name) }}</div>
<div>Unix: {{ $test }}</div>
<div>Unix: {!! $test !!}</div>