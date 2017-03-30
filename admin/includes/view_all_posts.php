
<form action="" method="post">
    <table class="table table-bordered table-hover">
    
        <div id="bulkOptionsContainer" class="col-xs-4">
            <select class="form-control" id="" name="bulk_options">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>

        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>

        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>ID</th>
                <th>Author</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th> 
                <th>Date</th>
                <th>View Post</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>View Count</th>
            </tr>
        </thead>
        <tbody>

        <?php viewAllPosts(); ?>    

        </tbody>
    </table>
</form>

<?php 

    if (isset($_GET['reset'])){
        $the_post_id = $_GET['reset'];
        $query = "UPDATE posts SET post_view_counts = 0 WHERE post_id = " . mysqli_real_escape_string($connect, $_GET['reset']) . " ";
        $reset_query = mysqli_query($connect, $query);
        header("Location: posts.php");
    }

    if (isset($_GET['delete'])){
        $the_post_id = $_GET['delete'];
        $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
        $delete_query = mysqli_query($connect, $query);
        header("Location: posts.php");
    }

?>