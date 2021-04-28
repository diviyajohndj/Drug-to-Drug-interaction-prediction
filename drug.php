<?php require_once("./includes/header.php");

if(isset($_POST['save'])){
    $drug_name=$_POST['drug_name'];
    $ingredients=$_POST['ingredients'];
    $q = "INSERT INTO `drugs` (`drug_name`, `ingredients`) VALUES ('$drug_name', '$ingredients')";
    $result = $mysqli -> query($q) or die($mysqli->error);
    $message = "Successfully added $drug_name to the list";
}

$q = "SELECT * FROM drugs";
if(isset($_POST['search'])){
    $search_term=$_POST['search_term'];
    $q = "SELECT * FROM drugs WHERE drug_name LIKE '%$search_term%';";
}
$result = $mysqli -> query($q) or die($mysqli->error);

require_once("./includes/message.php")
?>

<h2 class="mb-3">Drug</h2>
<form action="" class="row mb-3" method="POST">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="search_term" placeholder="Enter drug name here.." required>
        <button class="btn btn-outline-secondary" type="submit" name="search">Search</button>
    </div>
</form>
<div class="mb-3">
    <a href="drug-new.php">
        <button class="btn btn-secondary">
            New drug
        </button>
    </a>
</div>
<?php if($result -> num_rows > 0){?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Drug ID</th>
            <th scope="col">Drug Name</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        while($row=$result->fetch_assoc())
        {
        ?>
        <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td scope="row"><?php echo $row['id']; ?></td>
            <td><?php echo $row['drug_name']; ?></td>
            <td>
                <button class="btn btn-secondary" data-bs-toggle="modal"
                    data-bs-target="#drug_details<?php echo $row['id']; ?>">Info</button>
                <div class="modal fade" id="drug_details<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content p-3">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Drug Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <strong>Name: </strong>
                                    <em><?php echo $row['drug_name']; ?></em>
                                    <br /> <br />
                                    <strong>Ingredients: </strong>
                                    <em><?php echo $row['ingredients']; ?></em>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php } else { ?>
<p>No results found!</p>
<?php } ?>
<script>

</script>
<?php require_once("./includes/footer.php")?>