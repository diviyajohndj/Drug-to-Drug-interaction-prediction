<?php require_once("./includes/header.php");


if(isset($_POST['save']) && isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $address=$_POST['perm_addr'];
    $dob=$_POST['dob'];
    $mobile=$_POST['mobile'];
    $email=$_POST['email'];
    $gender=$_POST['gender'];
    $known_allergies=$_POST['known_allergies'];
    $q = "UPDATE `patients` SET `first_name` = '$first_name', `last_name` = '$last_name', `address` = '$address', `dob` = '$dob', `mobile` = '$mobile', `gender` = '$gender', `email` = '$email', `known_allergies` = '$known_allergies' WHERE `patients`.`id` = '$id';";
    $result = $mysqli -> query($q) or die($mysqli->error);
    if (mysqli_affected_rows($mysqli) == 1 ) {
        $message="Successfully updated!";
    } else {
        $message="Not updated";
    }
}

if(isset($_POST['delete']) && isset($_SESSION['id'])){
    $id=$_SESSION['id'];
    session_destroy();
    $q="DELETE FROM patients WHERE id='$id'";
    $result = $mysqli -> query($q) or die($mysqli->error);
    $q="DELETE FROM prescriptions WHERE patient_id='$id'";
    $result = $mysqli -> query($q) or die($mysqli->error);
    $message="Deleted patient information successfully";
}

if(isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $q = "SELECT * FROM patients WHERE id=$id;";
    $result = $mysqli -> query($q) or die($mysqli->error);
    $patient = $result->fetch_assoc();
}

require_once('./includes/message.php')
?>

<h2>Patient Information</h2>
<form class="row" method="POST" action='patient-information.php'>
    <div class="mb-3 col-6">
        <label for="patient_id" class="form-label">
            Patient ID
        </label>
        <input type="text" class="form-control" id="patient_id" name="patient_id" placeholder="" readonly
            value="<?php if(isset($_SESSION['id'])) { echo $patient['id'];} ?>">
    </div>
    <div class="mb-3 col-6">

    </div>
    <div class="mb-3 col-6">
        <label for="first_name" class="form-label">
            First Name
        </label>
        <input type="text" class="form-control" id="first_name" name="first_name" placeholder=""
            value="<?php if(isset($_SESSION['id'])) { echo $patient['first_name'];} ?>">
    </div>
    <div class="mb-3 col-6">
        <label for="last_name" class="form-label">
            Last Name
        </label>
        <input type="text" class="form-control" id="last_name" name="last_name" placeholder=""
            value="<?php if(isset($_SESSION['id'])) { echo $patient['last_name'];} ?>">
    </div>
    <div class="mb-3 col-6">
        <label for="perm_addr" class="form-label">
            Permanent Address
        </label>
        <input type="text" class="form-control" id="perm_addr" name="perm_addr" placeholder=""
            value="<?php if(isset($_SESSION['id'])) { echo $patient['address'];} ?>">
    </div>
    <div class="mb-3 col-6">
    </div>
    <div class="mb-3 col-6">
        <label for="dob" class="form-label">Date of birth</label>
        <div class="input-group">
            <span class="input-group-text"><img src="assets/icons/calendar.svg" alt=""></span>
            <input type="input" class="form-control datepicker" id="dob" name="dob" placeholder="Date" readonly
                style="background-color: white;">
        </div>
    </div>

    <div class="mb-3 col-6">
        <label for="mobile" class="form-label">
            Mobile Number
        </label>
        <input type="text" class="form-control" id="mobile" name="mobile" placeholder=""
            value="<?php if(isset($_SESSION['id'])) { echo $patient['mobile'];} ?>">
    </div>

    <div class="mb-3 col-6">
        <label for="gender" class="form-label">
            Gender
        </label>
        <select name="gender" id="gender" class="form-control">
            <option></option>
            <option value="Male"
                <?php if(isset($_SESSION['id']) && ($patient['gender'] == "Male")) { echo "selected";} ?>>Male</option>
            <option value="Female"
                <?php if(isset($_SESSION['id']) && ($patient['gender'] == "Female")) { echo "selected";} ?>>Female
            </option>
        </select>
    </div>

    <div class="mb-3 col-6">
        <label for="email" class="form-label">
            Email
        </label>
        <input type="text" class="form-control" id="email" name="email" placeholder=""
            value="<?php if(isset($_SESSION['id'])) { echo $patient['email'];} ?>">
    </div>

    <div class="mb-3">
        <label for="known_allergies" class="form-label">Known Allergies</label>
        <div class="input-group">
            <textarea class="form-control" id="known_allergies" name="known_allergies"
                rows="3"><?php if(isset($_SESSION['id'])) { echo $patient['known_allergies'];} ?></textarea>
        </div>
    </div>
    <div>
        <button class="btn btn-primary" type="submit" id="save_button" name="save">Save Changes</button>
        <button class="btn btn-danger" type="submit" id="delete_button" name="delete">Delete Patient</button>
    </div>
</form>
<script>
$(
    function() {
        document.getElementById("dob").value =
            "<?php if(isset($_SESSION['id'])) { echo $patient['dob'];} ?>";
    }
)
</script>
<?php require_once("./includes/footer.php")?>