<?php 
require_once("./includes/header.php");



	if(isset($_POST['c_drugs'])){
	
	
	

	$q = "SELECT * FROM prescriptions";
    $result = $mysqli -> query($q) or die($mysqli->error);
	
	
		
		 while($prescription = $result->fetch_assoc()): 
			
				 echo $prescription['address']; 
		 endwhile;
?>
<form>
<button type="button" id="c_drugs">Consume Drugs</button>
</form>
	

	




		