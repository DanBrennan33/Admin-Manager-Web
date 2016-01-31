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
		startsess("login.php");
		https("delete.php");
		if($_GET['deleted'] == 'n') { $del = 'Y'; }
		else { $del = 'N'; }
		$id = $_GET['id'];
		$query = 'UPDATE inventory
					  set deleted="' . $del . '"
					  where id="' . $id . '"';
		$status = new DBlink();
		$res=$status->set($status->conn(),$query);
			if ($res) {
				header("Location: view.php");
			} else {
				echo "Your query didn't work.  <a href=add.php>try again</a>";
			}
?>