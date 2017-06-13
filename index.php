<!DOCTYPE>

<?php
	$feed=simplexml_load_file("data/videos/June.xml") or die("Error: Cannot create object");
?>

<html>

<head>
    <title>ICE Media | Feed</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> 
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
    <link rel="stylesheet" type="text/css" href="w3.css">
    <link href="http://vjs.zencdn.net/5.8.8/video-js.css" rel="stylesheet">
</head>

<body>
    <div class="w3-panel w3-center">
        <img src="banner.png">
    </div>

    <div class="w3-bar w3-light-gray" style="padding-left: 250px; padding-right: 250px;">
        <div class="w3-dropdown-hover">
            <button class="w3-button">FILTER</button>
            <div class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="#" class="w3-bar-item w3-button">Video</a>
                <a href="#" class="w3-bar-item w3-button">Audio</a>
            </div>
        </div>
        <input type="text" class="w3-bar-item w3-input" placeholder="Search..">
        <a class="w3-bar-item w3-button w3-right" href="upload.php">UPLOAD</a>
        <div class="w3-dropdown-hover w3-right">
            <button class="w3-button">SORT</button>
            <div class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="#" class="w3-bar-item w3-button">Most Recent</a>
                <a href="#" class="w3-bar-item w3-button">Oldest</a>
                <a href="#" class="w3-bar-item w3-button">Alphabetical</a>
                <a href="#" class="w3-bar-item w3-button">Reverse Alphabetical</a>
            </div>
        </div>
    </div>

    <div class="w3-container" style="padding-left: 250px; padding-right: 250px;">
        <?php 
        if (count($feed->video) < 20) {
            $stop = count($feed->video);
            $num_mod = count($feed->video) - 1;
        }
        else {
            $stop = 20;
            $num_mod = 19;
        }
        for ($i = 0; $i < $stop; $i++) { 
        $num = $i + $num_mod - $i * 2;
        ?>
            <div class="w3-container" style="margin-top:25px;margin-bottom:25px;">
                <div class="w3-light-gray w3-leftbar w3-border-gray">
                    <h1 style="margin-left:5px"><?php echo $feed->video[$num]['title']; ?></h1>
                </div>
                <div class="w3-row">
                    <div class="w3-col" style="width:384px">
                        <video width="384" height="256" class="video-js" data-setup='{}' controls>
                            <source src=<?php echo $feed->video[$num]->path; ?>>
                        </video>
                    </div>
                    <div class="w3-container w3-rest w3-light-gray" style="height:256px">
                        <h4><strong>Author:</strong> <?php echo $feed->video[$num]['author']; ?></h4>
                        <h4><strong>Date Uploaded:</strong> <?php echo $feed->video[$num]->date; ?></h4>
                        <h4><strong>Time Uploaded:</strong> <?php echo $feed->video[$num]->time; ?></h4>
                        <?php $tags = explode(" ", $feed->video[$num]['tags']);?>
                        <h4><strong>Tags:</strong>
                            <span class="w3-tag w3-blue"><?php echo $tags[0] ?></span>
                            <span class="w3-tag w3-blue"><?php echo $tags[1] ?></span>
                            <span class="w3-tag w3-blue"><?php echo $tags[2] ?></span> 
                        </h4>
                        <h4><strong>Rating:</strong> <?php echo $feed->video[$num]['rating'] ?></h4>
                        <div class="w3-button w3-green">Up</div>
                        <div class="w3-button w3-red">Down</div>
                    </div>
                </div>
            </div>
        <?php } ?> 
    </div>

    <div>
        <audio src=<?php echo $feed->audio->sound[0]->path ?> controls>
            Your browser does not support the audio playback.
        </audio>
    </div>
</body>

</html>