<!DOCTYPE html>
<html lang="en">
<?php
    include "./includes/db.php";
    $DB = new Database();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ===== BOX ICONS ===== -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!-- ===== CSS ===== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <title>Course Enquiries from course.sankarmedia.com </title>
</head>

<body id="body-pd">
    <?php
        include "partials/sidebar.php";
        if (isset($_GET['remove']) && $_GET['remove'] !='') {
            $id = $_GET['remove'];
            $sql = "DELETE FROM `course_msg` WHERE `id` = $id";
            if ($DB->Query($sql)) {
                echo "<script>window.location='course-enquiries.php'</script>";
            }
        }
    ?>
<a href="" target="_blank"></a>

    <section class="componentContainer">
        <!-- <h1>Component</h1> -->
        <div class="navigation mb-3">
            <span class="rootMenu"><a href="#">Home</a> / </span><Span class="mainMenu"><a href="#">All Enquiries</a></Span>
        </div>

        <div class="row">
            <div class="col">
                <h3 style="font-weight: 600;">All Enquiries</h3>
            </div>  
        </div>
       
        <div class="dataContainer mt-3">
            <div class="row ">
                <div class="col-md-11 col-12 mx-auto">
                    <table class="table" id="myTable">
                       <thead>
                           <tr>
                               <th>#</th>
                               <th>Name</th>
                               <th>Email</th>
                               <th>Contact</th>
                               <th>Message</th>
                               <th>Date</th>
                               <th>remove</th>
                           </tr>
                       </thead>
                        <tbody>

                           <?php
                           $sn = 1;
                            $q = "SELECT * FROM `course_msg` ORDER BY `course_msg`.`id` DESC";
                            $enquiries = $DB->RetriveArray($q);
                            foreach ($enquiries as $enq) {
                                echo gettype($enq);
                                $enq_msg = json_decode($enq['msg'], true);
                                echo '<tr>
                                <td>'.$sn.'</td>
                                <td>'.$enq_msg['name'].'</td>
                                <td>'.$enq_msg['mail'].'</td>
                                <td>'.$enq_msg['contact'].'</td>
                                <td>'.$enq_msg['msg'].'</td>
                                <td>'.$enq['date'].'</td>
                                <td><a href="course-enquiries.php?remove='.$enq['id'].'" class="btn btn-danger">Delete</a></td>
                                </tr>';
                                $sn = $sn +1;
                            }

                           ?>
                        </tbody>
                        
                    </table>
                </div>
               
            </div>
        </div>
    </section >


  <?php
      include "partials/footer.php";
  ?>

    <!--===== MAIN JS =====-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
    </script>
    <script src="assets/js/main.js"></script>
    
</body>

</html>