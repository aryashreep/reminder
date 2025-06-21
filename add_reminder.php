<?php
ob_start();
session_start();
require("config.php");

if (!isset($_SESSION["login_info"])) {
	header("location:index.php");
	exit();
}

$err_msg = "";
$uploadOk = 1;
if (isset($_POST["reg"])) {
	$target_dir = "profile_photo" . DIRECTORY_SEPARATOR;
	$target_file = $target_dir . basename($_FILES["photo"]["name"]);
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$name = mysqli_real_escape_string($conn, $_POST["name"]);
	$email = mysqli_real_escape_string($conn, $_POST["email"]);
	$phone = mysqli_real_escape_string($conn, $_POST["phone"]);
	$gender = mysqli_real_escape_string($conn, $_POST["gender"]);
	$event = mysqli_real_escape_string($conn, $_POST["event"]);
	$phone = mysqli_real_escape_string($conn, $_POST["phone"]);
	$dob = date("Y-m-d", strtotime($_POST["dob"]));
	$check = getimagesize($_FILES["photo"]["tmp_name"]);
	if ($check !== false) {
		$err_msg = "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		$err_msg = "File is not an image.";
		$uploadOk = 0;
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		$err_msg = "Sorry, file already exists.";
		$uploadOk = 0;
	}

	// Check file size
	if ($_FILES["photo"]["size"] > 10485760) {
		$err_msg = "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// Allow certain file formats
	if (
		$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif"
	) {
		$err_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$err_msg = "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		$temp = explode(".", $_FILES["photo"]["name"]);
		$newfilename = str_replace(' ', '_', strtolower($name)) . "_" . round(microtime(true)) . '.' . end($temp);
		move_uploaded_file($_FILES["photo"]["tmp_name"], $target_dir . $newfilename);
		if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
			$err_msg = "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";
		} else {
			$err_msg = "Sorry, there was an error uploading your file.";
		}
	}
	$temp = explode(".", $_FILES["photo"]["name"]);
	$newfilename = str_replace(' ', '_', strtolower($name)) . "_" . round(microtime(true)) . '.' . end($temp);
	$sql = "insert into users (name,email,phone,photo,gender,event,dob,wish_year) values ('{$name}','{$email}','{$phone}','{$newfilename}','{$gender}','{$event}','{$dob}','-')";
	if ($conn->query($sql)) {
		echo "<script>
		alert('Devotee has been successfully created!');
		window.location.href='list_reminder.php';
		</script>";
		exit();
	} else {
		$err_msg = "Failed Try Again";
	}
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<body>
	<?php include "navbar.php"; ?>
	<div class='container mt-4'>
		<div class='row'>
			<div class='mx-auto col-md-8'>
				<h3 class='text-muted text-center'>ADD DETAILS</h3>
				<?php
				if ($err_msg != "") {
				?>
					<div class="alert alert-danger" role="alert">
						<?php echo $err_msg; ?>
					</div>
				<?php
				}
				?>
				<form action='add_reminder.php' method='post' autocomplete='off' enctype="multipart/form-data">
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name='name' placeholder="Name" required>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name='email' placeholder="Email">
					</div>
					<div class="form-group">
						<label>Phone</label>
						<input type="text" class="form-control" name='phone' placeholder="Phone" required>
					</div>
					<div class="form-group">
						<label>Photo</label>
						<input type="file" class="form-control" id="photo" name="photo" capture="camera" accept="image/*">
					</div>
					<div class="form-group">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" required>
							<label class="form-check-label" for="genderMale">Male</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" required>
							<label class="form-check-label" for="genderFemale">Female</label>
						</div>
					</div>
					<div class="form-group">
						<label>Event</label>
						<select class="custom-select" name="event" aria-label="Default select example" required>
							<option selected>Select the Event</option>
							<option value="Birthday">Birthday</option>
							<option value="Marriage Anniversary">Marriage Anniversary</option>
						</select>
					</div>
					<div class="form-group">
						<label>Event Date</label>
						<input type="text" class="form-control datepicker" name='dob' placeholder="dd-mm-yyyy" required>
					</div>

					<div class="form-group">
						<input type='submit' name='reg' value='Submit' class='btn btn-primary'>
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