  
  
  <body>
		<div class="main">
			<header>Login</header>
            <style> *
{box-sizing:border-box}

body
{background:linear-gradient(90deg,#57383b, #352850, #39393c)}

.main
{text-align:center;margin-top:15%}

.main header
{color:#fff;font-weight:bold;font-size:30px;}
.message {color:#fff;font-weight:bold;font-size:25px;text-align:center}
.main form input
{
	margin:13px auto;
	display:block;
	padding:10px;
	width:300px;
	border-radius:5px;
	border:1px solid #212121;
	background-color:#1c2129;
	box-shadow:-5px -5px 40px -10px rgba(255, 255, 255, 0.1) inset;
	color:#fff;
	border-bottom:1px solid rgba(255, 255, 255, 0.2)
}

.main input[type="submit"]
{
	border:none;
	width:300px;
	padding:10px;
	text-align:center;
	border-radius:5px;
	background-color:#4a77d4;
	box-shadow: 0px 5px 50px -3px rgba(255, 255, 255, 0.3) inset,
	 1px 1px 1px 2px rgba(25, 25, 25, 0.4);
	color:#fff;
	font-weight:bold;
	text-shadow:1px 2px 1px #333;
}

.main input[type="submit"]:hover
{cursor:pointer;background-color:#5183ea;transition:all .3s ease-in-out} </style>

			<form method="POST" action="<?php echo $_SERVER["PHP_SELF"] ?>">
				<input type="text" name="user" />
				<input type="password" name="pass" />
                <input type="submit" value="Let Me In." />
                
			</form>
			
		</div>
  </body>

  <?php
  $db= New PDO ("mysql:host=localhost;dbname=1st","ahmad","ahmad");

	$login = $db->query("
		SELECT * FROM login
	");

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty($_POST["user"]) && empty($_POST["pass"])){echo "<div class='message'>PLEASE ENTER YOUR <br>USERNAME & PASSWORD</div>";} 
	else if (empty($_POST["user"])){echo "<div class='message'>PLEASE ENTER YOUR USERNAME</div>";}
	else if (empty($_POST["pass"])) {echo "<div class='message'>PLEASE ENTER YOUR PASSWORD</div>";}
	else if(!empty($_POST["user"]) && !empty($_POST["pass"])){
		$user = $_POST["user"];
		$pass = $_POST["pass"];
		$check = "";
		$correct_user = "";
		$correct_pass = "";
		$fetch = $login->fetchall(PDO::FETCH_OBJ);
		foreach($fetch as $key => $users){
		if(in_array($user,$users) && !in_array($pass,$users)){$check .= " usernopass ";}
		else if(!in_array($user,$users) && in_array($pass,$users)){$check .= " passnouser ";}
		else if(in_array($user,$users) && in_array($pass,$users)){
			$check .= "userpass ";
			$correct_user = $user;
			$correct_pass = $pass;
			} else {$check .= "no "; }
	}
	
	$check = explode(" ",$check);
	
	if(!(in_array("userpass",$check) || in_array(" userpass",$check) || in_array("userpass ",$check) || in_array(" userpass ",$check))){
		if(in_array("usernopass",$check) || in_array(" usernopass",$check) || in_array("usernopass ",$check) || in_array(" usernopass ",$check))
		{echo "<div class='message'>INCORRECT PASSWORD</div>";}
	else if (in_array("passnouser",$check) || in_array(" passnouser",$check) || in_array("passnouser ",$check) || in_array(" passnouser ",$check)){
		echo "<div class='message'>USERNAME DOES NOT EXIST</div>";}
	else {echo "<div class='message'>USERNAME & PASSWORD <br> ARE INCORRECT</div>";}

	} else if(in_array("userpass",$check) || in_array(" userpass",$check) || in_array("userpass ",$check) || in_array(" userpass ",$check)){
			$succeed = $db->prepare("
				SELECT * FROM login WHERE user= :username AND pass= :password
			");
			$succeed->execute([
				':username' => $correct_user,
				':password' => $correct_pass
			]);
			$succeeds = $succeed->fetchobject();
			$age = $succeeds->age;
			$name = $succeeds->name;
		
			echo "<div class='message'>LOGIN SUCCEEDED <br> Hello, $name</div>";
	} 
	}
}