<?php
error_reporting(E_ALL);
    // include the configs / constants for the database connection
    require_once("config/db.php");
    
    // load the login class
    require_once("classes/Login.php");
    
    // create a login object. when this object is created, it will do all login/logout stuff automatically
    // so this single line handles the entire login process. in consequence, you can simply ...
    $login = new Login();
    
    // ... ask if we are logged in here:
    if ($login->isUserLoggedIn() == true) {
        // the user is logged in. you can do whatever you want here.
        // for demonstration purposes, we simply show the "you are logged in" view.
        include("views/logged_in.php");
     
    } else {
        // the user is not logged in. you can do whatever you want here.
        // for demonstration purposes, we simply show the "you are not logged in" view.
        include("views/not_logged_in.php");
    }   
 
    // CREATE FORM HANDLER //
    if(isset($_POST['submit'])){
        
        $db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
        if (!$db_conn){
          die('Could not connect: ' . mysql_error());
        }
            
        $user_name = $_SESSION['user_name'];
        $user_id = $_SESSION['user_id'];
        $field1 = mysql_real_escape_string($_POST['content_11']);
        $field2 = mysql_real_escape_string($_POST['content_12']);
        $pgcontent = $field1.'\n'.$field2;
            
        //Check if user exists in content table, and updatre record.  If not, create new record
        //"SELECT COUNT(1) FROM content WHERE user_id = '$user_id'"
        $usercheck = $db_conn->query("SELECT COUNT(1) FROM content WHERE user_id = '$user_id'");
        
        //var_dump($usercheck);
            
        if($usercheck === TRUE){
            // Use UPDATE - check syntax
            $SQL = "UPDATE `content` SET `content_11`=['$field1'],`content_12`=['$field2'] WHERE `user_id` = '$user_id'";
            //$SQL = "INSERT INTO content (content_11, content_12) VALUES ('$field1', '$field2') WHERE user_id = '$user_id'";
            $result = $db_conn->query($SQL);
            echo("Data Updated."); // TEST        
        }
        
        else {
            $SQL = "INSERT INTO content (user_id, content_11, content_12) VALUES ('".$user_id."', '$field1', '$field2')";
            $result = $db_conn->query($SQL);
            echo("Data Inserted."); //TEST    
        }
        //WRITE CODE TO DISPLAY CONTENTS FROM EACH FIELD
    }
    
?>

<!--CREATE FORM-->
   
<form id="form1" name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <p>
    <label for="content_11">Field 1</label>
    <input type="text" name="content_11" id="content_11" />
  </p>
  <p>
    <label for="content_12">Field 2</label>
    <textarea name="content_12" id="content_12" cols="45" rows="5"></textarea>
  </p>
  <p>Submit
    <input type="submit" name="submit" id="submit" value="Submit" />
  </p>
</form>

