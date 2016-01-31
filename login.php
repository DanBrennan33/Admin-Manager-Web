<?php /* Subject Code and Section (eg. IPC144A, OOP244B, etc.)
Daniel Brennan	
June 24, 2015

Student Declaration

I/we declare that the attached assignment is my/our own work in
accordance with Seneca Academic Policy. No part of this assignment
has been copied manually or electronically from any other source
(including web sites) or distributed to other students.

Name: Daniel Brennan

Student ID: 020 194 114
*/ ?>
<?php include("library.php"); ?>
<?php
    session_start();
    if(isset($_SESSION['username'])) {
        header("Location: view.php");
    }
    https("login.php");
?>
<?php
    $flag = false;
    $login_error="";
    //$user="";
    //$pass="";
    if($_POST && (!$_GET)) {
        if(!empty($_POST['user']) && !empty($_POST['pass'])) {
            $link = new DBlink();
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $user = escape($user);
            $pass = escape($pass);
            if(CRYPT_MD5 == 1) {
                $encryptuser=crypt($user, "$1$1p0rHF1b$");
                $encryptpass=crypt($pass, "$1$1p0rHF1b$");
            }
            $flag = true;
        } else {
            $login_error = "Please provide login information.";
        }
    }
    if($_POST && $flag) {
        $query = "SELECT * FROM users";
        $status = new DBlink();
        $res=$status->set($status->conn(),$query);
            while($r = mysqli_fetch_assoc($res)) {
                if(CRYPT_MD5 == 1) {
                    $cryptUser=crypt($r['username'], "$1$1p0rHF1b$");
                    $cryptPass=crypt($r['password'], "$1$1p0rHF1b$");
                }
                if($cryptUser == $encryptuser && $cryptPass == $encryptpass) {    
                    $_SESSION['username'] = $r['username'];
                    $_SESSION['role'] = $r['role'];
                    header("Location: view.php");
                }
                else {
                    $flag = false;
                    $login_error="Invalid username or password";
                }        
            }
    }
    if((!$_POST) && $_GET) {
        $user = $_GET['f'];
        $query = "select * from users";
        $status = new DBlink();
        $res=$status->set($status->conn(),$query);
        while($r = mysqli_fetch_assoc($res)) {
            if($r['username'] == $user) {
                $to = $user;
                $subject = "Login - Hint";
                $message = $r['passwordHint'];
                $header = "From:int322_152b03@senecacollege.ca";
                $retval = mail($to,$subject,$message,$header);
            }                  
        } if(empty($_GET['f'])) {
            header("Location: login.php");
        }
    }
    if(isset($_GET['f']) && $_GET) {
    top("Password");          
?>
     <form action="" method="get">
    <table>
            <tr>
                <td>User Name:</td>
                <td><input type='text' name='f'
                    value="<?php if(isset($_GET['f'])) ?>"></td>
            </tr>
            <tr>
                <td><input type='submit' name ='submit'></td>
            </tr>
    </table>
<?php } 
    if (!$flag && !$_GET) {
top("Log In");
?>
    <form action="" method="post">
        <?php echo $login_error; ?>
        <table>
            <tr>
                <td>User Name:</td>
                <td><input type='text' name='user'
                    value="<?php if(isset($_POST['user'])) ?>"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type='password' name='pass'
                    value="<?php if(isset($_POST['pass'])) ?>"></td>
            </tr>
            <tr>
                <td><input type='submit' name ='submit'></td>
            </tr>
            <tr>
                <td><a href="<?="login.php?f=1"?>">Forgot your password?</a></td>
            </tr>
        </table>
    </form>
<?php } ?>
<?php bottom(); ?>