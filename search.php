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

                <?php searchFunctionality(); ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php" ?>

        </div>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php" ?>