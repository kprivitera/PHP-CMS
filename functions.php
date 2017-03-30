<?php 
/*******************************************************************************
                                Helper Functions
********************************************************************************/

//escape strings
function escape_string($string){
    global $connect;
    return mysqli_real_escape_string($connect, $string);
}


/*******************************************************************************
                                 Front End Functions
********************************************************************************/

function blogHomepage(){

    global $connect, $count, $page;

    $per_page = 2;

    if(isset($_GET['page'])){
        $page = escape_string($_GET['page']);
    } else {
        $page = "";
    } 

    if($page == "" || $page == 1){
        $page_1 = 0;
    } else {
        $page_1 = ($page * $per_page) - $per_page;
    }

    $post_query_count = "SELECT * FROM posts";
    $find_count = mysqli_query($connect, $post_query_count);
    $count = mysqli_num_rows($find_count);
    $count = ceil($count / $per_page);

    $query = "SELECT * FROM posts WHERE post_status = 'published' LIMIT $page_1, $per_page "; 
    $result = mysqli_query($connect, $query);

    //check if any posts have published
    $numResults = mysqli_num_rows($result);
    if($numResults == 0){
            echo "<h1>No posts published</h1>";

        } else {
        
        //else display posts with publish
        while($row = mysqli_fetch_assoc($result)){
            //the id is needed to link it for the individual pages
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = substr($row['post_content'], 0,100);
            $post_status = $row['post_status'];
        
            $blogHomepage = <<<DELIMITER
                <h2>
                    <!-- pass the key of the id superglobal for individual post page -->
                    <a href="post.php?p_id={$post_id}">{$post_title}</a>
                </h2>
                <p class="lead">
                    by <a href="author_posts.php?author={$post_author}&p_id={$post_id}">{$post_author}</a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span>{$post_date}</p>
                <hr>
                <a href="post.php?p_id={$post_id}">
                    <img class="img-responsive" src="images/{$post_image}" alt="">
                </a>
                <hr>
                <p><{$post_content}</p>
                <a class="btn btn-primary" href="post.php?p_id={$post_id}">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                <hr>
DELIMITER;
            echo $blogHomepage;
        } 
    }
}

function singlePost(){

    global $connect;

    if (isset($_GET['p_id'])){
        $the_post_id = escape_string($_GET['p_id']);

        //$view_query = "UPDATE posts SET post_view_counts = post_view_counts + 1 WHERE post_id = $the_post_id ";
        //$send_query = mysqli_query($connect, $view_query);

        // if(!$send_query){
        //     die("query failed");
        // }

        $query = "SELECT * FROM posts WHERE post_id = $the_post_id"; 
        $result = mysqli_query($connect, $query);

        while($row = mysqli_fetch_assoc($result)){
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];


            $singleBlogPost = <<<DELIMITER
                <h2>
                    <a href="#">{$post_title}</a>
                </h2>
                <p class="lead">
                    by <a href="index.php">{$post_author}</a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span>{$post_date}</p>
                <hr>
                <img class="img-responsive" src="images/{$post_image}" alt="">
                <hr>
                <p><{$post_content}</p>

                <hr>
                
DELIMITER;
            echo $singleBlogPost;
        } 

    } else {
        header("Location: index.php");
    }
                        
}

function postComments(){

    global $connect;

    if(isset($_GET['p_id'])){

        if(isset($_POST['create_comment'])){

            $the_post_id = escape_string($_GET['p_id']);
            //get all of the post data out
            $comment_author = escape_string($_POST['comment_author']);
            $comment_email = escape_string($_POST['comment_email']);
            $comment_content = escape_string($_POST['comment_content']);

            if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'UNAPROVE', now())";

                $select_comment_query = mysqli_query($connect, $query);

                if(!$select_comment_query){
                    die("failed");
                }

                // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = {$the_post_id} ";
                // $update_comment_count = mysqli_query($connect, $query);
                    
            } else {
                echo "<script>alert('Fields cannot be empty')</script>";
            }

        }

        $the_post_id = $_GET['p_id'];
        //grab the id from the get request in the url
        $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
        $query .= "AND comment_status = 'approved' ";
        //newest comments first
        $query .= "ORDER BY comment_id DESC ";
        $select_comment_query = mysqli_query($connect, $query);

        //confirm($select_comment_query);

        while($row = mysqli_fetch_array($select_comment_query)){
            $comment_date = $row['comment_date'];
            $comment_content = $row['comment_content'];
            $comment_author = $row['comment_author'];
                    

            $postComment = <<<DELIMITER
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">{$comment_author}
                                <small>{$comment_date}</small>
                            </h4>
                            {$comment_content}
                        </div>
                    </div>
DELIMITER;
            echo $postComment;

        }
    }
}


