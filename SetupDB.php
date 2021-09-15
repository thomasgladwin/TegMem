<a href = "index.php">Open an existing brain</a>

<?php
	include('header0.php'); // Params

	// Check loggin or present password input
	if (!isset($_POST['password'])) {
		echo '<h1>Create new brain</h1>';
		echo '<form method="post" action="SetupDB.php">';
		echo 'Brain name:<br><input type="text" name="BrainName" size="15" value="" />';
		echo '<p>Password for creation:<br><input type="text" name="password" size="15" value=""/>';
		echo '<p><input type="submit" value="Create">';
		echo '</form>';
		die();
	} else {
		if ($_POST['password'] != $loginpassword) {
			echo 'Incorrect password';
			echo '<script>window.location.href = window.location.pathname</script>';
			die();
		}
	}
?>

<?php

$link = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()) {
   die("Connect failed: %s\n" + mysqli_connect_error());
   exit();
}
echo 'Connection succeeded.<p>';

// Table names for this Brain
$episodic_name = $_POST['BrainName']."tegmem_episodic";
$semantic_name = $_POST['BrainName']."tegmem_semantic";

// Delete table
try {
	$sql = "DROP TABLE ".$episodic_name;
	$result = mysqli_query($link,$sql);
	echo 'Old tegmem_episodic table deleted.<p>';
} catch (Exception $e) {
	echo 'No previous table.<p>';
}

try {
	$sql = "DROP TABLE ".$semantic_name;
	$result = mysqli_query($link,$sql);
	echo 'Old tegmem_semantic table deleted.<p>';
} catch (Exception $e) {
	echo 'No previous table.<p>';
}

echo '1.';

// Create table
$sql = "CREATE TABLE ".$episodic_name."(ID INT AUTO_INCREMENT, Title varchar(255), Description MEDIUMTEXT, Actions MEDIUMTEXT, Timestamp int, primary key(ID))";
$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
echo 'New ImpactEvents table created.<p>';

echo '2.';

$sql = "CREATE TABLE ".$semantic_name."(ID INT AUTO_INCREMENT, SemanticEntry MEDIUMTEXT, primary key(ID))";
$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
echo 'New semantic table created.<p>';

mysqli_close($link);

echo 'Setup complete.';

echo '<br><a href = "SetupDB.php">Add another brain</a>';

?>
