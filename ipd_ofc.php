<?php

// การเชื่อมต่อฐานข้อมูล MySQL
$servername = "172.16.0.213";
$username = "webcom";
$password = "p@ss10697";
$dbname = "claim";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง stm
$sql = "SELECT 
case 
when MONTHNAME(i.dchdate) = 'October' then 1
when MONTHNAME(i.dchdate) = 'November' then 2
when MONTHNAME(i.dchdate) = 'December' then 3
when MONTHNAME(i.dchdate) = 'January' then 4
when MONTHNAME(i.dchdate) = 'February' then 5
when MONTHNAME(i.dchdate) = 'March' then 6
end as nm,

case 

when i.dchdate BETWEEN '2022-10-01' and '2022-10-31' then concat((year(i.dchdate)+543),' ','ตุลาคม')
when i.dchdate BETWEEN '2022-11-01' and '2022-11-30' then concat((year(i.dchdate)+543),' ','พฤศจิกายน')
when i.dchdate BETWEEN '2022-12-01' and '2022-12-31' then concat((year(i.dchdate)+543),' ','ธันวาคม')

when i.dchdate BETWEEN '2023-01-01' and '2023-01-31' then concat((year(i.dchdate)+543),' ','มกราคม')
when i.dchdate BETWEEN '2023-02-01' and '2023-02-28' then concat((year(i.dchdate)+543),' ','กุมภาพันธ์')
when i.dchdate BETWEEN '2023-03-01' and '2023-03-31' then concat((year(i.dchdate)+543),' ','มีนาคม')
end as mm,
count(i.an) as ใบแจ้งหนี้ทั้งหมด,
count(case when i.debt_price > 0 then i.an end) as ใบแจ้งหนี้ที่มียอด,
count(case when s.total is not null and i.debt_price > 0 then 1 end) as ใบแจ้งหนี้ที่ได้รับSTM,
count(case when s.total is null and i.debt_price > 0 then 1 end) as ไม่ใบแจ้งหนี้ที่ได้รับSTM,
sum(i.debt_price) as ลูกหนี้ทั้งหมด,
sum(s.total) as ยอดเคลมที่ได้รับSTM,
sum(case when s.total is null and i.debt_price > 0 then i.debt_price end) as ยังไม่ได้STM

FROM ipd_ofc i
LEFT JOIN stm_ipd s on i.an = s.an
WHERE i.dchdate BETWEEN '2022-10-01' and '2023-03-31'
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

<?php
    function formatCurrency($amount) {
     return number_format($amount, 2, '.', ',');
    }
?>

<body>
<div class="container">
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
                        <a class="nav-link" href="index.php">OPD กรมบัญชีกลาง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="ipd_ofc.php">IPD กรมบัญชีกลาง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
    <br/> 
	<!-- สร้างตาราง HTML สำหรับ DataTable -->
    <div class="container">
        <a href="uploadipd.php" class="btn btn-info" role="button">นำเข้า STM</a>
        <br>
            <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <tr>
                            <th rowspan="2" colspan="2" style="vertical-align : middle;text-align:center;">เดือน</th>
                            <th colspan="3" bgcolor="yellow" style="vertical-align : middle;text-align:center;">ลูกหนี้ทั้งหมด</th>
                            <th colspan="2" bgcolor="green" style="vertical-align : middle;text-align:center;">ได้รับ STM</th>
                            <th colspan="2" bgcolor="red" style="vertical-align : middle;text-align:center;">ไม่ได้รับ STM</th>
                        </tr>
                            
                            <th>an</th>
                            <th>anที่มียอดทั้งหมด</th>
                            <th>ลูกหนี้ทั้งหมด</th>   
                            <th>an</th>
                            <th>ยอด</th>
                            <th>an</th>
                            <th>ยอด</th>
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
                    <td ><?php echo formatCurrency($row["ลูกหนี้ทั้งหมด"]); ?> </td>
                    <td ><?php echo $row["ใบแจ้งหนี้ที่ได้รับSTM"]; ?> </td>        
                    <td ><?php echo formatCurrency($row["ยอดเคลมที่ได้รับSTM"]); ?> </td>  
                    <td ><?php echo $row["ไม่ใบแจ้งหนี้ที่ได้รับSTM"]; ?> </td>    
                    <td ><?php echo formatCurrency($row["ยังไม่ได้STM"]); ?> </td>        
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
    </div>  
</div>    
</body>
</html>

<?php

// ปิดการเชื่อมต่อฐานข้อมูล MySQL
$conn->close();

?>

