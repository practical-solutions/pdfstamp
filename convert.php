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
    color:#417ebf;
    background:white;
    position:absolute;
    left:0;
    bottom:40;
    border:1px solid #417ebf;
    visibility:hidden;
    height:20px;
    display:inline-table;
    font-size:11px;
    box-shadow: black 2px 2px 2px;
    max-height: fit-content;
}

#action {
    position:absolute;
    top:50px;
    color: steelblue;
    font-weight: bold;
    visibility:hidden;
    border: 1px solid steelblue;
    padding: 2px;
}

.top {
    display: block;
    width: 100%;
    height: 90%;
    overflow-y: scroll;
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

#text, #uploadmsg {
    display: block;
    text-align: center;
    margin-top: 30%;
    font-size: 24px;
    font-weight: bold;
    color: lightblue;
}

#uploadmsg {
    color:linen;
}

.msg {
    color:black;
    background:cornsilk;
    width:90%;
    margin-bottom:1em;
}

#stamptext {
    display:none;
}

#savetext {
    display:none;
    width: 3em;
    text-align: center;
    color: black;
    margin: 1px;
    cursor: pointer;
    background:lightgray;
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
    document.getElementById("text").style.display = "none";
    document.getElementById("action").style.visibility = "hidden";
    document.getElementById("mark").style.visibility = "hidden";
    document.getElementById("stampcaption").innerHTML = "Stamp";
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
        document.getElementById("action").style.visibility = "visible";
        
        document.getElementById("mark").style.width = parseInt(document.getElementById("myCanvas").width * 0.24);
        
}

function createStamp(){
    var text = encodeURI(document.getElementById("stampcaption").innerHTML);
    window.location.href = "stamp.php?src=" + file + '&x=' + posx + '&y=' + posy + '&text=' + text;
}

function edittext(){
    document.getElementById("stampcaption").style.display = "none";
    
    document.getElementById("stamptext").value = document.getElementById("stampcaption").innerHTML;
    
    document.getElementById("stamptext").style.display = "block";
    document.getElementById("savetext").style.display = "block";
}

function setText(){
    document.getElementById("stampcaption").innerHTML = document.getElementById("stamptext").value;
    
    document.getElementById("stampcaption").style.display = "block";
    
    document.getElementById("stamptext").style.display = "none";
    document.getElementById("savetext").style.display = "none";
    
}

function checkload(id){
    if (id == src) loadImage(id);
    
}

// Retrieve GET-Parameter
urlParams = new URLSearchParams(window.location.search);
var src = urlParams.get('src');

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

function msg($m) {
    echo "<div class='thumb msg'>$m</div>";
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
  msg("Sorry, file already exists.");
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 12500000) {
  msg("Sorry, your file is too large.");
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "pdf") {
  msg("Sorry, only PDF files are allowed.");
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  msg("Sorry, your file was not uploaded.");
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    msg("The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
  } else {
    msg("Sorry, there was an error uploading your file.");
  }
}

}


if (isset($_GET['msg'])) msg($_GET['msg']);

$dir = scandir('uploads');
$found = 0;
foreach ($dir as $f) {
    if (strpos($f,'.pdf') !== false) {
        
        if ($_GET['erase'] == $f) {
            unlink ("uploads/$f");
            msg("$f was deleted.");

        } else {
            echo "<div class='thumb'>";
            echo $f;
            echo "<img id='$f' src='pdfimg.php?src=uploads/$f' onclick='loadImage(this.id)' onload='checkload(this.id)'>";
            echo '<a href="'.$_SERVER['PHP_SELF'].'?erase='.$f.'">Erase</a> | ';
            echo '<a href="uploads/'.$f.'" download>Download</a>';
            $found++;
            echo '</div>';
        }
        
    }
}

if ($found==0) { ?>
<div id="uploadmsg">
Upload a file to start
</div>

<?php } ?>


</div>

<div class="bottom">
    <form method="post" enctype="multipart/form-data" action="convert.php">
        Select PDF to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" >
        <input type="submit" value="Upload PDF" name="submit">
    </form>
</div>


</div>


<div class="split right">
    
    <div id="text">
    Choose a pdf file on the left
    </div>
    
    
    <div id="stamp">
        <span id="xy"></span>
    </div>


    <img id="myCanvas" onclick="coordinates(event)" style="max-height:90%;background:white;right: 20px;position: absolute;">

    <div id="mark">
        <span id="stampcaption" onclick="edittext()">Stamp</span>
        
        <textarea id="stamptext"></textarea>
        <span id="savetext" class="button" onclick="setText()">OK</span>
    </div>
    

    <div id="action">
        <a onclick="createStamp()" style="cursor:pointer">Mark document</a>
    </div>

</div>



</body>





</html>
