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
<?php
    class DBlink {
	//private $secret;
	//private $link;
	//private $query;
	//private $status;
	
	function conn() { 
	    $secret = file('/home/int322_152b03/secret/topsecret');
	    $link = mysqli_connect(trim($secret[0]),trim($secret[1]),trim($secret[2]),trim($secret[3])) or die(mysqli_connect_error());
	    return $link;
	}
        function set($link, $query) {
	    $status = mysqli_query($link,$query) or die(mysqli_error($link));
	    mysqli_close($link);
	    return $status;
	}
    }
?>
<?php function escape($string) {
	    $link = new DBlink();
	    return mysqli_real_escape_string($link->conn(),$string);	    
} ?>
<?php function startsess($location) { 
    session_start();
    if(!isset($_SESSION['username'])) {
        header("Location: ".$location);
    }
} ?>
<?php function https($https) {
    if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) {
	header("Location: https://zenit.senecac.on.ca/~int322_152b03/assign2/". $https);
    }
} ?>
<?php function top($title) { ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <header>
            <h1>Brennan Films</h1>
	    <nav>
                <?php if(isset($_SESSION['username'])) { ?>
		    <form action="view.php" method="post">
		    <a href="add.php">Add</a>
		    <a href="view.php?ob=&dest">View All</a>
		    Search in description: <input type="text" name="search"
		    value="<?php if(isset($_POST['search']) || isset($_SESSION['search']))
			{ echo htmlentities($_SESSION['search']); }?>">
		    <input type="submit" name="button" value="search">
		    <?php echo htmlentities($_SESSION['username'] .", Role: ". $_SESSION['role']); ?>
		    <a href="logout.php">Log Out</a>
		    </form>
		<?php } ?>
            </nav>
        </header>
        <hr>
<?php } ?>
<?php function bottom() { ?>
        <h6>&copy; 2015 Brennan Films</h6>
    </body>
</html>
<?php } ?>