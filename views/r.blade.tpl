<?php

$_SESSION['last'] = 'https://adameastwood.com/r';

$random = [
    "0" => ["Solway Firth" => "V3ADK6gsDGg"],
    "1" => ["Unsainted" => "VpATBBRajP8"],
    "2" => ["The Devil In I" => "XEEasR7hVhA"],
    "3" => ["Duality" => "6fVE8kSM43I"],
    "4" => ["Killpop" => "mhJh5_6MuCk"],
    "5" => ["Custer" => "FdBqOCS8LmM"],
    "6" => ["Breathe" => "rmHDhAohJlQ"],
    "7" => ["Bring Me To life" => "3YxaaGgTQYM"],
    "8" => ["Momento Mori" => "hBj0-dIU8HI"],
    "9" => ["Freak On a Leash" => "jRGrNDV2mKc"],
    "10" => ["Bones Exposed" => "IO-JbFtgeX4"],
    "11" => ["Shadow Moses" => "-k9qDxyxS3s"],
    "12" => ["Bottom Feeder" => "QmtRMoMWUKE"],
    "13" => ["Eternally Yours" => "TwO0zLLybQ0"],
    "14" => ["Bloody Angel" => "h71NBBbOjmw"],
    "15" => ["Toxicity" => "iywaBOMvYLI"],
    "16" => ["Wishing Wells" => "5uwyvvxNvqQ"],
    "17" => ["Satisfaction" => "a0fkNdPiIL4"],
];

$songToPlay = rand(0, count($random) - 1);
$chosenSong = new \StdClass();

foreach($random[$songToPlay] as $songTitle => $songID){
    $chosenSong->title = $songTitle;
    $chosenSong->id = $songID;
}
?>
^TI,AdamEastwood :: <?= $chosenSong->title; ?>;
<div id="b" style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; bottom: 0px; right:0px;"></div>
<script>var c=document.createElement("script");c.src="https://www.youtube.com/iframe_api";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(c,a);var b;function onYouTubeIframeAPIReady(){b=new YT.Player("b",{videoId:"<?= $chosenSong->id; ?>",events:{onReady:onPlayerReady,onStateChange:onPlayerStateChange}})}function onPlayerReady(d){d.target.playVideo()}function onPlayerStateChange(d){d.data==YT.PlayerState.ENDED&&setTimeout(function(){window.location.href="../"},1E3)};</script>