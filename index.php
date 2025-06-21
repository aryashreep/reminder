<?php
session_start();
require("config.php");

if (isset($_POST["login"])) {
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$uname = mysqli_real_escape_string($conn, $_POST["uname"]);
	$upass = mysqli_real_escape_string($conn, $_POST["upass"]);

	$sql = "SELECT * FROM admin WHERE ANAME='{$uname}' AND APASS='{$upass}'";
	$res = $conn->query($sql);
	if ($res->num_rows > 0) {
		$row = $res->fetch_assoc();
		$_SESSION["login_info"] = $row;
		exit(header("Location: home.php"));
	} else {
		echo "<div class='alert alert-danger'>Invalid Login Details.</div>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once("header.php"); ?>

<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="index.php">🎁 Event Reminder</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
	</nav>
	<div class='container mt-3'>
		<div class="jumbotron">
			<h2 class='text-muted text-center'>🎁 Event Reminder</h2>
		</div>
		<div class='row'>
			<div class='col-md-5 mx-auto'>
				<h3 class='text-muted text-center'>LOGIN</h3>
				<form name="adminlogin" action='' method='post'>
					<div class="form-group">
						<label>User Name</label>
						<input type="text" class="form-control" name='uname' placeholder="UserName" required>
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name='upass' placeholder="Password" required>
					</div>
					<div class="form-group">
						<input type='submit' name='login' value='Login' class='btn btn-primary'>
					</div>
				</form>
			</div>

		</div>
	</div>
</body>
<script>
	if ('serviceWorker' in navigator) {
		console.log("Will the service worker register?");
		navigator.serviceWorker.register('service-worker.js')
			.then(function(reg) {
				console.log("Yes, it did.");
			}).catch(function(err) {
				console.log("No it didn't. This happened:", err)
			});
	}
</script>

</html>