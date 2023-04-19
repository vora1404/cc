<!DOCTYPE html>
<html>
<head>
  <title>Upload XML File</title>
</head>
<body>
  <form action="uploadipd.php" method="post" enctype="multipart/form-data">
    <input type="file" name="xmlfile" accept=".xml">
    <input type="submit" value="Upload">
  </form>

  <?php
  $servername = "172.16.0.213";
  $username = "webcom";
  $password = "p@ss10697";
  $dbname = "claim";
  
  // สร้างการเชื่อมต่อฐานข้อมูล
  $conn = new mysqli($servername, $username, $password, $dbname);



  if(isset($_FILES["xmlfile"])) {
    // ตรวจสอบว่าไฟล์ที่อัปโหลดเป็นไฟล์ XML หรือไม่
    $filetype = strtolower(pathinfo($_FILES["xmlfile"]["name"],PATHINFO_EXTENSION));
    if($filetype != "xml") {
      echo "Error: Only XML files are allowed";
    } else {
      // อ่านไฟล์ XML และแปลงเป็น object
      $xml = simplexml_load_file($_FILES["xmlfile"]["tmp_name"]);
      //print_r($xml);
      $imported_count = 0;
      $duplicate_count = 0;
      
      foreach($xml->thismonip as $thismonip) {

        
      
        $id = $thismonip->an;
        $an = $thismonip->an;
        $dchdate = $thismonip->datedsc;

        $amlim = $thismonip->amlim;
        $amreimb = $thismonip->amreimb;
        $adjrw = $thismonip->adjrw;
        $tamreim = $thismonip->tamreim;

        $ramreim = $thismonip->ramreim;
        $pamreim = $thismonip->pamreim;
        $gamlim = $thismonip->gamlim;
        $gamreim = $thismonip->gamreim;


        $total = $thismonip->total;
        $rtotal = $thismonip->rtotal;
        $gtotal = $thismonip->gtotal;
        $rid = $thismonip->rid;

        $date_str = $thismonip->datedsc;
        $datetime = date("Y-m-d H:i:s", strtotime($date_str));


        $sql = "INSERT INTO stm_ipd (id, an, dchdate, amlim, amreimb, adjrw, tamreim, ramreim, pamreim, gamlim, gamreim, total, rtotal, gtotal, rid) VALUES ('$id', $an, '$date_str', '$amlim', '$amreimb', '$adjrw', '$tamreim', '$ramreim', '$pamreim', '$gamlim', '$gamreim', '$total', '$rtotal', '$gtotal', '$rid')";
      
         // รัน SQL query
        if ($conn->query($sql) === TRUE) {
          if ($conn->affected_rows > 0) {
              // เพิ่มจำนวนรายการที่นำเข้าเรียบร้อยแล้ว
              $imported_count++;
              echo "INVNO " . $id . " นำเข้าเรียบร้อย". "<br>" ;
          } else {
              // เพิ่มจำนวนรายการที่มีอยู่แล้วในฐานข้อมูล
              $duplicate_count++;
              echo "AN " . $id . " มีอยู่แล้วในฐานข้อมูล". "<br>" ;
          }
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }
      // แสดงจำนวนรายการที่นำเข้าเรียบร้อยและจำนวนรายการที่มีอยู่แล้วในฐานข้อมูล
      echo "นำเข้าข้อมูลสำเร็จ: " . $imported_count . " รายการ<br>";
      echo "ข้อมูลซ้ำ: " . $duplicate_count . " รายการ";
      
    


    }
  }
  $conn->close();
  ?>
</body>
</html>
