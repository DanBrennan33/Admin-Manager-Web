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
	https("add.php");
	if(isset($_SESSION['search']))
	unset($_SESSION['search']);
?>
<?php
        $flag = true;
        $iName_error = "";
        $desc_error = "";
        $sCode_error = "";
        $cost_error = "";
        $sPrice_error = "";
        $noHand_error = "";
	$rPnt_error = "";
        if($_POST) {
            if(empty($_POST['iName']) || preg_match('/^ *$/', $_POST['iName'])) {
				$iName_error = "Inventory Name may not be empty.";
				$flag = false;
			}
			else if(!preg_match("/^[A-Z0-9 \:\;\-\,\']+$/xi",$_POST['iName'])) {
				$iName_error = "Inventory Name may contain letters, spaces, colons, semi-colons, dashes, commas, apostrophes or numeric characters.";
				$flag = false;
			}
			if(empty($_POST['desc'])|| preg_match('/^ *$/', $_POST['desc'])) {
				$desc_error = "Description may not be empty.";
				$flag = false;
			}
			else if(!preg_match('/^[\r\nA-Z0-9 \.\,\'\-]+$/xi',$_POST['desc'])) {
				$desc_error = "Description may contain letters, digits, periods, commas, apostrophes, dashes or newlines.";
				$flag = false;
			}
			if(empty($_POST['sCode'])|| preg_match('/^ *$/', $_POST['sCode'])) {
				$sCode_error = "Supplier Code may not be empty.";
				$flag = false;
			}
			else if(!preg_match('/^[A-Z0-9 -]+$/i',trim($_POST['sCode']))) {
				$sCode_error = "Supplier Code may contain letters, spaces, numeric characters, or dashes.";
				$flag = false;
			}
			if(empty($_POST['cost'])|| preg_match('/^ *$/', $_POST['cost'])) {
				$cost_error = "Cost may not be empty.";
				$flag = false;
			}
			else if(!preg_match('/^([0-9]+\.[0-9]{2})$/',$_POST['cost'])) {
				$cost_error = "Cost may contain monetary amounts only. (ex. '123.45')";
				$flag = false;
			}
			if(empty($_POST['sPrice'])|| preg_match('/^ *$/', $_POST['sPrice'])) {
				$sPrice_error = "Selling Price may not be empty.";
				$flag = false;
			}
			else if(!preg_match('/^([0-9]+\.[0-9]{2})$/',$_POST['sPrice'])) {
				$sPrice_error = "Selling Price may contain monetary amounts only. (ex. '123.45')";
				$flag = false;
			}
			if(preg_match('/ +^$/', $_POST['noHand'])|| preg_match('/^ *$/', $_POST['noHand'])) {
				$noHand_error = "Number On Hand may not be empty.";
				$flag = false;
			}
			else if(!preg_match('/^(\d)+$/',$_POST['noHand'])) {
				$noHand_error = "Number On Hand may contain digits only.";
				$flag = false;
			}
			if(preg_match('/ +^$/', $_POST['rPnt'])|| preg_match('/^ *$/', $_POST['rPnt'])) {
				$rPnt_error = "Reorder Point may not be empty.";
				$flag = false;
			}
			else if(!preg_match('/^(\d)+$/',$_POST['rPnt'])) {
				$rPnt_error = "Reorder Point may contain digits only..";
				$flag = false;
			}
		}
		if ($_POST && $flag) {
			$iN = $_POST['iName'];
			$d = $_POST['desc'];
			$sC = $_POST['sCode'];
			$c = $_POST['cost'];
			$sP = $_POST['sPrice'];
			$noH = $_POST['noHand'];
			$rP = $_POST['rPnt'];
			if(!empty($_POST['obOrd'])) { $bO = "Y"; }
			else {$bO = "N"; }
			if(isset($_GET['deleted']) == 'y') {
				$del = "y";	
			} else { $del = "n"; }
			if(isset($_GET['id'])) {
				$id = $_GET['id'];
				$query= 'UPDATE inventory
					 SET itemName= "' . escape($iN) . '",
					  description= "' . escape($d) . '",
					  supplierCode= "' . escape($sC) . '",
					  cost= "' . escape($c) . '",
					  price= "' . escape($sP) . '",
					  onHand= "' . escape($noH) . '",
					  reorderPoint= "' . escape($rP) . '",
					  backOrder= "' . escape($bO) . '",
					  deleted= "' . escape($del) . '"
					  WHERE id="' . escape($id) . '"';
					
			} else {
			$query = 'insert into inventory set 
					  itemName= "' . escape($iN) . '",
					  description= "' . escape($d) . '",
					  supplierCode= "' . escape($sC) . '",
					  cost= "' . escape($c) . '",
					  price= "' . escape($sP) . '",
					  onHand= "' . escape($noH) . '",
					  reorderPoint= "' . escape($rP) . '",
					  backOrder= "' . escape($bO) . '",
					  deleted= "' . escape($del) . '"'; 
			}
			$status = new DBlink();
			$res=$status->set($status->conn(),$query);
			if ($res) {
				header("Location: view.php");
			} else {
				echo "Your query didn't work.  <a href=add.php>try again</a>";
			}
		} else {
?>
<?php
    top("Brennan Films Add"); ?>
    <h5>*All fields mandatory except "On Back Order"</h5>	
	<form action="" method="post">
		<table>
		<tr>
			<?php if (isset($_GET['id'])) { ?>
				<td>ID:</td>
				<td><input readonly="readonly" value="<?= $_GET['id']; ?>"></td>	
			<?php
				$row = escape($_GET['id']);
				$query = "SELECT * FROM inventory WHERE id = ".$row;
				$status = new DBlink();
				$res=$status->set($status->conn(),$query);
				while ($r = mysqli_fetch_assoc($res)) {
					$_POST['iName']= $r['itemName'];
					$_POST['desc']= $r['description'];
					$_POST['sCode']= $r['supplierCode'];
					$_POST['cost']= $r['cost'];
					$_POST['sPrice']= $r['price'];
					$_POST['noHand']= $r['onHand'];
					$_POST['rPnt'] = $r['reorderPoint'];
					if($r['backOrder']=='y')
					$_POST['obOrd']= $r['backOrder'];
				}
			} ?>
		</tr>
		<tr>
			<td>Item Name:</td>
			<td><input type='text' name='iName'
				value="<?php if(isset($_POST['iName']))
				{ echo htmlentities($_POST['iName']); } ?>"></td>
				<td><?php echo $iName_error; ?></td>
		</tr>
		<tr>
			<td>Description:</td>
			<td><textarea name='desc' rows="4" cols="30"><?php if(isset($_POST['desc'])) {echo htmlentities($_POST['desc']);} ?></textarea></td>
				<td><?php echo $desc_error; ?></td>
		</tr>	
		<tr>
			<td>Supplier Code:</td>
			<td><input type='text' name='sCode'
				value='<?php if(isset($_POST['sCode']))
				{ echo htmlentities($_POST['sCode']); } ?>'></td>
				<td><?php echo $sCode_error; ?></td>
		</tr>
		<tr>	
			<td>Cost:</td>
			<td><input type='text' name='cost'
				value='<?php if(isset($_POST['cost']))
				{ echo htmlentities($_POST['cost']); } ?>'></td>
				<td><?php echo $cost_error; ?></td>
		</tr>
		<tr>
			<td>Selling Price:</td>
			<td><input type='text' name='sPrice'
				value='<?php if(isset($_POST['sPrice']))
				{ echo htmlentities($_POST['sPrice']); } ?>'></td>
				<td><?php echo $sPrice_error; ?></td>
		</tr>
		<tr>
			<td>Number On Hand:</td>
			<td><input type='text' name='noHand'
				value='<?php if(isset($_POST['noHand']))
				{ echo htmlentities($_POST['noHand']); } ?>'></td>
				<td><?php echo $noHand_error; ?></td>
		</tr>
		<tr>
			<td>Reorder Point:</td>
			<td><input type='text' name='rPnt'
				value='<?php if(isset($_POST['rPnt']))
				{ echo htmlentities($_POST['rPnt']); } ?>'></td>
				<td><?php echo $rPnt_error; ?></td>
		</tr>
		<tr>
			<td>*On Back Order:</td> 
			<td><input type='checkbox' name='obOrd' value='obOrd'
			<?php if(!empty($_POST['obOrd'])){ echo "CHECKED"; }?>></td>
		</tr>
		<tr>
			<td><input type='submit' name='submit'></td>
		</tr>
		</table>
	</form>
<?php } ?>
<?php bottom(); ?>