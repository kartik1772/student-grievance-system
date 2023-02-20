<?php

require_once '../Shared/process.php';

if (isset($_POST['username']) && isset($_POST['password']) ) 
{
    function test_input($data) 
    {
        $data = trim($data);   //removing the white spaces
        $data = stripslashes($data);  //removing the backslashes
        $data = htmlspecialchars($data);   //this is done to make sure that the hacker doesn't enter external javascript code
        return $data;

    }
    
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    
    if (empty($username)) 
    {
        //if username is empty then it is re-directed to login page with an error message
        header("Location: login.php?error=User Name is Required");
    }
    else if (empty($password)) 
    {
         //if passwword is empty then it is re-directed to login page with an error message
        header("Location: login.php?error=Password is Required");
    }
    else 
    {
        // Hashing the password  since, in the database also we had stored the hashed value password and not the original password of the user
        $password = md5($password);
        
        $sql = "SELECT * FROM users WHERE (email='$username' OR registration_id='$username') AND password='$password'";
        $result = $conn->query($sql) or die($conn->error);
        
        
        if (mysqli_num_rows($result) === 1) 
        {
            // the user name must be unique
            $row = mysqli_fetch_assoc($result); //converting the result table in the form of an associative array
            if ($row['password'] === $password && ($row['email'] == $username || $row['registration_id'] == $username)) 
            {
                $_SESSION['registration_id'] = $row['registration_id'];
                
                //if the username and password matches with the entry in the database
                // then we will re-direct the user to the home.php page
                header("Location: home.php");
            }
            else 
            {
                header("Location: login.php?error=Incorect User name or password");
            }
        }
        else 
        {
            header("Location: login.php?error=Incorect User name or password");
        }
        
    }
}
else 
{
	header("Location: ../index.php");
}

?>