function registration(){

    global $connect, $message;

    if(isset($_POST['submit'])){

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($username) && !empty($email) && !empty($password)){

            $username = mysqli_real_escape_string($connect, $username);
            $email = mysqli_real_escape_string($connect, $email);
            $password = mysqli_real_escape_string($connect, $password);


            $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12) );

            // $query = "SELECT randSalt FROM users";
            // $select_randsalt_query = mysqli_query($connect, $query);
            
            // if(!$select_randsalt_query){
            //     die("Query Failed" . mysqli_error($connect));
            // }
            // $row = mysqli_fetch_array($select_randsalt_query);
            // $salt = $row['randSalt'];
            // $password = crypt($password, $salt);

            $query = "INSERT INTO users (username, user_email, user_password) ";
            $query .= "VALUES('{$username}','{$email}', '{$password}' )";
            $register_user_query = mysqli_query($connect, $query);

            if(!$register_user_query){
                die("Query Failed" . mysqli_error($connect));
            }

            $message = "Your Registration has been Submitted";

        } else {
            $message = "Fields cannot be empty";
        }
    } else {
        $message = "";
        
    }
}

function searchFunctionality(){

    global $connect;

    if(isset($_POST['submit'])){
        $search = escape_string($_POST['search']);
    }

    $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%'";
    $search_query = mysqli_query($connect, $query);

    if(!$search_query){
        die("query fail");
    }

    $count = mysqli_num_rows($search_query);
    
    if ($count == 0){
        echo "<h1>NO RESULT</h1>";
    } else {

        while($row = mysqli_fetch_assoc($search_query)){
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];

            $searchResult = <<<DELIMITER
                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="#">{$post_title}</a>
                </h2>
                <p class="lead">
                    by <a href="index.php">{$post_author}</a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span>{$post_date}</p>
                <hr>
                <img class="img-responsive" src="images/{$post_image} alt="">
                <hr>
                <p><?php echo $post_content ?></p>
                <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                <hr>
DELIMITER;
        echo $searchResult;

        } /* END WHILE LOOP */
                
    }
                
}

/*******************************************************************************
                                 Back End Functions
********************************************************************************/

