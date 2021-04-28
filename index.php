<?php require_once("./includes/header.php")?>
<?php
//Patient details already registered
//Anytime when Save is clicked on form with all required parameters
if(isset($_POST['save'])){
    $patient_id = $_SESSION['id'];
    $fill_date = $_POST['fill_date'];
    $last_date = $_POST['last_date'];
    $drug_id_list=$_POST['drug_id_list'];
    $drug_no_list=$_POST['drug_no_list'];
    $address=$_POST['address'];
    $prescriber=$_POST['prescriber'];
    $drug_no_array=explode('|',$drug_no_list);
    $drug_id_array=explode('|',$drug_id_list);
    $no=count($drug_no_array);
    for($i=1;$i<$no;$i++){
        $drug_id=$drug_id_array[$i];
        $quantity=$drug_no_array[$i];
        $q = "INSERT INTO `prescriptions` (`patient_id`, `drug_id`, `prescription_date`, `address`, `prescriber`, `quantity`, `end_date`) VALUES ('$patient_id', '$drug_id', '$fill_date', '$address;', '$prescriber', '$quantity', '$last_date');";
        $mysqli -> query($q) or die($mysqli->error);
        $message="Successfully saved prescription";
    }
    if($i==1){
        $message="Nothing to save";
    }
}

//when id is typed and rest of he fields fill in automatically
else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $q = "SELECT * FROM patients WHERE id=$id;";
    $result = $mysqli -> query($q) or die($mysqli->error);
    if ($result) {
        if($result -> num_rows==1){
            $_SESSION['id']=$id;
        }
        else {
            $message = "Cannot find a patient with id $id";
        }
    }
    else {
        echo "Some error has occurred";
    }
}
//if name is entereed rest of the information gets automatically filled in
else if (isset($_GET['full_name'])) {
    $full_name = $_GET['full_name'];
    $q = "SELECT id FROM patients WHERE concat_ws(' ',first_name,last_name) like '%$full_name%';";
    $result = $mysqli -> query($q) or die($mysqli->error);
    if ($result) {
        if($result -> num_rows==1){
            $row=$result->fetch_assoc();
            $_SESSION['id']=$row['id'];
        }
        else {
            $message = "Cannot find a patient with name $full_name";
        }
    }
    else {
        echo "Some error has occurred";
    }
}
//Clear button on form
if(isset($_SESSION['id'])){
    if(isset($_POST['clear'])){
        session_destroy();
        header('Location: '.$_SERVER['REQUEST_URI']);
    } else {
        $id = $_SESSION['id'];
        $q = "SELECT * FROM patients WHERE id=$id;";
        $result = $mysqli -> query($q) or die($mysqli->error);
        $patient = $result->fetch_assoc();
    }
    
}



//select patient details 
//select drug details
$q = "SELECT id FROM patients";
$allpatients = $mysqli -> query($q) or die($mysqli->error);
$q = "SELECT first_name, last_name FROM patients";
$allpatients2 = $mysqli -> query($q) or die($mysqli->error);
$q = "SELECT id, drug_name FROM drugs";
$alldrugs = $mysqli -> query($q) or die($mysqli->error);
require_once('./includes/message.php')
?>

