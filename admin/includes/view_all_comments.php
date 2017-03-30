<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In Response to</th>
            <th>Date</th>
            <th>Approve</th>
            <th>Unapprove</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>

        <?php viewAllComments(); ?>
        
    </tbody>
</table>

<?php 

    if (isset($_GET['approve'])){
        $the_comment_id = $_GET['approve'];
        $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $the_comment_id ";
        $approve_comment_query = mysqli_query($connect, $query);
        header("Location: comments.php");
    }

    if (isset($_GET['unapprove'])){
        $the_comment_id = $_GET['unapprove'];
        $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = $the_comment_id ";
        $unnaprove_comment_query = mysqli_query($connect, $query);
        header("Location: comments.php");
    }

    if (isset($_GET['delete'])){
        $the_comment_id = $_GET['delete'];
        $query = "DELETE FROM comments WHERE comment_id = {$the_comment_id} ";
        $delete_query = mysqli_query($connect, $query);
        header("Location: comments.php");
    }

?> 