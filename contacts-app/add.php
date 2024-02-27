<?php
  require "database.php";
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    return;
  }
  $error = null;

  if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (empty($_POST["name"]) || empty($_POST["phone_number"])){
      $error = "Please fill all the fields.";
    }else if(strlen($_POST["phone_number"]) < 9) {
      $error = "Phone number must be at least 9 characters.";
    }else{
      $name = $_POST["name"];
      $phoneNumber = $_POST["phone_number"];
      $statment = $conn->prepare("INSERT INTO contacts(name, phone_number) VALUES (:name, :phone_number')");
      $statment-> bindParam(":name",$_POST["name"]);
      $statment-> bindParam(":phone_number",$_POST["phone_number"]);
      $statment-> execute();
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
                  <form method="POST" action="add.php">
                   <div class="mb-3">
                     <label for="name" class="form-label">Name</label>
                     <input type="text" class="form-control" id="name" name="name">
                   </div>
                   <div class="mb-3">
                     <label for="phone_number" class="form-label">Phone number</label>  
                     <input type="tel" class="form-control" id="phone_number" name="phone_number">
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
