<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS Feed Reader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        a {
            text-decoration: none;
        }
        .post {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <h2 class="my-4 text-center">RSS Feed Reader</h2>
    <div class="p-5 m-3 rounded" style="background-color: #FFF6EB;">
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div class="form text-center">
                <input type="text" class="p-2 w-75 rounded" placeholder="Enter the URL here" name="rss-url" id="rss-url">
                <input type="submit" value="Add" class="btn btn-primary mx-3">
            </div>
        </form>
        <div>
        <?php 
            try {
                if (isset($_POST["rss-url"])) {
                    $url = $_POST["rss-url"];
                    $rss = simplexml_load_file($url);
                } else {
                    echo '<h4 class="text-center m-4">Enter an URL to see posts</h4>';
                }
        
                if(isset($url)){
                    echo '<h4 class="text-center m-4 fs-1">'. $rss->channel->title . '</h4>';

                    foreach ($rss->channel->item as $item) {
                        echo '<div class="post border rounded p-4 my-3">';
                            echo '<h4 class="my-5 text-center"><a href="'. $item->link .'">' . $item->title . "</a></h4>";
                            echo "<p>" . $item->description . "</p>";
                            echo "<p>" . $item->pubDate . "</p><br><hr>";
                        echo '</div>';
                    } 
                }
            } catch (Exception $e) {
            
            }
        ?>
        </div>
    </div>
</body>
</html>
