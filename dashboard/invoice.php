<?php
session_start();
require_once('../conn/pdo.php');
include('inactivity.php');

if(!isset($_SESSION['supplier'])) {
	header('location: ../login.php');
}

if(isset($_SESSION['change'])) {
	header('location: changepassword.php');
}

$res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

$row = $res->fetch(PDO::FETCH_ASSOC);

$fullname = $row['firstname']." ".$row['lastname'];


$HR = $conn->query("SELECT * FROM user WHERE usertype = 'HR'");

$hrRow = $HR->fetch(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Invoice</title>
		<link rel="stylesheet" href="../css/invoice.css">
		<link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
		<link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
		<script src="../js/script.js"></script>
		<style>
						
			.buttons{
				display: flex;
				align-items: center;
				justify-content: center;
				margin: 10px;
				margin-top: 60px !important;
			}

			.buttons a {
				display: inline-block;
				font-size: 15px;
				display: flex;
				align-items: center;
				font-weight: bold;
				border: none;
				padding: 5px 7px;
				background: #420a77;
				color: #fff;
				border-radius: 5px; 
				margin-right: 10px;
				text-decoration: none;
			}
			.buttons a i {
				margin-right: 5px;
				font-size: 20px;
			}
			.buttons .printBtn {
				font-size: 15px;
				font-weight: bold;
				border: none;
				padding: 5px 7px;
				background: #00008d;
				color: #fff;
				border-radius: 5px; 
				cursor: pointer;
			}
			.buttons .printBtn i {
				margin-right: 5px;
			}

			.buttons .printBtn:hover {
				cursor: pointer;
				background:  #1717c9;
			}

		</style>
	</head>
	<body>
		<div class="print">
			<header>
				<h1>Invoice</h1>
				<address contenteditable>
					<p><?=$fullname?></p>
					<p>300 B Samora Ave<br>Posta, Dar es salaam</p>
					<p>+255 (<?=substr($row['phone'], 1, 3)?>) <?=substr($row['phone'], 4)?></p>
				</address>
				<span><img alt="" src="../inc/fav.ico"><input type="file" accept="image/*"></span>
			</header>
			<article>
				<h1>Recipient</h1>
				<address contenteditable>
					<p><?=$hrRow['firstname'].' '.$hrRow['lastname']?>,<br>Human Resource M, <br>Swissport Co.</p>
				</address>
				<table class="meta">
					<tr>
						<th><span contenteditable>Invoice #</span></th>
						<td><span contenteditable><?php echo rand(99999,000000) ?></span></td>
					</tr>
					<tr>
						<th><span contenteditable>Date</span></th>
						<td><span contenteditable><?php echo date('F j, Y')?></span></td>
					</tr>
					<tr>
						<th><span contenteditable>Amount Due</span></th>
						<td><span id="prefix" contenteditable>Tzs &nbsp;</span><span>600.00</span></td>
					</tr>
				</table>
				<table class="inventory">
					<thead>
						<tr>
							<th><span contenteditable>Item</span></th>
							<th><span contenteditable>Description</span></th>
							<th><span contenteditable>Rate</span></th>
							<th><span contenteditable>Quantity</span></th>
							<th><span contenteditable>Price</span></th>
						</tr>
					</thead>
					<tbody>
						<!-- <tr>
							<td><a class="cut">-</a><span contenteditable></span></td>
							<td><span contenteditable>Experience Review</span></td>
							<td><span data-prefix>Tzs </span><span contenteditable>150.00</span></td>
							<td><span contenteditable>4</span></td>
							<td><span data-prefix>Tzs </span><span>600.00</span></td>
						</tr> -->
					</tbody>
				</table>
				<a class="add">+</a>
				<table class="balance">
					<tr>
						<th><span contenteditable>Total</span></th>
						<td><span data-prefix>Tzs </span><span>600.00</span></td>
					</tr>
					<tr>
						<th><span contenteditable>Amount Paid</span></th>
						<td><span data-prefix>Tzs </span><span contenteditable>0.00</span></td>
					</tr>
					<tr>
						<th><span contenteditable>Balance Due</span></th>
						<td><span data-prefix>Tzs </span><span>600.00</span></td>
					</tr>
				</table>
			</article>
			<aside>
				<h1><span contenteditable>Additional Notes</span></h1>
				<div contenteditable>
					<p>A finance charge of 1.5% will be made on unpaid balances after 30 days.</p>
				</div>
			</aside>
		</div>
		<div class="buttons">
            <a href="payments.php"><i class="bx bx-undo"></i>Go Back</a>
            <a class="printBtn"><i class="bx bx-printer"></i>Print Report</a>
        </div>
		<script>
			const printBtn = document.querySelector('.printBtn');
			printBtn.addEventListener('click', () => {
				print()
			});
		</script>
	</body>
</html>