function users_online(){
    if(isset($_GET['onlineusers'])){

        global $connect;

        if(!$connect){
            session_start();
            include ("../includes/db.php");

            $session = session_id();
            $time = time();
            $time_out_in_seconds = 60;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session' ";
            $send_query = mysqli_query($connect, $query);
            $count = mysqli_num_rows($send_query);

            if($count == NULL){
                mysqli_query($connect, "INSERT INTO users_online(session, time) VALUES ('$session', '$time')");
            } else {
                mysqli_query($connect, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }

            $users_online_query = mysqli_query($connect, "SELECT * FROM users_online WHERE time > '$time_out' ");
            echo $count_user = mysqli_num_rows($users_online_query);
        }
    } //get request isset
}

users_online();

function confirm($result){
    global $connect;
    if(!$result){
        die("QUERY FAILED: " . mysqli_error($connect));
    }
}

function insert_categories(){
    global $connect;
    //were submitting the form
    if(isset($_POST['submit'])){
        //assigning the post value to this variable
        $cat_title = escape_string($_POST['cat_title']);
        //validation - if the cat title has an empty string or if its empty error message
        if($cat_title == "" || empty($cat_title) ){
            echo "This field should not be empty";
        } else {
            //insert the data into the table
            $query = "INSERT INTO categories(cat_title) ";
            $query.= "VALUE('{$cat_title}') ";

            //send the query
            $create_category_query = mysqli_query($connect, $query);

            if(!$create_category_query){
                die("QUERY FAILED");
            }
        }
    }
}


function findAllCategories(){
    global $connect;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connect, $query);
    
    while($row = mysqli_fetch_assoc($select_categories)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        //this says cat_id because whenever it sees id it will delete it, parameters will be passed to the link when it is clicked on
        //so it will add a key and value in a associative array
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}


function deleteCategories() {
    global $connect;
    
    if(isset($_GET['delete'])){
    $delete_cat_id = escape_string($_GET['delete']);
    $query = "DELETE FROM categories WHERE cat_id = {$delete_cat_id} ";
    $delte_query = mysqli_query($connect, $query);
    header("Location: categories.php");
    }
}

function viewAllPosts(){
    global $connect;

    //we're going to grab the post data from the form below
    if(isset($_POST['checkBoxArray'])){
        foreach($_POST['checkBoxArray'] as $postValueId){
            $bulk_options = escape_string($_POST['bulk_options']);

            switch($bulk_options){
                case "published":
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";

                    $update_to_published_status = mysqli_query($connect, $query);
                    confirm($update_to_published_status);

                    break;

                case "draft":
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId} ";

                    $update_to_draft_status = mysqli_query($connect, $query);
                    confirm($update_to_draft_status);

                    break;

                case "delete":
                    $query = "DELETE FROM posts WHERE post_id = {$postValueId} ";

                    $update_to_delete_status = mysqli_query($connect, $query);
                    confirm($update_to_delete_status);

                    break;

                case "clone":

                    $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}' ";
                    $select_post_query = mysqli_query($connect, $query);

                    while($row = mysqli_fetch_array($select_post_query)){
                        $post_title = $row['post_title'];
                        $post_category_id = $row['post_category_id'];
                        $post_date = $row['post_date'];
                        $post_author = $row['post_author'];
                        $post_status = $row['post_status'];
                        $post_image = $row['post_image'];
                        $post_tags = $row['post_tags'];
                        $post_content = $row['post_content'];
                    }
                    
                    $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date,post_image,post_content,post_tags,post_status) ";
                 
                    $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}',now(),'{$post_image}','{$post_content}','{$post_tags}', '{$post_status}') "; 
                    
                    $copy_query = mysqli_query($connect, $query);   

                    if(!$copy_query ) {
                        die("QUERY FAILED" . mysqli_error($connect));
                    }   
                     
                    break;

            }
        }
    }

    $query = "SELECT * FROM posts ORDER BY post_id DESC";
    $select_posts = mysqli_query($connect, $query);
    
    while($row = mysqli_fetch_assoc($select_posts)){
        $post_id = $row['post_id'];
        $post_author = $row['post_author'];
        $post_title = $row['post_title'];
        $post_category_id = $row['post_category_id'];
        $post_status = $row['post_status'];
        $post_image = $row['post_image'];
        $post_tags = $row['post_tags'];
        $post_comment_count = $row['post_comment_count'];
        $post_date = $row['post_date'];
        //$post_view_counts = $row['post_view_counts'];
        echo "<tr>";
        echo "<td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]'' value='$post_id'></td>";
        echo "<td>{$post_id}</td>";
        echo "<td>{$post_author}</td>";
        echo "<td>{$post_title}</td>";
        

        $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";
        $select_categories_id = mysqli_query($connect, $query);

        confirm($select_categories_id);
        
        while($row = mysqli_fetch_assoc($select_categories_id)){
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            echo "<td>{$cat_title}</td>";
        }
        // echo "<td>{$post_category_id}</td>";
        echo "<td>{$post_status}</td>";
        echo "<td><img width='100' src='../images/{$post_image}'></td>";
        echo "<td>{$post_tags}</td>";


        $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
        $send_comment_query = mysqli_query($connect, $query);
        $row = mysqli_fetch_array($send_comment_query);
        $comment_id = $row['comment_id'];
        $count_comments = mysqli_num_rows($send_comment_query);
        echo "<td><a href='comment.php?id=$comment_id'>{$count_comments}</a></td>";


        echo "<td>{$post_date}</td>";
        echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
        echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
        echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete?'); \" href='posts.php?delete={$post_id}'>Delete</a></td>";
        //echo "<td><a href='posts.php?reset={$post_id}'>{$post_view_counts}</a></td>";
        echo "</tr>";
    }
}

function addPosts(){
    global $connect;

    if(isset($_POST['create_post'])){
        $post_title = $_POST['post_title'];
        $post_author = $_POST['post_author'];
        $post_category_id = $_POST['post_category'];
        $post_status = $_POST['post_status'];

        $post_image = $_FILES['image']['name'];
        $post_image_temp = $_FILES['image']['tmp_name'];
        
        $post_tags = $_POST['post_tags'];
        $post_content = $_POST['post_content'];
        $post_date = date('d-m-y');
        // $post_comment_count = 5;

        //move file from the temp location to the location we want
        move_uploaded_file($post_image_temp, "../images/$post_image");

        //now() formts the date
        $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) "; 
        $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}',now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}') "; 
        $create_post_query = mysqli_query($connect, $query);

        confirm($create_post_query);

        $the_post_id = mysqli_insert_id($connect);

        echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View Post </a></p>";
    }

}

