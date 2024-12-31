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
            session_start();
            if (!isset($_SESSION['feeds'])) {
                $_SESSION['feeds'] = array(); // Initialize session feeds array if not set
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rss-url"])) {
                $url = $_POST["rss-url"];
                array_push($_SESSION['feeds'], $url); // Add the new URL to the session feeds array
            
                // Redirect to the same page to avoid form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } 
            if (empty($_SESSION['feeds'])) {
                echo '<h4 class="text-center m-4">Enter an URL to see posts</h4>';
            } else {
                $allItems = array(); // Array to store all RSS items

                // Loop through all feeds in the session
                foreach ($_SESSION['feeds'] as $feed) {
                    $rss = simplexml_load_file($feed);
                    
                    // Loop through each item in the feed
                    foreach ($rss->channel->item as $item) {
                        $allItems[] = array(
                            'title' => (string)$item->title,
                            'link' => (string)$item->link,
                            'description' => (string)$item->description,
                            'pubDate' => new DateTime((string)$item->pubDate), // Convert pubDate to DateTime object
                            'channelTitle' => (string)$rss->channel->title
                        );
                    }
                }

                // Sort the array by pubDate in descending order
                usort($allItems, function($a, $b) {
                    return $b['pubDate'] <=> $a['pubDate']; // Compare dates, newest first
                });

                // Display sorted feed items
                foreach ($allItems as $item) {
                    echo '<div class="post border rounded p-4 my-3">';
                        echo '<h4 class="text-center my-5 fs-2">'. $item['channelTitle'] . '</h4>';
                        echo '<h4 class="my-5 text-center fs-3"><a href="'. $item['link'] .'">' . $item['title'] . "</a></h4>";
                        echo "<p>" . $item['description'] . "</p>";
                        echo "<p>" . $item['pubDate']->format('D, d M Y H:i:s') . "</p><br><hr>"; // Format date
                    echo '</div>';
                }
            }
        ?>
        </div>
    </div>
</body>
</html>
