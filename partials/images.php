<?php 
    function images($name, $url, $alt, $username, $email) {
        $userStr = "";
        if ($username && $email):
            $userStr = '<small>
            by <a href="./profile?email=' . $email . '">' . $username .'</a>
            </small>';
        endif;

        echo '<div>
            <img
                src="' . $url . '"
                alt="' . $alt . '" loading="lazy">
            <div class="content">
                <h4 class="font-700">' . $name . '</h4>' .
                $userStr .
            '</div>
        </div>';
    }
?>