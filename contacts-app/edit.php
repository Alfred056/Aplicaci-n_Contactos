<?php
  require "database.php";
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    return;
  }
  $id = $_GET["id"];

  $statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
  $statement->execute([":id" => $id]);

  if($statement->rowCount()==0){
    http_response_code(404);
    echo("HTTP 404 NOT FOUND");
    return;
  }

  $contact = $statement->fetch(PDO::FETCH_ASSOC);

  $error = null;

  if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (empty($_POST["name"]) || empty($_POST["phone_number"])){
      $error = "Please fill all the fields.";
    }else if(strlen($_POST["phone_number"]) < 9) {
      $error = "Phone number must be at least 9 characters.";
    }else{
      $name = $_POST["name"];
      $phoneNumber = $_POST["phone_number"];
      $statement = $conn->prepare("UPDATE contacts SET name = :name, phone_number = :phone_number WHERE id = :id");
      $statement->execute([
        ":id" => $id,
        ":name" => $_POST["name"],
        ":phone_number" => $_POST["phone_number"],
      ]);
      header("Location: home.php");
    }
  }
?>


<?php require "partials/header.php"?>
<main>
      <!-- <div></div> -->
      <div class="container pt-5">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                Add New Contact
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <?php if($error): ?>
                    <p class ="text-danger">
                      <?= $error ?>
                    </p>
                  <?php endif ?>
                  <form method="POST" action="edit.php?id=<?= $contact["id"]?>">
                   <div class="mb-3">
                     <label for="name" class="form-label">Name</label>
                     <input value="<?= $contact["name"]?>" type="text" class="form-control" id="name" name="name">
                   </div>
                   <div class="mb-3">
                     <label for="phone_number" class="form-label">Phone number</label>  
                     <input value="<?= $contact["phone_number"]?>" type="tel" class="form-control" id="phone_number" name="phone_number">
                   </div>
                   <button type="submit" class="btn btn-primary">Submit</button>
                 </form>
                </li>
              </ul>
            </div>    
          </div>
        </div>
      </div>
  </main>   
<?php require "partials/footer.php"?>
