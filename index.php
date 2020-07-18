<html>
<style>
.split {
  height: 100%;
  width: 50%;
  position: fixed;
  z-index: 1;
  top: 0;
  overflow-x: hidden;
  padding-top: 20px;
}

.left {
  left: 0;
  background-color: lightsteelblue;
}

/* Control the right side */
.right {
  right: 0;
  background-color: linen;
  padding-left:15px;
}

.thumb {
    display:inline-block;
    width:160px;
    overflow-wrap: break-word;
    font-size:13px;
    margin:4px;
    padding: 8px;
    float:left;
    color:aliceblue;
    text-align:center;
    box-shadow:black 1px 1px 1px;
    background: lightslategray;
}

.thumb img {
    width:100px;
    background:white;
    display:block;
    margin:0 auto;
    margin-top:6px;
}

.thumb a {
    text-decoration:none;
    color: linen;
}

#stamp {
    position:absolute;
    left:0;
    visibility:hidden;
}

#mark{
    color:red;
    position:absolute;
    left:0;
    bottom:40;
    visibility:hidden;
}

#action {
    position:absolute;
    top:50px;
    color: steelblue;
    font-weight: bold;
    visibility:hidden;
}

.top {
    display: block;
    width: 100%;
    height: 90%;
    overflow: scroll;
    padding-left:1em;
}

.bottom {
    padding:1em;
    background:aliceblue;
    color:steelblue;
    position:absolute;
    bottom:0px;
    width:100%;
}

body {
    font-family: verdana;
    font-size:14px;
}
</style>
<script>
// Stamp position
posx = 0;
posy = 0;
file = "";
    
function loadImage(id){
    var c = document.getElementById("myCanvas");
    //var ctx = c.getContext("2d");
    
    var i = document.getElementById(id);
    //ctx.drawImage(i, 0, 0);
    c.src = i.src;
    
    file = id;
    document.getElementById("action").style.visibility = "visible";
}

function coordinates(e) {
    
    
        posx = e.offsetX ? (e.offsetX) : e.pageX - document.getElementById("myCanvas").offsetLeft;
        posy = e.offsetY ? (e.offsetY) : e.pageY - document.getElementById("myCanvas").offsetTop;
        
        width = document.getElementById("myCanvas").width;
        height = document.getElementById("myCanvas").height;
        
        document.getElementById("mark").style.left= document.getElementById("myCanvas").offsetLeft + posx;
        document.getElementById("mark").style.top= document.getElementById("myCanvas").offsetTop + posy;
        
        
        posx = Math.round(posx*100/width);
        posy = Math.round(posy*100/height);
        
        document.getElementById("mark").style.visibility = "visible";
        
        
        //document.getElementById("xy").innerHTML = width + "<br>";
        
        //document.getElementById("xy").innerHTML = posx+","+posy;
        
        
    
}

function createStamp(){
    window.location.href = "stamp.php?src=" + file;
}

</script>

<body>

<div class="split left">
    
<div class="top">
<?php
function folder_exist($folder)
{
    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    return ($path !== false AND is_dir($path)) ? $path : false;
}



$target_dir = "uploads/";

if (!folder_exist($target_dir)) mkdir($target_dir);


#print_r($_POST);

if (isset($_POST['submit'])){

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 12500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "pdf") {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}

}

$dir = scandir('uploads');
foreach ($dir as $f) {
    if (strpos($f,'.pdf') !== false) {
        echo "<div class='thumb'>";
        if ($_GET['erase'] == $f) {
            unlink ("uploads/$f");
            echo "$f was deleted.";

        } else {
            echo $f;
            echo "<img id='$f' src='pdfimg.php?src=uploads/$f' onclick='loadImage(this.id)'>";
            echo '<a href="'.$_SERVER['PHP_SELF'].'?erase='.$f.'">Erase</a>';
        }
        echo '</div>';
    }
}

?>


</div>

<div class="bottom">
    <form method="post" enctype="multipart/form-data">
        Select PDF to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>
</div>


</div>


<div class="split right">

    <div id="stamp">
        <span id="xy"></span>
    </div>


    <img id="myCanvas" onclick="coordinates(event)" style="max-height:90%;background:white;right: 20px;position: absolute;">

    <div id="mark">
        Stempel
    </div>

    <div id="action">
        <a onclick="createStamp()" style="cursor:pointer">Mark document</a>
    </div>

</div>



</body>
</html>
