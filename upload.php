<!DOCTYPE>

<?php
$month = "June";
$monthVideos = simplexml_load_file("data/videos/". $month . ".xml");
$monthAudio = simplexml_load_file("data/audio/". $month . ".xml");
$tz = date_default_timezone_set('America/Denver');

if (isset($_POST["submit"])) {
    $uploadOk = 1;
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Get media type
    if ($fileType == "mp4" || $fileType == "OGG" || $fileType == "MOV" || $fileType == "mov") {
        $mediatype = 'videos';
        $target_dir = "uploads/videos/" . $month . "/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
    } else if ($fileType == "M4A" || $fileType == "mp3" || $fileType == "WAV") {
        $mediatype = 'audio';
        $target_dir = "uploads/audio/" . $month . "/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
    } else {
        $message = "That file type is not supported.";
        $uploadOk = 0;
    }

    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        if ($title == "" || $title == " ") {
            $message = "Please set a title.";
            $uploadOk = 0;
        } else if (strlen($title) > 20) {
            $message = "Your title must be a maximum of 20 characters.";
            $uploadOk = 0;
        }
    } else {
        $message = "Please set a title.";
        $uploadOk = 0;
    }
    if (isset($_POST['author'])) {
        $author = $_POST['author'];
        if ($author == "" || $author == " ") {
            $message = "Please set an author.";
            $uploadOk = 0;
        } else if (strlen($author) > 20) {
            $message = "Your author name must be a maximum of 20 characters.";
            $uploadOk = 0;
        }
    } else {
        $message = "Please set an author.";
        $uploadOk = 0;
    }
    if (isset($_POST['tags'])) {
        $tags = $_POST['tags'];
        if ($tags == "" || $tags == " ") {
            $message = "Please set your tags.";
            $uploadOk = 0;
        }
        $check = explode(" ", $tags);
        if (count($check) > 3) {
            $message = "Maximum of three tags.";
            $uploadOk = 0;
        }
        for ($i = 0; $i < count($check); $i++) {
            if (strlen($check[$i]) > 6) {
                $message = "Each tag allows for a maximum of 6 characters.";
                $uploadOk = 0;
            }
        }
    } else {
        $message = "Please set your tags.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $message = "That file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["file"]["size"] > 1000000000) {
        $message = "That file is too large to upload.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo $message;
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
            if ($mediatype == 'videos') {
                $media = $monthVideos->addChild('video');
            } else if ($mediatype == 'audio') {
                $media = $monthAudio->addChild('sound');
            }
            $media->addAttribute('title', $title);
            $media->addAttribute('author', $author);
            $media->addAttribute('tags', $tags);
            $media->addAttribute('rating', 0);
            $media->addChild('path', '"' . $target_file . '"');
            $media->addChild('date', date("m/d/Y"));
            $media->addChild('time', date("h:i:s a"));
            if ($mediatype == 'videos') {
                $monthVideos->asXML("data/videos/". $month . ".xml");
            } else if ($mediatype == 'audio') {
                $monthAudio->asXML("data/audio/". $month . ".xml");
            }
        } else if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            die("Upload failed with error code " . $_FILES['file']['error']);
        }
    }
}
?>

<html>

<head>
    <title>Ice Media | Upload</title>
    <link rel="stylesheet" type="text/css" href="w3.css">
</head>

<body>
    <div class="w3-panel w3-center">
        <img src="banner.png">
    </div>

    <div class="w3-card-4 w3-center" style="margin-left:250px;margin-right:250px;">
        <div class="w3-container w3-blue">
            <h2>Upload Video</h2>
        </div>

        <form class="w3-container" action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" style="margin-top:10px;margin-bottom:10px;"></br> 

            <div class="w3-tooltip">
            <label>Title</label>
            <input class="w3-input" type="text" name="title"></br>
            <p class="w3-text">The title of the media. Max of 20 characters.</p>
            </div>

            <div class="w3-tooltip">
            <label>Author</label>
            <input class="w3-input" type="text" name="author"></br>
            <p class="w3-text">The author of the media. Max of 20 characters.</p>
            </div>

            <div class="w3-tooltip">
            <label>Tags</label>
            <input class="w3-input" type="text" name="tags"></br>
            <p class="w3-text">Seperate each tag by a space. Max of six characters in each tag, and max of three tags.</p>
            </div>

            <button class="w3-btn w3-blue" name="submit" style="margin-bottom:10px">Upload</button>
        </form>
    </div>
    <a class="w3-btn w3-blue" href="index.php" style="margin-left:250px">Back</a>
</body>

</html>