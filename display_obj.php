<html>

    <head>

    <script src="https://cdn.tailwindcss.com/"></script>
    </head>
<body>    
<?php

require __DIR__ .'/vendor/autoload.php';
$bucketName="audio-book-musics-23022022";

putenv("GOOGLE_APPLICATION_CREDENTIALS=GoogleCloudKey_MyServiceAcct.json");
use Google\Cloud\Storage\StorageClient;

$storage = new StorageClient();
$bucket = $storage->bucket($bucketName);
foreach ($bucket->objects() as $object) {

$url="https://storage.googleapis.com/audio-book-musics-23022022/".$object->name();   

echo '<p class="bg-teal-900 p-4 text-white">';
echo 'Title : ' . PHP_EOL,$object->name();  
echo '<audio controls>'; 
echo "<source src='{$url}' type='audio/mpeg'>";
echo '</audio>';
echo '</p>';
}
?>

</body> 
</html>

<!-- <audio controls>
 
  <source src="https://storage.googleapis.com/audio-book-musics-23022022/music_file.mp3" type="audio/mpeg">

</audio> -->