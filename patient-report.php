<?php require_once("./includes/header.php");
if(isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $q = "SELECT * FROM patients WHERE id=$id;";
    $result = $mysqli -> query($q) or die($mysqli->error);
    $patient = $result->fetch_assoc();
    $q = "SELECT drug_id, SUM(quantity) AS drug_count FROM prescriptions WHERE patient_id='$id' GROUP BY drug_id;";
    $prescriptions = $mysqli -> query($q) or die($mysqli->error);
    $q = "SELECT min(prescription_date) AS from_date, max(end_date) AS to_date FROM prescriptions WHERE patient_id=$id;";
    $dates = $mysqli -> query($q) or die($mysqli->error);
    $dates = $dates->fetch_assoc();
}
?>
<?php if(isset($_SESSION['id'])) { ?>
<div id="forprint">
    <h2 class="mb-3">Patient Report</h2>
    <div class="card mb-3">
        <div class="card-header">
            Patient Details
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-3">
                    First Name:
                </div>
                <div class="col-3">
                    <?php if(isset($_SESSION['id'])) { echo $patient['first_name'];} ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3">
                    Last Name:
                </div>
                <div class="col-3">
                    <?php if(isset($_SESSION['id'])) { echo $patient['last_name'];} ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3">
                    Patient ID:
                </div>
                <div class="col-3">
                    <?php if(isset($_SESSION['id'])) { echo $patient['id'];} ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3">
                    Sex:
                </div>
                <div class="col-3">
                    <?php if(isset($_SESSION['id'])) { echo $patient['gender'];} ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3">
                    Birth date:
                </div>
                <div class="col-3">
                    <?php if(isset($_SESSION['id'])) { echo date("j F Y", strtotime($patient['dob']));} ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($prescriptions->num_rows > 0){ ?>
    <div class="card mb-3">
        <div class="card-header">
            Prescribed Medicines
        </div>
        <div class="card-body">
            <?php
                while($prescription=$prescriptions->fetch_assoc()){
                    $drug_id=$prescription['drug_id'];
                    $q = "SELECT drug_name FROM drugs WHERE id='$drug_id';";
                    $res = $mysqli -> query($q) or die($mysqli->error);
                    $drug = $res->fetch_assoc();
                    echo $drug['drug_name']." - ".$prescription['drug_count']."<br>";
                }
            ?>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">
            Prescription Period
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    Start Date:
                    <?php if(isset($_SESSION['id'])) {echo date("j F Y", strtotime($dates['from_date']));} ?>
                </div>
                <div class="col-6">
                    End Date: <?php if(isset($_SESSION['id'])) {echo date("j F Y", strtotime($dates['to_date']));} ?>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?>
    <p>No prescriptions found!</p>
    <?php } ?>
</div>

<button id="print" class="btn btn-secondary" type="button" onclick="printDiv('forprint')">Print</button>
<?php } ?>
<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
<?php if(isset($_GET['print']) && isset($_SESSION['id'])): ?>
$('document').ready(function() {
    setTimeout(function() {
        document.getElementById("print").click();
    }, 1000)
});
<?php endif; ?>
</script>
<?php require_once("./includes/footer.php")?>