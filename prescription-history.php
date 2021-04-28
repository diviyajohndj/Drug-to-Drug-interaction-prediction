<?php 
require_once("./includes/header.php");
if(isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $q = "SELECT drug_name, prescription_date, end_date, quantity FROM prescriptions JOIN drugs ON prescriptions.drug_id=drugs.id WHERE patient_id=$id;";
    $result = $mysqli -> query($q) or die($mysqli->error);
}
?>

<?php if(isset($_SESSION['id'])) { ?>
<h2 class="mb-3">Prescription History</h2>
<?php if($result->num_rows > 0){ ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr style="background-color:grey; color:white;">
            <th scope="col">Drug name</th>
            <th scope="col">Start date</th>
            <th scope="col">End date</th>
            <th scope="col">Quantity</th>
        </tr>
    </thead>
    <tbody>

        <?php while($prescription = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $prescription['drug_name']; ?></td>
            <td><?php echo date("j/n/Y", strtotime($prescription['prescription_date'])); ?></td>
            <td><?php echo date("j/n/Y", strtotime($prescription['end_date'])); ?></td>
            <td><?php echo $prescription['quantity']; ?></td>
        </tr>
        <?php endwhile; ?>

    </tbody>
</table>
<?php } else { ?>
<p>No presriptions found!</p>
<?php }} ?>
<?php require_once("./includes/footer.php")?>