function viewAllUsers(){
    global $connect;
    
    $query = "SELECT * FROM users";
    $select_users = mysqli_query($connect, $query);
        
    while($row = mysqli_fetch_assoc($select_users)){
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];

        echo "<tr>";
        echo "<td>{$user_id}</td>";
        echo "<td>{$username}</td>";
        echo "<td>{$user_firstname}</td>";
        echo "<td>{$user_lastname}</td>";
        echo "<td>{$user_email}</td>";
        // $query = "SELECT * FROM users WHERE post_id = $comment_post_id ";
        // $select_post_id_query = mysqli_query($connect, $query);
        // while($row = mysqli_fetch_assoc($select_post_id_query)){
        //     $post_id = $row['post_id'];
        //     $post_title = $row['post_title'];
        //     echo "<td><a href='../post.php?p_id=$post_id'>{$post_title}</a></td>";
        // }
        echo "<td>{$user_role}</td>";
        echo "<td><a href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
        echo "<td><a href='users.php?change_to_sub=${user_id}'>Subscriber</a></td>";
        echo "<td><a href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";   
        echo "<td><a href='users.php?delete={$user_id}'>Delete</a></td>";
        echo "</tr>";
    }
}

function viewAllComments(){
    global $connect;
    $query = "SELECT * FROM comments";
    $select_comments = mysqli_query($connect, $query);
    
    while($row = mysqli_fetch_assoc($select_comments)){
        $comment_id = $row['comment_id'];
        $comment_post_id = $row['comment_post_id'];
        $comment_author = $row['comment_author'];
        $comment_content = $row['comment_content'];
        $comment_email = $row['comment_email'];
        $comment_status = $row['comment_status'];
        $comment_date = $row['comment_date'];

        echo "<tr>";
        echo "<td>{$comment_id}</td>";
        echo "<td>{$comment_author}</td>";
        echo "<td>{$comment_content}</td>";
        
        // $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";
        // $select_categories_id = mysqli_query($connect, $query);

        // confirm($select_categories_id);
        
        // while($row = mysqli_fetch_assoc($select_categories_id)){
        //     $cat_id = $row['cat_id'];
        //     $cat_title = $row['cat_title'];
        //     echo "<td>{$cat_title}</td>";
        // }
        
        echo "<td>{$comment_email}</td>";
        echo "<td>{$comment_status}</td>";

        $query = "SELECT * FROM posts WHERE post_id = $comment_post_id ";
        $select_post_id_query = mysqli_query($connect, $query);
        
        while($row = mysqli_fetch_assoc($select_post_id_query)){
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            echo "<td><a href='../post.php?p_id=$post_id'>{$post_title}</a></td>";
        }

        echo "<td>{$comment_date}</td>";
        echo "<td><a href='comments.php?approve=$comment_id'>Approve</a></td>";
        echo "<td><a href='comments.php?unapprove=$comment_id'>Unapprove</a></td>";   
        echo "<td><a href='comments.php?delete=$comment_id'>Delete</a></td>";
        echo "</tr>";
    }
}

function updateCategories(){
    global $connect;
    
    if(isset($_GET['edit'])){
        //ake sure you catch the data
        $cat_id = escape_string($_GET['edit']);

        $query = "SELECT * FROM categories WHERE cat_id = $cat_id ";
        $select_categories_id = mysqli_query($connect, $query);
        
        while($row = mysqli_fetch_assoc($select_categories_id)){
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            
            if(isset($cat_title)){
                echo "<input value='{$cat_title}' type=\"text\" class=\"form-control\" name=\"cat_title\">";

            } else{
                echo "<input value=\"\" type=\"text\" class=\"form-control\" name=\"cat_title\">";
            }
          
        }
    }  
    //Update Query
    if (isset($_POST['update_category'])){
        $update_cat_title = escape_string($_POST['cat_title']);
        $query = "UPDATE categories SET cat_title = '{$update_cat_title}' WHERE cat_id = {$cat_id} ";
        $update_query = mysqli_query($connect, $query);
        
        if(!$update_query){
            die("query failed");
        }
    }
}

?>

                 