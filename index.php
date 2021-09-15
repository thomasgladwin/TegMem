<?php
	include('header.php'); // Creates $link for SQL queries
?>

<script>
	function toggleSemantic() {
		if (document.getElementById("semanticTable").style.display == "table") {
			document.getElementById("semanticTable").style.display = "none";
			document.getElementById("newsemanticform").style.display = "none";
		} else {
			document.getElementById("semanticTable").style.display = "table";
			document.getElementById("newsemanticform").style.display = "table";
		}
	}
	function toggleVis(elid) {
		if (document.getElementById(elid).style.display == "table") {
			document.getElementById(elid).style.display = "none";
		} else {
			document.getElementById(elid).style.display = "table";
		}
	}
</script>

<html>
	<head>
		<title>TegMem</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

		<?php
			if (isset($_POST['Description'])) {
				echo $_POST['Description'];
				// Event entry
				if (isset($_POST['ID'])) {
					// Replace event
					$sql = "UPDATE ".$episodic_name." SET Title = '".$_POST['Title']."' WHERE ID = ".$_POST['ID'].";";
					echo $sql;
					$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
					$sql = "UPDATE ".$episodic_name." SET Description = '".$_POST['Description']."' WHERE ID = ".$_POST['ID'].";";
					$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
					$sql = "UPDATE ".$episodic_name." SET Actions = '".$_POST['Actions']."' WHERE ID = ".$_POST['ID'].";";
					$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
				} else {
					// New event
					$sql = "INSERT INTO ".$episodic_name." (Title, Description, Actions, Timestamp) VALUES ('".$_POST['Title']."', '".$_POST['Description']."', '".$_POST['Actions']."', ".time().");";
					$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
				}
			}

			if (isset($_POST['NewSemantic'])) {
				$sql = "INSERT INTO ".$semantic_name." (SemanticEntry) VALUES ('".$_POST['NewSemantic']."');";
				$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
			}
		?>

		<?php
			if (isset($_GET['delete'])) {
				$sql = "DELETE FROM ".$episodic_name." WHERE ID = ".$_GET['delete'];
				$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
			}
			
			if (isset($_GET['deletesemantic'])) {
				$ID = $_GET['deletesemantic'];
				$sql = "DELETE FROM ".$semantic_name." WHERE ID = ".$_GET['deletesemantic'];
				$result = mysqli_query($link, $sql) or die("Unable to select: ".mysql_error());
			}
		?>

		<?php
			if (isset($_GET['edit'])) {
				$ID = $_GET['edit'];
				$sql = "SELECT * FROM ".$episodic_name." WHERE ID = ".$ID;
				$result = mysqli_query($link,$sql) or die("Unable to select: ".mysql_error());
				$row = mysqli_fetch_row($result);
				$Title = $row[1];
				$Description = $row[2];
				$Actions = $row[3];
			} else {
				$Title = "";
				$Description = "";
				$Actions = "";
			}
		?>

		<h1>TegMem</h1>
		
		<div>
		<button id="semanticButton" onclick="toggleSemantic(this);"><h2>Semantic</h2></button>
		<?php
			$sql = "SELECT * FROM ".$semantic_name;
			$result = mysqli_query($link,$sql) or die("Unable to select: ".mysql_error());
			print "<table id='semanticTable' style='display:none'>\n";
			while($row = mysqli_fetch_row($result)) {
				print "<tr>\n";
				print "<td><a href='index.php?t=".time()."&deletesemantic=".$row[0][0]."'>".Del."</a></td>";
				print "<td>".$row[1]."</td>";
				print "</tr>\n";
			}
			print "</table>\n";
		?>

		<?php
			echo '<form id="newsemanticform" method="post" action="index.php?t="'.time().' method = "post" style="display:none">';
		?>
			<p><input type="submit" value="Add new semantic entry: " />
			<?php
				print '<input type="text" name="NewSemantic" size="75">'."".'</textarea>';
			?>
			</p>
		</form>
		</div>

		<div>
		<h2>Episodic and working memory: <button onclick="toggleVis('listform');">Overview</button>, <button onclick="toggleVis('listformfull');">Full</button></h2>

		<?php
			$sql = "SELECT * FROM ".$episodic_name;
			$result = mysqli_query($link,$sql) or die("Unable to select: ".mysql_error());
			print "<table id = 'listform' style='display:none'>\n";
			while($row = mysqli_fetch_row($result)) {
				print "<tr>\n";
				print "<td><a href='index.php?t=".time()."&delete=".$row[0]."'>Delete</a></td>";
				print "<td><a href='index.php?t=".time()."&edit=".$row[0]."'>Edit</a></td>";
				print "<td>".$row[1]."</td>";
				print "<td>|</td>";
				print "<td>".$row[3]."</td>";
				print "</tr>\n";
			}
			print "</table>\n";
		?>

		<?php
			$sql = "SELECT * FROM ".$episodic_name;
			$result = mysqli_query($link,$sql) or die("Unable to select: ".mysql_error());
			print "<table id = 'listformfull' style='display:none'>\n";
			while($row = mysqli_fetch_row($result)) {
				print "<tr>\n";
				print "<td>---</td>\n";
				print "</tr>\n";
				print "<tr>\n";
				print "<td>".date("Y.m.d", $row[count($row) - 1])."</td>";
				print "</tr>\n";
				foreach($row as $field) {
					print "<tr>\n";
					print "<td>$field</td>\n";
					print "</tr>\n";
				}
				print "<tr>\n";
				print "<td><a href='index.php?delete=".$row[0]."'>Delete</a></td>";
				print "</tr>\n";
				print "<tr>\n";
				print "<td><a href='index.php?edit=".$row[0]."'>Edit</a></td>";
				print "</tr>\n";
				print "<tr>\n";
				print "<td>---</td>\n";
				print "</tr>\n";
			}
			print "</table>\n";
		?>
		<p>
		</div>

		<div>
		<?php
			if (isset($_GET['edit'])) {
				print '<button onclick="toggleVis(\'episodeform\');" href="#"><h2>Edit episode</h2></button>';
				print '<form id="episodeform" method="post" action="index.php?t="'.time().' method = "post" style="display:table">';
			} else {
				print '<button onclick="toggleVis(\'episodeform\');" href="#"><h2>Enter episodic memory</h2></button>';
				print '<form id="episodeform" method="post" action="index.php?t='.time().'" method = "post" style="display:none">';
			}
		?>
			<p>Title:<br>
			<?php
				print '<p><input type="text" name="Title" size="75" value="'.$Title.'" />';
			?>
			<p>Description:<br>
			<?php
				print '<p><textarea name="Description" rows="10" cols="75">'.$Description.'</textarea>';
			?>
			<p>In working memory:<br>
			<?php
				print '<p><textarea name="Actions" rows="4" cols="75">'.$Actions.'</textarea>';
			?>
			<?php
				if (isset($_GET['edit'])) {
					print '<p><input type="hidden" name="ID" value = '.$ID.' />';
					print '<p><input type="submit" value="Overwrite memory" /></p>';
				} else {
					print '<p><input type="submit" value="Add new memory" /></p>';
				}
			print "<p>Note: special characters will be removed: NUL (ASCII 0) \\n \\r \\ ' \" and Control-Z.</p>";
			?>
		</form>

		<?php
			if (isset($_GET['edit'])) {
				print '<a href = "index.php?t='.time().'">Clear page</a><p>';
			}
		?>
		</div>

		<p>
		<hr>
		<p>

	</body>
</html>

<?php
	include('footer.php');
?>
