<?php
include "includes/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ===== BOX ICONS ===== -->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <!-- ===== CSS ===== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <title>View Details </title>
</head>
<style>
    .form-check-input {
        cursor: pointer;
    }
</style>

<body id="body-pd">
    <?php
    if (!isset($_GET['user']) && !isset($_GET['type'])) {
        header('location: ../');
    }
    include "partials/sidebar.php";
    $db_table = $_GET['type'];
    $userid = $_GET['user'];


    ?>

    <section class="componentContainer">
        <!-- <h1>Component</h1> -->
        <div class="navigation mb-3">
            <span class="rootMenu"><a href="#">Home</a> / </span><Span class="mainMenu"><a href="#">View User's Details</a></Span>
        </div>

        <div class="row">
            <div class="col">
                <h3 style="font-weight: 600;">User's Details</h3>
            </div>
            <!-- <div class="col d-flex flex-row-reverse">
            <a href="category.php" class="btn btn-primary mr-2 ">Add New</a>
        </div> -->


        </div>

        <div class="dataContainer mt-3">
            <div class="row ">
                <div class="col-md-11 col-12 mx-auto">
                    <div class="row">
                        <div class="col-md-7 col-12 px-3">
                            <?php

                            include "./controller/fetchLocation.php";

                            $details_qry = "SELECT * FROM `$db_table` WHERE id = '$userid'";
                            $userData = $DB->RetriveSingle($details_qry);
                            $staus = '';

                            foreach ($userData as $key => $UD) {

                                if ($key == 'psw' || $key == 'token' || $key == 'id') {
                                    continue;
                                }
                                if ($key == 'state') {
                                    echo '<p class="ms-3 m-0 detailsItem text-capitalize"><strong>' . $key . ' : </strong> ' . fetch_state($UD, $DB) . '</p>';
                                    continue;
                                }
                                if ($key == 'district') {
                                    echo '<p class="ms-3 m-0 detailsItem text-capitalize"><strong>' . $key . ' : </strong> ' . fetch_district($UD, $DB) . '</p>';
                                    continue;
                                }
                                if ($key == 'block') {
                                    echo '<p class="ms-3 m-0 detailsItem text-capitalize"><strong>' . $key . ' : </strong> ' . fetch_block($UD, $DB) . '</p>';
                                    continue;
                                }
                                if ($key == 'status') {
                                    $staus = $UD;
                                }
                                if ($key == 'date') {
                                    echo '<p class="ms-3 m-0 detailsItem text-capitalize"><strong>Joined at : </strong> ' . $UD . '</p>';
                                    continue;
                                }
                                echo '<p class="ms-3 m-0 detailsItem text-capitalize"><strong>' . $key . ' : </strong> ' . $UD . '</p>';
                            }



                            ?>

                        </div>
                        <div class="col-md-5 col-12 px-3">
                            <form action="#" method="post" id="statusForm">

                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                <input type="hidden" name="type" value="<?php echo $db_table; ?>">

                                <h5>Status</h5>
                                <div class="form-check detailsItem">
                                    <input class="form-check-input" type="radio" name="status" value="active" <?php if ($staus == 'active') echo 'checked'; ?>>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Active
                                    </label>
                                </div>

                                <div class="form-check detailsItem">
                                    <input class="form-check-input" type="radio" name="status" value="block" <?php if ($staus != 'active') echo 'checked'; ?>>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Block
                                    </label>
                                </div>

                                <button id="status-btn" class="btn btn-primary mt-4">Submit</button>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <?php
    include "partials/footer.php";
    ?>

    <!--===== MAIN JS =====-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="http://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

            $('#status-btn').click(function(e) {
                e.preventDefault();
                let data = $('#statusForm').serializeArray();
                // console.log(data);
                // console.log(data[2]);
                // console.log(data[2].value);


                let msg = "Once Blocked, User Can't able to chat with other";
                if (data[2].value !== 'block') {
                    msg = "Once Active, User can interact with other Members";
                }

                willDelete = false;

                swal({
                        title: "Are you sure?",
                        text: msg,
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: "controller/changeStatus.php",
                                type: "POST",
                                data: data,

                                success: function(data) {
                                    console.log(data);
                                }
                            })





                            swal("Poof! User was Blocked!", {
                                icon: "success",
                            });

                        } else {
                            alert("cancel");
                        }
                    });






            })

        });
    </script>
    <script src="assets/js/main.js"></script>

</body>

</html>