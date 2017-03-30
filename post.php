<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php include "functions.php" ?>

    <!-- Navigation -->

    <?php include "includes/navigation.php" ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <?php singlePost(); ?>

                <!-- Blog Comments -->
                

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form" action="" method="post">
                         <div class="form-group">
                            <label for="Author">Author</label>
                            <input type="text" name="comment_author" class="form-control">
                        </div>
                         <div class="form-group">
                            <label for="Email">Email</input>   
                            <input type="email" name="comment_email" class="form-control">
                        </div>
                         <div class="form-group">
                            <label for="Comment">Your Comment</label>
                            <textarea class="form-control" name="comment_content" rows="3"></textarea>
                        </div>

                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
             
                <?php postComments(); ?>
                               
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php" ?>

        </div>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php" ?>