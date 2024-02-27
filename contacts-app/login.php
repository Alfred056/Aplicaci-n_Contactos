<?php
  require "database.php";
  $error = null;

  if ($_SERVER["REQUEST_METHOD"]=="POST"){
     if(empty($_POST["email"]) || empty($_POST["password"])){
        $error = "Please fill all the fileds.";
     }else{ 
        $statement = $conn ->prepare("SELECT * from users WHERE email = :email LIMIT 1");
        $statement->bindParam(":email",$_POST["email"]);
        $statement->execute();

        if($statement->rowCount()==0){
          $error="Invalid credentials.";
        }else{
          $user=$statement->fetch(PDO::FETCH_ASSOC);
          if(!password_verify($_POST["password"],$user["password"])){
            $error = "Invalid credentials.";
          }else{
            session_start();
            unset($user["password"]);
            $_SESSION["user"] = $user;
            header("Location: home.php");
          }
        }
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
                Login
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <?php if($error): ?>
                    <p class ="text-danger">
                      <?= $error ?>
                    </p>
                  <?php endif ?>
                  <form method="POST" action="login.php">
                   <div class="mb-3">
                     <label for="email" class="form-label">email</label>  
                     <input type="email" class="form-control" id="email" name="email">
                   </div>
                   <div class="mb-3">
                     <label for="password" class="form-label">Password</label>  
                     <input type="password" class="form-control" id="password" name="password">
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
