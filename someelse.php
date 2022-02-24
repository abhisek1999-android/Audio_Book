<Html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Example</title>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" ></script>
    <script src="https://cdn.tailwindcss.com/"></script>
  </head>
  
  <body>
    
<audio id='myMusic'> 
 <source src="https://storage.googleapis.com/audio-book-musics-23022022/music_file.mp3" type="audio/mpeg">
</audio>

<!-- <div class="bg-gray-900 grid grid-cols-2 gap-4">

  <img src="play_button.png" id="button" class="...">
  <p class="...">Title Image</p>
  
  <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_fmFSzb.json" class="row-span-2" background="transparent"  speed="1"  style="width: 30px; height: 30px;"  loop></lottie-player>

</div> -->

<div class="grid grid-cols-2 gap-4">

  <div class="... bg-cyan-400">
    <p>Title Image</p>
  </div>
  <div class="row-span-2 bg-cyan-400">
    <img src="play_button.png" id="button"> 
    
    
  </div>
  <div class="... bg-cyan-400">
    <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_fmFSzb.json" background="transparent"  speed="1"  style="width: 90px; height: 90px;"  loop></lottie-player>
  </div>
</div>
<script>

var myMusic=document.getElementById("myMusic");
var button=document.getElementById("button");
let player = document.querySelector("lottie-player");

button.onclick=function(){

  if(myMusic.paused){
    myMusic.play();
    button.src="pause_button.png";
    player.play();

    // do need

  }else{

    myMusic.pause();
    button.src="play_button.png";
    player.stop();
  }
 
}

</script>
  
</body>


    </html>

