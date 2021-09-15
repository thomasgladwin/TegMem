<?php
	include('header0.php'); // Params
	
	session_start();
	// Check loggin or present password input
	if (isset($_POST['NewBrainName'])) {
		// Table names for this Brain
		$_SESSION['BrainName'] = $_POST['NewBrainName'];
	} else {
		if (!isset($_SESSION['logged_in'])) {
			if (!isset($_POST['password'])) {
				echo '<h1>Brain-name and log-in password</h1>';
				echo '<form method="post" action="index.php">';
				echo '<br><input type="text" name="BrainName" size="75" value=""/>';
				echo '<br><input type="text" name="password" size="75" value=""/>';
				echo '<br><input type="submit" value="Open">';
				echo '</form>';
				die();
			} else {
				if ($_POST['password'] == $loginpassword) {
					// Continue
					$_SESSION['logged_in'] = 1;
				} else {
					echo 'Incorrect password';
					echo '<script>window.location.href = window.location.pathname</script>';
					die();
				}
			}
			$_SESSION['BrainName'] = $_POST['BrainName'];
		}
	}
	$brainName = $_SESSION['BrainName'];
	$episodic_name = $brainName."tegmem_episodic";
	$semantic_name = $brainName."tegmem_semantic";
	echo '<form method="post" action="index.php">';
	echo 'Current Brain: '.$brainName.' <input type="text" name="NewBrainName" size="15" value=""/> ';
	echo '<input type="submit" value="Open new brain"> ';
	echo '<a href = "SetupDB.php">Create a new brain</a><br>';
	echo '</form>';
?>

<?php
	$link = mysqli_connect($hostname, $username, $password, $database);
	if (mysqli_connect_errno()) {
	   die("Database connection failed: %s\n" + mysqli_connect_error());
	   exit();
	}
	
	// Safety checks of all POST and GET var's
	foreach($_POST as $key => $userinput) {
		//print $key.' = '.$userinput.'<br>';
		$_POST[$key] = mysqli_real_escape_string($link, $_POST[$key]);
	}

	foreach($_GET as $key => $userinput) {
		//print $key.' = '.$userinput.'<br>';
		$_GET[$key] = mysqli_real_escape_string($link, $_GET[$key]);
	}
?>

<?php
	// Check existence of Brain
	$sql = "SELECT * FROM ".$episodic_name;
	$result = mysqli_query($link,$sql) or die("Unable to select: ".mysql_error());
	$exists = TRUE;
	if ( !mysqli_query($link, "SELECT * FROM ".$episodic_name) ) {
		$exists = FALSE;
	}
	if ( !mysqli_query($link, "DESCRIBE ".$semantic_name) ) {
		$exists = FALSE;
	}
	if (!$exists) {
		echo "Brain does not exist; check capitalization.</br>";
		die();
	}
?>
