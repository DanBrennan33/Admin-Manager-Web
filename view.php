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
    https("view.php");
    if(isset($_COOKIE["Sorted"])) {
	 $ob = $_COOKIE["Sorted"];
    } else {
	$ob = 'id';
    }
    if (!empty($_GET['ob'])) {
	$ob = $_GET['ob'];
	setcookie("Sorted", $ob, time()+(60*60*24*365)/12, "/");
    }
    if(isset($_GET['dest'])) {
	unset($_SESSION['search']);
    }
    if (isset($_POST['search'])) {
	$_SESSION['search'] = escape($_POST['search']);
    }
    if(!isset($_SESSION['search'])) {
	$query = 'SELECT * FROM inventory ORDER BY '.$ob;
        $status = new DBlink();
	$res=$status->set($status->conn(),$query);
    } else {
	$query = 'SELECT * FROM inventory WHERE description LIKE "%'.$_SESSION['search'].'%" ORDER BY '.$ob;
	$status = new DBlink();
	$res=$status->set($status->conn(),$query);	
    }
    
    //print_r($_COOKIE);
    //echo $query;
    //echo $ob;
    //echo $_POST['search'];
    top('Brennan Films View');
    ?>
    <table border="1">
        <tr>
	    <th><a href="view.php?ob=id">ID</a></th>
	    <th><a href="view.php?ob=itemName">Inventory name</a></th>
	    <th><a href="view.php?ob=description">Description</a></th>
	    <th><a href="view.php?ob=supplierCode">Supplier Code</a></th>
	    <th><a href="view.php?ob=cost">Cost</a></th>
	    <th><a href="view.php?ob=price">Selling Price</a></th>
	    <th><a href="view.php?ob=onHand">Number On Hand</a></th>
	    <th><a href="view.php?ob=reorderPoint">Reorder Point</a></th>
	    <th><a href="view.php?ob=backOrder">On Back Order</a></th>
	    <th><a href="view.php?ob=deleted">Delete/Restore</a></th>
	</tr>
    
<?php if($rowcount=mysqli_num_rows($res)==0) { ?>
    <tr>
	<td colspan='10' align=center>
	    There are zero rows selected.
	</td>
    </tr>
<?php } ?>
<?php while ($r = mysqli_fetch_assoc($res)) { ?>
    <tr>
	<td><a href="<?='add.php?id=' . $r['id'] . '&deleted=' . $r['deleted']?>"><?= $r['id'] ?></a></td>
	<td><?= $r['itemName'] ?></td>
	<td><?= $r['description'] ?></td>
	<td><?= $r['supplierCode'] ?></td>
	<td><?= $r['cost'] ?></td>
	<td><?= $r['price'] ?></td>
	<td><?= $r['onHand'] ?></td>
	<td><?= $r['reorderPoint'] ?></td>
	<td><?= $r['backOrder'] ?></td>
	<td><a href=<?="delete.php?id=" . $r['id'] . "&deleted=" . $r['deleted']?>><?php if($r['deleted'] == 'n') { echo  'Delete'; } else { echo 'Restore'; } ?></a></td>	
	</tr>
<?php } ?>
    </table>
<?php bottom(); ?>