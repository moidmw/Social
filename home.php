<html>
<head>
    <link rel="stylesheet" href="../includes/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../includes/css/bootstrap.min.css">
    <script src="../includes/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="stylesheet.css">
    <?php
    include "connect.php";
    session_start();
    $user_id=$_SESSION['id'];
    ?>
</head>
<!--script for youtube player-->
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <?php

                if(isset($_SESSION['id']) && $_SESSION['start']== true){
                    echo '<a href="index.php" class="btn cred">Logout</a>';
                }
                else{
                    echo '<a href="logout.php" class="btn cred">Login</a>';
                }
                ?>
                <a class="navbar-brand">
                    <a href="home.php"  class="btn home">Home</a>
                </a>
            </div>
        </div>
    </nav>
</header>
<body>
<div class="container-fluid">

<!--    friends and unknown list-->

    <div class="col-md-2 friends">
        <?php
        //counting if any row exists

        $un = true;
        $kn = true;
        $ad = true;
        $wl = true;
        //friend list
        $query = $conn->query("select * from user_info");
        while($row = $query->fetch()) {
            $get_id = $row['id'];
            if ($get_id != $user_id) {
                $count_query = $conn->query("select count(*) from friends where user_id = $user_id and friend_id = $get_id");
                $row = $count_query->fetchColumn();
                if($row == 1){
                    $f_query = $conn->query("select * from friends where user_id = $get_id and friend_id = $user_id");
                    $r_query = $conn->query("select * from friends where user_id = $user_id and friend_id = $get_id");
                    $f_row = $f_query->fetch();
                    $r_row = $r_query->fetch();

                    if ($f_row['accepted'] == 1 && $r_row['accepted'] == 1) {
                        if ($kn) {
                            echo '<h3>Friends</h3>';
                            $kn = false;
                        }
                        $user_query = $conn->query("select * from user_info where id = $get_id");
                        $user_row = $user_query->fetch();
                        echo '<a href="user.php?id=' . $user_row['id'] . '"><p class="name">' . $user_row['firstname'] . ' ' . $user_row['lastname'] . '</p></a>';
                        echo '<p class="">' . $user_row['email'] . '</p>';
                    }
                }
            }
        }
        // pending requests
        $query = $conn->query("select * from user_info");
        while($row = $query->fetch()) {
            $get_id = $row['id'];
            if ($get_id != $user_id) {
                $count_query = $conn->query("select count(*) from friends where user_id = $user_id and friend_id = $get_id");
                $row = $count_query->fetchColumn();
                if($row == 1){
                    $f_query = $conn->query("select * from friends where user_id = $get_id and friend_id = $user_id");
                    $r_query = $conn->query("select * from friends where user_id = $user_id and friend_id = $get_id");
                    $f_row = $f_query->fetch();
                    $r_row = $r_query->fetch();

                    if ($r_row['accepted'] == 0 && $f_row['accepted'] == 1) {
                        if ($ad) {
                            echo '<h3>Request received</h3>';
                            $ad = false;
                        }
                        $user_query = $conn->query("select * from user_info where id = $get_id");
                        $user_row = $user_query->fetch();
                        echo '<a href="user.php?id=' . $user_row['id'] . '"><p class="name">' . $user_row['firstname'] . ' ' . $user_row['lastname'] . '</p></a>';
                        echo '<p class="">' . $user_row['email'] . '</p>';
                    }
                }
            }
        }
        //People you may know
        $query = $conn->query("select * from user_info");
        while($row = $query->fetch()) {
            $get_id = $row['id'];
            if ($get_id != $user_id) {
                $count_query = $conn->query("select count(*) from friends where user_id = $user_id and friend_id = $get_id");
                $row = $count_query->fetchColumn();
                if ($row == 0) {
                    if ($un) {
                        echo '<h3>People you may know</h3>';
                        $un = false;
                    }
                    $user_query = $conn->query("select * from user_info where id = $get_id");
                    $user_row = $user_query->fetch();
                    echo '<a href="user.php?id=' . $user_row['id'] . '"><p class="name">' . $user_row['firstname'] . ' ' . $user_row['lastname'] . '</p></a>';
                    echo '<p class="">' . $user_row['email'] . '</p>';
                    //if no row exists
                }
                else if($row == 1){
                    $f_query = $conn->query("select * from friends where user_id = $get_id and friend_id = $user_id");
                    $r_query = $conn->query("select * from friends where user_id = $user_id and friend_id = $get_id");
                    $f_row = $f_query->fetch();
                    $r_row = $r_query->fetch();

                    if ($r_row['accepted'] == 1 && $f_row['accepted'] == 0) {
                        $user_query = $conn->query("select * from user_info where id = $get_id");
                        $user_row = $user_query->fetch();
                        echo '<a href="user.php?id=' . $user_row['id'] . '"><p class="name">' . $user_row['firstname'] . ' ' . $user_row['lastname'] . '</p></a>';
                        echo '<p class="">' . $user_row['email'] . '</p>';
                    }
                }
            }
        }

        ?>
    </div>
<!--user info -img, name and status update-->
    <div class="col-md-10">
        <div class="row">

            <?php
            $user_query = $conn ->query("select * from user_info where id = $user_id");
            $user_row = $user_query->fetch();
            echo '<div class="col-md-2 dp_box">';
                echo '<a href="dp_change.php?user='.$user_id.'"><img src="img/img1.jpg" class="user_dp"></a>';
            echo '</div>';
            echo '<div class="col-md-10">';
                echo '<p>Hi, '.$user_row['firstname'];
            ?>
            <p>How u doin...</p>
                <form action="status_update.php" method="post" enctype="multipart/form-data">
                    <textarea class="form-control" rows="2" wrap="hard" name="status"></textarea>
                    <input type="file" id="file1" name="file1">
                    <input type="submit" value="Submit" class="btn btn-default">
                </form>
            </div>
        </div>
<!--    Previous posts-->
        <div class="prev_content">
            <?php

            include "connect.php";
            $post_query = $conn->query("select * from status_update where user_id = $user_id");
            while($post_row = $post_query->fetch()) {
                echo '<div class="row post">';
                echo '<div class="col-md-offset-2 col-md-10">';
                echo '<div class="row">';
                echo '<div class="col-md-1">';
//            user image
                echo '<a href="dp_change.php?user=' . $user_id . '"><img src="img/img1.jpg" class="user_dp"></a>';
                echo '</div>';
                echo '<div class="col-md-11">';
//            user content
                echo '<a href="comp_post.php?id=' . $post_row['id'] . '"><img src="' . $post_row['image'] . '" class="post_img">';

                if($post_row['video_link']) {
                    $url = $post_row['video_link'];
                    if (strpos($url, 'youtube') > 0) {
                        $link = "http://www.youtube.com/oembed?url=" . $url . "&format=json";
                    }
                    else if (strpos($url, 'vimeo') > 0) {
                        $link = "http://vimeo.com/api/oembed.json?url=".$url."&maxwidth=480&maxheight=270";
                    }
                    $curl = curl_init($link);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $return = curl_exec($curl);
                    curl_close($curl);
                    $info = json_decode($return);
                    echo '<a><p>' . $info->title . '</p></a>';
                    echo $info->html;
                }
                echo '<p class="post_txt">' . $post_row['status'] . '</p></a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>