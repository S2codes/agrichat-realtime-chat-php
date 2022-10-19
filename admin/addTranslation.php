<?php
    include "includes/config.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $keyId = $_POST['key_id'];
        $defaultVal = $_POST['default_val'];
        $eng = $_POST['eng'];
        $hindi = $_POST['hnd'];
        $odia = $_POST['odi'];

        if ($_POST['status'] == 'new') {
            $sql= "INSERT INTO `translation`(`key_id`, `default_val`, `english`, `hindi`, `odia`) VALUES ('$keyId','$defaultVal','$eng','$hindi','$odia')";
        }
        if ($_POST['status'] == "update") {
            $id = $_POST['id'];
            $sql = "UPDATE `translation` SET `key_id`='$keyId',`default_val`='$defaultVal',`english`='$eng',`hindi`='$hindi',`odia`='$odia' WHERE id = $id";
        }


        if ($DB->Query($sql)) {
            echo '<script>
            window.location="../admin/translation";
        </script>';
        }else {
            echo '<script>alert("Something Error Occured")</script>';
        }

    }

    $exits = false;
    if (isset($_GET['status']) && $_GET['status'] == 'update') {
        if (isset($_GET['id'])) {
            
            $exits = true;
            $id = $_GET['id'];
            $q = "SELECT * FROM `translation` WHERE id = $id";
            if ($DB->Query($q) > 0) {
                $data = $DB->RetriveSingle($q);
                $data_key = $data['key_id'];
                $data_default = $data['default_val'];
                $data_english = $data['english'];
                $data_hindi = $data['hindi'];
                $data_odia = $data['odia'];
            }


        }
    }



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

    <title>Add New Translation</title>
</head>

<body id="body-pd">
    <?php
        include "partials/sidebar.php";
    ?>
    <section class="componentContainer">

        <div class="navigation mb-3">
            <span class="rootMenu"><a href="#">Home</a> / </span><Span class="mainMenu"><a href="#">Add New Translation</a></Span>
        </div>

        <div class="row">
            <div class="col">
                <h3 style="font-weight: 600;">Add New Translation</h3>
            </div>

            <!-- <div class="col d-flex flex-row-reverse">
                <a href="addTranslation" class="btn btn-primary mr-4">Add New</a>
            </div>  -->

        </div>

        <div class="dataContainer mt-3">
            <div class="row ">
                <div class="col-md-11 col-12 mx-auto">
                    <pre>
                    
                    </pre>

                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" class="row">

                    <input type="hidden" name="status" value="<?php echo $_GET['status'];?>">
                    <?php
                        if ($exits) {
                            echo '<input type="hidden" name="id" value="'.$_GET['id'].'">';
                        }
                    ?>

                        <div class="col-md-6 col-12">
                            <label for="" class="form-label">Key</label>
                            <input type="text" name="key_id" class="form-control mb-3" placeholder="Enter the key" value="<?php echo $retVal = ($exits) ? $data_key : "" ;?>">
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="" class="form-label">Default Value</label>
                            <input type="text" name="default_val" class="form-control mb-3" placeholder="Enter the default value" value="<?php echo $retVal = ($exits) ? $data_default : "" ;?>">
                        </div>

                        <div class="col-md-12 col-12">
                            <label for="" class="form-label">English</label>
                            <input type="text" name="eng" class="form-control mb-3" placeholder="Enter in English" value="<?php echo $retVal = ($exits) ? $data_english : "" ;?>">
                        </div>
                        <div class="col-md-12 col-12">
                            <label for="" class="form-label">Hindi</label>
                            <input type="text" name="hnd" class="form-control mb-3" placeholder="Enter in Hindi" value="<?php echo $retVal = ($exits) ? $data_hindi : "" ;?>">
                        </div>
                        <div class="col-md-12 co l-12">
                            <label for="" class="form-label">Odia</label>
                            <input type="text" name="odi" class="form-control mb-3" placeholder="Enter in Odia" value="<?php echo $retVal = ($exits) ? $data_odia : "" ;?>">
                        </div>

                        <button class="btn-primary btn btn-lg w-50 mx-auto"> Submit</button>

                    </form>

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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script src="assets/js/main.js"></script>

</body>

</html>