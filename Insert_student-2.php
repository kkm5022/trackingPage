<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="fallstyle.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="html2CSV.js" ></script>
	<script type="text/javascript">

	// make tr clickable
	jQuery(document).ready(function($) {
		$(".clickable-row").click(function() {
			var link =  $(this).data("href");
			var w = window.open(link, "popupWindow", "width=600, height=400, scrollbars=yes");
			var $w = $(w.document.body);
			$w.html("<textarea></textarea>");
		});
	});

</script>
</head>
<body>
	<?php
	$id = $_POST["id"];
	$username = 'FALL1';
	$password = 'qqqqqq1!';
	$hostname = '10.1.10.24';
	$dbName = 'TestDB1';

	$serverName = "10.1.10.24\\FALL1";

	$connectionInfo = array( "Database"=>$dbName, "UID" => $username, "PWD" => $password);
	$conn = sqlsrv_connect( $hostname, $connectionInfo);

	
// $sql = "SELECT * FROM student";
// $sql = "SELECT * FROM student WHERE name LIKE 'John' ";

	$sql = "INSERT INTO student(id,name,studentNumber,pro,phoneNumber,email,startdate,LSA,tag,Notes,DocuSign,Cpu,AddtoLed,Ordered,Onhand,LenApp,TimApp,PickUpDate,ShipDate,TrackingNumber,Received,Completed,MSOFFICE,ReturnReceived) values(?,?,?,?
,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	$params = array(
		$_POST["txtId"],
		$_POST["txtName"],
		$_POST["txtStudentNumber"],
		$_POST["txtPro"],
		$_POST["txtPhoneNumber"],
		$_POST["txtEmail"],
		$_POST["txtStartdate"],
		$_POST["txtLSA"],
		$_POST["txtTag"],
		$_POST["txtNotes"],
		$_POST["txtDocuSign"],
		$_POST["txtCpu"],
		$_POST["txtAddtoLed"],
		$_POST["txtOrdered"],
		$_POST["txtOnhand"],
		$_POST["txtLenApp"],
		$_POST["txtTimApp"],
		$_POST["txtPickUpDate"],
		$_POST["txtShipDate"],
		$_POST["txtTrackingNumber"],
		$_POST["txtReceived"],
		$_POST["txtCompleted"],
		$_POST["txtMSOFFICE"],
		$_POST["txtReturnReceived"]
		);
	$stmt = sqlsrv_query($conn,$sql,$params);

	$studentNumber = $_POST["txtStudentNumber"];

	$sql = "SELECT id FROM student WHERE studentNumber=$studentNumber";
	$stmt = sqlsrv_query($conn,$sql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
	$sid = $row[0];

	$sql = "SELECT * FROM reservation order BY id DESC";
	$stmt = sqlsrv_query($conn,$sql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);

	$rid = $row[0]+1;	//reservation id
	$trackingNumber = $row[1];	//tracking number
	$trackingNumber = str_pad($rid,"5","0",STR_PAD_LEFT);
	$trackingNumber =date("ymd").$trackingNumber;
	$date = date("ymd");
	
	$sql = "INSERT INTO reservation VALUES ($rid, $trackingNumber, $sid, $id, $date, null)";
	$stmt = sqlsrv_query($conn,$sql);
	
	$sql = "UPDATE laptop SET studentid=$sid, available='N' WHERE id = $id";
	$stmt = sqlsrv_query($conn,$sql);

	$sql = "UPDATE student SET Ordered='Y', Onhand='Y', PickUpDate=$date, ShipDate=$date, trackingNumber=$trackingNumber, received='Y', completed='N', ReturnReceived=NULL WHERE id = $sid";
	$stmt = sqlsrv_query($conn,$sql);

	if( $stmt === false ) {

	//die(print("error"))
	die( print_r( "Record insert fail"));

	}

	else
	{
	echo "Record insert successfully";

	}

	?>
</body>
</html>