<h2>New Prescription</h2>
<div class="row">
    <div class="row">
        <div class="mb-3 col-6">
            <label for="patient-id" class="form-label">Patient ID</label>
            <form method="GET" action="">
                <div class="input-group">
                    <span class="input-group-text"><img src="assets/icons/person-fill.svg" alt=""></span>

                    <input list="patient_ids" class="form-control" id="patient-id" name="id" type="number"
                        value="<?php if(isset($_SESSION['id'])) { echo $_SESSION['id'];} ?>">
                    <datalist id="patient_ids">
                        <?php while($row=$allpatients->fetch_assoc()){ ?>

                        <option value="<?php echo $row['id']?>"></option>

                        <?php } ?>
                    </datalist>
                    <button class="btn btn-outline-secondary" type="submit" id="search-ids">Search</button>
                </div>
            </form>
        </div>
        <div class="mb-3 col-6">
            <label for="patient-name" class="form-label">Patient Name</label>
            <form>
                <div class="input-group">
                    <span class="input-group-text"><img src="assets/icons/person-fill.svg" alt=""></span>
                    <input type="text" list="patient_names" class="form-control" id="patient-name" name="full_name"
                        value="<?php if(isset($_SESSION['id'])) { echo $patient['first_name']." ".$patient['last_name'];} ?>">
                    <datalist id="patient_names">
                        <?php while($row=$allpatients2->fetch_assoc()){ ?>

                        <option value="<?php echo $row['first_name']." ".$row['last_name']?>"></option>

                        <?php } ?>
                    </datalist>
                    <button class="btn btn-outline-secondary" type="submit" id="search-name">Search</button>
                    <!--<span class="input-group-text"><img src="assets/icons/search.svg" alt=""></span>-->
                </div>
            </form>
        </div>
    </div>
    <form class="row" method="POST" action="index.php">
        <div class="mb-3 col-4">
            <label for="fill-date" class="form-label">Fill Date</label>
            <div class="input-group">
                <span class="input-group-text"><img src="assets/icons/calendar.svg" alt=""></span>
                <input type="text" class="form-control datepicker" id="fill_date" name="fill_date" readonly
                    style="background-color: white;">
            </div>
        </div>
        <div class="mb-3 col-4">
            <label for="original-date" class="form-label">Original Date</label>
            <div class="input-group">
                <span class="input-group-text"><img src="assets/icons/calendar.svg" alt=""></span>
                <input type="text" class="form-control datepicker" id="original_date" name="original_date"
                    placeholder="Date" readonly style="background-color: white;">
            </div>
        </div>
        <div class="mb-3 col-4">
            <label for="last-date" class="form-label">Last Date</label>
            <div class="input-group">
                <span class="input-group-text"><img src="assets/icons/calendar.svg" alt=""></span>
                <input type="text" class="form-control datepicker" id="last_date" name="last_date" placeholder="Date"
                    readonly style="background-color: white;" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <div class="input-group">
                <span class="input-group-text"><img src="assets/icons/address.svg" height="20px" alt=""></span>
                <textarea class="form-control" id="address" name="address"
                    rows="3"><?php if(isset($_SESSION['id'])) { echo $patient['address'];} ?></textarea>

            </div>
        </div>

        <div class="mb-3 col-6">
            <label for="prescriber" class="form-label">Prescriber</label>
            <div class="input-group">
                <span class="input-group-text"><img src="assets/icons/person-fill.svg" alt=""></span>
                <input type="text" class="form-control" id="prescriber" name="prescriber" placeholder="">
            </div>
        </div>
		<!-- Modal for currently consuming drugs -->
        <div class="mb-3 col-4">
			<div class="input-group">
				<button class="btn btn-outline-secondary" class='form-control' type="button"  name="consuming_drugs"
				  onclick="document.getElementById('id01').style.display='block'">Consuming drugs</button>
			</div>
				  
        </div>
		
		<!-- Modal -->
		<div id="id01">
			<span onclick="document.getElementById('id01').style.display='none'">&times;</span>
			
			</div>
			
			
	
		
        <div class="mb-3 col-6">
            <label for="drug" class="form-label">Drug</label>
            <select name="drug" class="form-control" id="drug" style="height: 50px !important;">
                <option disabled value selected> Search </option>
                <?php  while($row=$alldrugs->fetch_assoc()){ ?>

                <option value="<?php echo $row['id'];?>"> <?php echo $row['drug_name'];?></option>

                <?php } ?>
            </select>
        </div>
        <div class="mb-3 col-6">
            <label for="quantity" class="form-label">Quantity</label>
            <div class="input-group">
                <select id="quantity" name="quantity" class="form-control">
                    <option disabled selected value> 0 </option>
                    <?php
                for($i=1;$i<50;$i++){
                    ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="add-button"
                        onclick="handleAdd()">Add</button>
                    <button class="btn btn-outline-secondary" type="button" id="clear-button"
                        onclick="handleClear()">Clear</button>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="prescribed-med" class="form-label">Prescribed Medicines</label>
            <div class="input-group">
                <span class="input-group-text"><img src="assets/icons/prescribed.svg" height="20px"></span>
                <textarea class="form-control" id="prescribed-med" rows="3" disabled
                    style="background-color: white;"></textarea>
            </div>
        </div>

        <div class="mb-3">
            <input class="btn btn-secondary" type="submit" name='save' id='save' value='Save' <?php 
            if(!isset($_SESSION['id'])) 
            { 
                echo "disabled"; 
            } ?>>
            <input class="btn btn-secondary" type="submit" name="clear" value="Clear Form">
        </div>
        <input type="hidden" id="drug_id_list" name="drug_id_list" value="">
        <input type="hidden" id="drug_no_list" name="drug_no_list" value="">
    </form>
</div>
<script>
$(
    function() {
        document.getElementById("original_date").value =
            "<?php if(isset($_SESSION['id'])) { echo $patient['original_date'];} ?>";
        document.getElementById("fill_date").value = new Date().getFullYear() + '-' + ("0" + (new Date()
            .getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
        document.getElementById("last_date").value = new Date().getFullYear() + '-' + ("0" + (new Date()
            .getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
    }
)
$(document).ready(function() {
    $('#drug').select2();
});

function handleAdd() {
    if (document.getElementById("quantity").value === '' || document.getElementById("drug").value === '') {
        alert("Please select a drug and its quantity");
    } else {
        if (document.getElementById("prescribed-med").value !== "") {
            document.getElementById("prescribed-med").value = document.getElementById("prescribed-med").value + ", ";
        }
        document.getElementById("prescribed-med").value =
            `${document.getElementById("prescribed-med").value} ${$("#drug option:selected").text()} (${document.getElementById("quantity").value})`;
        document.getElementById("drug_id_list").value =
            `${document.getElementById("drug_id_list").value}|${document.getElementById("drug").value}`;
        document.getElementById("drug_no_list").value =
            `${document.getElementById("drug_no_list").value}|${document.getElementById("quantity").value}`;
        //document.getElementById("quantity").value = '';
        //document.getElementById("drug").value = '';
    }

}

function handleClear() {
    document.getElementById("prescribed-med").value = '';
}


	
</script>
<?php require_once("./includes/footer.php")?>