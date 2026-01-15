


<?php

?>
<ul style="font-size: 20px;">
    <?php

    $users = DB::table('users')->get();

    foreach ($users as $user) {
        if (Cache::has('user-is-online-' . $user->id)):
            echo "<li style='color: green'>". " " . ucwords($user->name) . "  "."</li>";
            echo '</br>';
        endif;
    }
    ?>
</ul>