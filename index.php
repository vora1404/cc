<?php

// การเชื่อมต่อฐานข้อมูล MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "claim";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง stm
$sql = "SELECT 


case 
when MONTHNAME(o.vstdate) = 'October' then 1
when MONTHNAME(o.vstdate) = 'November' then 2
when MONTHNAME(o.vstdate) = 'December' then 3
when MONTHNAME(o.vstdate) = 'January' then 4
when MONTHNAME(o.vstdate) = 'February' then 5
when MONTHNAME(o.vstdate) = 'March' then 6
end as nm,

case 

when o.vstdate BETWEEN '2022-10-01' and '2022-10-31' then MONTHNAME(o.vstdate)
when o.vstdate BETWEEN '2022-11-01' and '2022-11-30' then MONTHNAME(o.vstdate)
when o.vstdate BETWEEN '2022-12-01' and '2022-12-31' then MONTHNAME(o.vstdate)

when o.vstdate BETWEEN '2023-01-01' and '2023-01-31' then MONTHNAME(o.vstdate)
when o.vstdate BETWEEN '2023-02-01' and '2023-02-28' then MONTHNAME(o.vstdate)
when o.vstdate BETWEEN '2023-03-01' and '2023-03-31' then MONTHNAME(o.vstdate)
end as mm,
count(o.debt_id) as ใบแจ้งหนี้ทั้งหมด,
count(case when o.amount_debt > 0 then o.debt_id end) as ใบแจ้งหนี้ที่มียอด,
count(case when s.amount is not null and o.amount_debt > 0 then 1 end) as ใบแจ้งหนี้ที่ได้รับSTM,
count(case when s.amount is null and o.amount_debt > 0 then 1 end) as ไม่ใบแจ้งหนี้ที่ได้รับSTM,
sum(o.amount_debt) as ลูกหนี้ทั้งหมด,
sum(s.amount) as ยอดเคลมที่ได้รับSTM

 FROM opd_oo o
left JOIN stm s on o.debt_id = s.invno
WHERE o.vstdate BETWEEN '2022-10-01' and '2023-03-31'
GROUP BY nm";
$result = $conn->query($sql);

// กำหนดค่าเริ่มต้นสำหรับ DataTable
//$data = array();
//while($row = $result->fetch_assoc()) {
//    $data[] = $row;
//}
//$json_data = json_encode($data);

?>

<!-- ส่วนของ HTML และ JavaScript สำหรับ DataTable -->
<!DOCTYPE html>
<html>
<head>
	<title>แสดงข้อมูลจาก MySQL database ด้วย DataTable</title>
	<!-- นำเข้าไฟล์ CSS ของ Bootstrap 4 -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <!-- Load jQuery library and DataTables with Buttons extension -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    

	<script type="text/javascript">
        
		$(document).ready( function () {
			$('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                'csv', 'excel', 'pdf', 'print'
                ]
			});
            
		});
	</script>
</head>

<script>
    function showPopup(url) {
      var w = 600;
      var h = 400;
      var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);
      window.open(url, 'popup', 'width='+w+', height='+h+', top='+top+', left='+left);
    }
  </script>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Your Website</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">เกี่ยวกับเรา</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">บริการ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">ติดต่อเรา</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
    <br/> 
	<!-- สร้างตาราง HTML สำหรับ DataTable -->
    <div class="container">
        <a href="upload.php" class="btn btn-info" role="button">นำเข้า STM</a>
        <br>
            <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>เดือน</th>
                        <th>จำนวนใบแจ้งหนี้ทั้งหมด</th>
                        <th>ใบแจ้งหนี้ที่มียอดทั้งหมด</th>
                        <th>ลูกหนี้ทั้งหมด</th>   
                        <th>ใบแจ้งหนี้ที่ได้รับSTM</th>
                        <th>ยอดเคลมที่ได้รับSTM</th>
                        <th>ไม่ใบแจ้งหนี้ที่ได้รับSTM</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    while($row = mysqli_fetch_array($result)) 
                    {
                ?> 
                <tr>
                    <td ><?php echo $row["nm"]; ?> </td>
                    <td ><?php echo $row["mm"]; ?> </td>
                    <td ><?php echo $row["ใบแจ้งหนี้ทั้งหมด"]; ?> </td>
                    <td ><?php echo $row["ใบแจ้งหนี้ที่มียอด"]; ?> </td>
                    <td ><?php echo $row["ลูกหนี้ทั้งหมด"]; ?> </td>
                    <td ><?php echo $row["ใบแจ้งหนี้ที่ได้รับSTM"]; ?> </td>        
                    <td ><?php echo $row["ยอดเคลมที่ได้รับSTM"]; ?> </td>  
                    <td ><?php echo $row["ไม่ใบแจ้งหนี้ที่ได้รับSTM"]; ?> </td>      
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
    </div>  
</body>
</html>

<?php

// ปิดการเชื่อมต่อฐานข้อมูล MySQL
$conn->close();

