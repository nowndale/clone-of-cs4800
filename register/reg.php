<?php
   // Include config file
   require_once "../dbcon/config.php";
    
   // Define variables and initialize with empty values
   $username = $password = $confirm_password = "";
   $username_err = $password_err = $confirm_password_err = "";
    
   // Processing form data when form is submitted
   if($_SERVER["REQUEST_METHOD"] == "POST"){
    
       // Validate username
       if(empty(trim($_POST["email"]))){
           $username_err = "Please enter a email.";
       } else{
           // Prepare a select statement
           $sql = "SELECT id FROM account WHERE email = ?";
           
           if($stmt = mysqli_prepare($link, $sql)){
               // Bind variables to the prepared statement as parameters
               mysqli_stmt_bind_param($stmt, "s", $param_username);
               
               // Set parameters
               $param_username = trim($_POST["email"]);
               
               // Attempt to execute the prepared statement
               if(mysqli_stmt_execute($stmt)){
                   /* store result */
                   mysqli_stmt_store_result($stmt);
                   
                   if(mysqli_stmt_num_rows($stmt) == 1){
                       $username_err = "This email is already taken.";
                   } else{
                       $username = trim($_POST["email"]);
                   }
               } else{
                   echo "Oops! Something went wrong. Please try again later.";
               }
           }
            
           // Close statement
           mysqli_stmt_close($stmt);
       }
       
       // Validate password
       if(empty(trim($_POST["password"]))){
           $password_err = "Please enter a password.";     
       } elseif(strlen(trim($_POST["password"])) < 6){
           $password_err = "Password must have at least 6 characters.";
       } else{
           $password = trim($_POST["password"]);
       }
       
       // Validate confirm password
       if(empty(trim($_POST["password-repeat"]))){
           $confirm_password_err = "Please confirm password.";     
       } else{
           $confirm_password = trim($_POST["password-repeat"]);
           if(empty($password_err) && ($password != $confirm_password)){
               $confirm_password_err = "Password did not match.";
           }
       }
       
       // Check input errors before inserting in database
       if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
           $user = $_POST["username"];
           $pass = $_POST["password"];
           $emai = $_POST["email"];
           // Prepare an insert statement

           $sql = "INSERT INTO `account`(`username`, `password`, `email`,`profile`,`country`) VALUES ('$user','$pass','$emai','male.png','Earth')";
           var_dump($sql);
            
           if(mysqli_query($link, $sql))
            { 
            echo "Account register successfully."; 
            // Redirect user to welcome page
            header("location: ../login");
             } 
                
               
           }
       
       // Close connection
       mysqli_close($link);
   }
   ?>