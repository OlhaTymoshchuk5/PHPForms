<!--Lab3 
Date:15.11.19
Author: Olha Tymoshchuk
Purpose: to create a simple web form that will store data to the db-->
<?php 
//start  session
session_start(); 

//DB connection
	define("DBHOST", "localhost");
	define("DBDB",   "demo");
	define("DBUSER", "lamp1user");
	define("DBPW", "!Lamp12!");

	function connectDB(){
		$dsn = "mysql:host=".DBHOST.";dbname=".DBDB.";charset=utf8";
		try{
			$db_conn = new PDO($dsn, DBUSER, DBPW);
			return $db_conn;
        } 
        catch (PDOException $e){
			echo "<p>Error opening database <br/>\n".$e->getMessage()."</p>\n";
			exit(1);
		}
	}

?>
<html>
<head>
    <title>Lab 3</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$errorArray = validate_fields();
	if (count($errorArray) > 0){
        display_error($errorArray);
	} else {
		display_success();
	}
}
else{
    /*$_SESSION['first_name'] = "";
    $_SESSION['last_name'] = "";
    $_SESSION['email'] = "";
    $_SESSION['phone'] = "";*/
} 
?>
<body>
    <h1>Fill in the form</h1>
    <form method="POST" action="./index.php">

      <h3>First Name:</h3>
        <input type="text" class="form-control" name="first_name" value="<?php  echo $_SESSION["first_name"]; ?>"/>
        <h3>Last Name:</h3>
        <input type="text" class="form-control" name="last_name" value="<?php  echo $_SESSION["last_name"]; ?>"/>
        <h3>Email:</h3>
        <input type="text" class="form-control" name="email" value="<?php  echo $_SESSION["email"]; ?>"/>
        <h3>Personal Email</h3>
        <input type="checkbox"  name="email_personal" value = "1" <?php echo isSet($_SESSION['email_personal']) ? "checked" : null; ?>><?php echo $email_personal;?> </input>
        <h3>Phone:</h3>
        <input type="text" class="form-control" name="phone" value="<?php  echo $_SESSION["phone"]; ?>"/>
        <h3>Personal Phone</h3>
        <input type="checkbox"  name="phone_personal" value ="1" <?php echo isSet($_SESSION['phone_personal']) ? "checked" : null; ?>><?php echo $phone_personal;?> </input>
        <br><br>
        <input class="btn-lg btn-primary" type="submit" value="Submit"     name="submit" />	
    </form>

    
    <?php function validate_fields(){
        $errorArray = array();
        if (!isset($_POST['first_name'])){
            $errorArray[] = "First Name field not defined";
        }
        else if (isset($_POST['first_name'])){
            $first_name = trim($_POST['first_name']);
            
            if (empty($first_name)){
                $errorArray[] = "The First Name field is empty";
            } 
            else {
                if (strlen($first_name) >  50){
                    $errorArray[] = "The First Name field contains too many characters";
                }
            }
        }
        if (!isset($_POST['last_name'])){
            $errorArray[] = "Las Name field not defined";
        }
        else if (isset($_POST['last_name'])){
            $last_name = trim($_POST['last_name']);
            if (empty($last_name)){
                $errorArray[] = "The Last Name field is empty";
            } else {
                if (strlen($last_name) >  50){
                    $errorArray[] = "The Name field contains too many characters";
                }
            }
        }
        if (!isset($_POST['email'])){
            $errorArray[] = "Email field not defined";
        } else if (isset($_POST['email'])){
            $email = trim($_POST['email']);
            if (empty($email)){
                $errorArray[] = "The email field is empty";
            } 
            else if (strlen($email) >  128){
                $errorArray[] = "The email field contains too many characters";
            } 
            else {
                $tmp_email = filter_var($email, FILTER_VALIDATE_EMAIL);
                if (!$tmp_email){
                    $errorArray[] = "Invalid email address entered";
                }
            }
        }
        if (!isset($_POST['phone'])){
            $errorArray[] = "Phone field not defined";
        } 
        else if (isset($_POST['phone'])){
            $phone = trim($_POST['phone']);
            if (empty($phone)){
                $errorArray[] = "The phone field is empty";
            } 
            else if (strlen($phone) > 20){
                $errorArray[] = "The phone field contains too many characters";
            }
        }
        
        if(isset($_POST['email_personal'])){
           $email_personal = 1;
        }
        else{
            $email_personal = 0;
        }

        if(isset($_POST['phone_personal'])){
            $phone_personal = 1;
        }
        else{
             $phone_personal = 0;
        }

        if (count($errorArray) == 0){
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            $_SESSION['email_personal'] = $email_personal;
            $_SESSION['phone'] = $phone;
            $_SESSION['phone_personal'] = $phone_personal;
             
            $db_conn = connectDB();

            $stmt = $db_conn->prepare("insert into lab3 (first_name, last_name, email, email_personal, phone, phone_personal) values (:first_name, :last_name, :email, :email_personal, :phone, :phone_personal)");
            if (!$stmt){
                echo "Error ".$dbc->errorCode()."\nMessage ".implode($dbc->errorInfo())."\n";
            exit(1);
            }       
            $data = array(":first_name" => $first_name, ":last_name" => $last_name, ":email" => $email, ":email_personal" => $email_personal, ":phone" => $phone, ":phone_personal" => $phone_personal);
            $status = $stmt->execute($data);
    
            if(!$status) {
            echo "Error ".$stmt->errorCode()."\nMessage ".implode($stmt->errorInfo())."\n";
            exit(1);
            }
        }
        return $errorArray;
    } ?>
    
    <?php function display_error($errorArray){
        echo "<p>\n";
        foreach($errorArray as $v){
            echo $v."<br>\n";
        }
        echo "</p>\n";
    } ?>
    
    <?php function display_success(){ ?>
        <h2>Success</h2>
        <p> The input data is valid</p>
    <?php } ?>
    
</html>