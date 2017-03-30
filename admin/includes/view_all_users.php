<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>

        <?php viewAllUsers(); ?>

    </tbody>
</table>

<?php 

    if (isset($_GET['change_to_admin'])){
        $the_user_id = $_GET['change_to_admin'];
        $query = "UPDATE users SET user_role = 'admin' WHERE user_id = $the_user_id ";
        $change_to_admin_query = mysqli_query($connect, $query);
        header("Location: users.php");
    }

    if (isset($_GET['change_to_sub'])){
        $the_user_id = $_GET['change_to_sub'];
        $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = $the_user_id ";
        $change_to_subscriber_query = mysqli_query($connect, $query);
        header("Location: users.php");
    }

    if (isset($_GET['delete'])){
        $the_user_id = $_GET['delete'];
        $query = "DELETE FROM users WHERE user_id = {$the_user_id} ";
        $delete_query = mysqli_query($connect, $query);
        header("Location: users.php");
    }

?> 