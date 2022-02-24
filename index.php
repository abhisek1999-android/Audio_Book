<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Example</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.tailwindcss.com/"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" ></script>
    <script type="text/javascript" language="javascript">

        $(document).ready(function(){
            
            var p = document.getElementById('selected_file');
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                // alert('The file "' + fileName +  '" has been selected.');
                p.textContent = fileName;
            });
        });
    </script>

<script type="text/javascript" language="javascript">



$(function() {
    $(document).on("click", '#button_play_pause', function() {

      
      var myMusic=document.querySelector("#myMusic");
      var button=document.querySelector("#button_play_pause");
      let player = document.querySelector("lottie-player");
      
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

    });
});
</script>
  </head>
  <body>

<div class="bg-teal-800 flex flex-col sm:flex-row max-w-full">

<div class="h-screen rounded-tr-3xl flex-wind sm:flex-1 bg-teal-50">
<!-- <img src="logo.png" class="place-content-center h-28 w-96"/> -->
<div class="flex-row rounded-tr-3xl h-3/5">
    <h1 class="text-7xl font-bold text-teal-900  text-center mt-16">Audio Book.</h1>

    <form  method="post" action="">

    <input type="file" id="file" name="file_name" accept="application/pdf" style="display: none;"/>
    

    <label for="file" class="bg-teal-900 h-14 w-80 rounded-2xl absolute m-auto relative flex justify-center text-center 
    items-center text-white font-bold mt-24 "> <span class="material-icons p-2">
        picture_as_pdf
        </span>Choose File</label>
        <p id="selected_file" class="relative flex justify-center text-center items-center"> No file chosen</p>
        <input type="submit" class="bg-gray-900 h-14 w-40 rounded-2xl absolute m-auto relative flex justify-center text-center 
        items-center text-white font-bold mt-10 ">
    </form>

   <!-- <button class="p-8 w-1/3 text-white ml-96 mt-24 bg-teal-800 rounded-2xl text-3xl font-semibold"> <span class="material-icons p-2">
    picture_as_pdf
    </span>SELECT FILE</button> -->
    
    <!-- <input type="file" class="block w-96 tems-center text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold
    file:bg-teal-800 file:text-white
    hover:file:bg-teal-200 file:text-teal-800
  "/> -->
</div>

<div class="flex-row bg-gray-800 h-1/4 relative  mt-10  overflow-y-auto">
<p class="text-white p-4 bg-gray-300 text-gray-900 font-bold">Log info</p> 

<p class="text-white p-4 bg-gray-800 text-white">

<?php


      if(isset($_POST['file_name']))
      {

        $file_name=$_POST['file_name'];

        $command = escapeshellcmd("python MakeAudioBook.py $file_name");
        $output = shell_exec($command);
        echo $output;
       

      //   if (!isset($_SESSION)) {
      //     session_start();
      // }
      
      // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      //     $_SESSION['postdata'] = $_POST;
      //     unset($_POST);
      //     header("Location: ".$_SERVER['PHP_SELF']);
      //     exit;
      // }

    }

  
?>


</p>

</div>
</div>



<!-- playlist -->
<div class="h-screen sm:w-80 bg-teal-800">


    <div class="flex-row mt-16">

      <form method="post" class="float-right text-white font-semibold mr-6 mt-2">

        <input type="submit" name="refresh" value="Refresh">
      </form>
      
           <h1 class="text-white text-3xl font-semibold ml-3">  <span class="material-icons p-2">
            audiotrack</span>Play List</h1>

            
    </div>
    <div class="flex-row bg-teal-700 h-4/5 bg-gray-300 rounded-2xl m-3 overflow-y-auto overflow-x-clip">
    
    <p >

    <?php

        require __DIR__ .'/vendor/autoload.php';
        $bucketName="audio-book-musics-23022022";

        putenv("GOOGLE_APPLICATION_CREDENTIALS=GoogleCloudKey_MyServiceAcct.json");
        use Google\Cloud\Storage\StorageClient;

        $storage = new StorageClient();
        $bucket = $storage->bucket($bucketName);
        foreach ($bucket->objects() as $object) {

        $url="https://storage.googleapis.com/audio-book-musics-23022022/".$object->name();   
        $lotti_url="https://assets6.lottiefiles.com/packages/lf20_fmFSzb.json";
        $name=$object->name();

       
        echo "<div class='grid grid-cols-2 gap-0 m-2 '>";

        echo  "   <div class='rounded-t-2xl col-span-2 bg-teal-600 mt-3 p-2'>";
        echo      "<p class='text-white overflow-hidden '>Title :{$name}</p>";
        echo    "</div>";
        echo    "<div class='rounded-bl-2xl bg-teal-600 flex justify-center text-center items-center min-w-min'>";
        // echo      "<img src='play_button.png' id='button_play_pause' > ";
        echo    "<lottie-player src='{$lotti_url}' background='transparent'  speed='1'  style='width: 80px; height: 80px;'  loop></lottie-player>";        
                  
        echo   "</div>";
        echo  "<div class='... rounded-br-2xl bg-teal-600 flex justify-center text-center items-center p-2'>";

        echo '<audio controls>'; 
        echo "<source src='{$url}' type='audio/mpeg'>";
        echo '</audio>';
       
        echo " </div>";
        echo "</div>";

        echo '<p">'; 
  
        echo '</p>';
     
        }
?>

    </p>
    
    
    </div>
</div>





</div>    

<!-- <form action="index.php">

    <input type="submit" class="bg-gray-800 text-white p-4"/>
</form> -->


</script>

  </body>
</html>