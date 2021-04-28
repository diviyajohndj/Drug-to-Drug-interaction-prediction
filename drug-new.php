<?php require_once("./includes/header.php")?>
<h2> Add a new drug </h2>
<form action="drug.php" method="POST">
    <div class="row">
        <div class="mb-3">
            <label for="drug_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="drug_name" name="drug_name" placeholder="" required>
        </div>
        <div class="mb-3">
            <label for="ingredients" class="form-label">Ingredients</label>
            <textarea class="form-control" id="ingredients" name="ingredients" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <button class="btn btn-secondary" name="save">Save</button>
            <a href="drug.php"><button class="btn btn-secondary" type="button">Back</button></a>
        </div>
    </div>
</form>
<?php require_once("./includes/footer.php")?>