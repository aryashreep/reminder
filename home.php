<?php
session_start();
require("config.php");

if (!isset($_SESSION["login_info"])) {
	header("location:index.php");
}
if (isset($_POST["go"])) {
	$homename = $_POST['name'];
} else {
	$homename = '';
}
$users = [];
$current_month_day = date("m-d");
$minus6 = date('Y-m-d', strtotime('-6 days', strtotime(date('Y-m-d'))));
$last_six_days = date("m-d", strtotime($minus6));
$current_year = date("Y");
#Select today birthday users
$sql = "SELECT * FROM users WHERE DATE_FORMAT(DOB, '%m-%d') BETWEEN '{$last_six_days}' AND '{$current_month_day}' AND wish_year<>'{$current_year}'";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		$users[] = $row;
	}
}

#Send birthday wishes to Mail
foreach ($users as $user) {

	/*$to = $user["EMAIL"];

		$subject = "Birthday Greetings";

		$message = "<h3>Wish you Happy Birthday {$user["NAME"]}</h3>";

		$header="From:user@domain.in"."\r\n";
		$header.="X-Mailer:PHP/".phpversion()."\r\n";
		$header.="Content-type:text/html; charset=iso-8859-1";  

		$response=mail($to,$subject,$message,$header);
		
		if($response==true){
			$sql="update users set WISH_YEAR='{$current_year}'  where ID='{$user["ID"]}'";
			$con->query($sql);
		}else{
			echo "Mail send Failed!!!";
		}*/
}

#List of reminder
if (isset($_GET['pageno'])) {
	$pageno = $_GET['pageno'];
} else {
	$pageno = 1;
}
$no_of_records_per_page = 10;
$offset = ($pageno - 1) * $no_of_records_per_page;
if (isset($_POST["go"])) {
	$name = $_POST['name'];
	$total_pages_sql = "SELECT COUNT(*) FROM users WHERE DATE_FORMAT(DOB, '%m-%d') BETWEEN '{$last_six_days}' AND '{$current_month_day}' AND wish_year<>'{$current_year}' AND name LIKE '%$name%'";
} else {
	$total_pages_sql = "SELECT COUNT(*) FROM users WHERE DATE_FORMAT(DOB, '%m-%d') BETWEEN '{$last_six_days}' AND '{$current_month_day}' AND wish_year<>'{$current_year}'";
}
$result = $conn->query($total_pages_sql);
$total = $result->fetch_assoc();
$total_rows = $total['COUNT(*)'];
$total_pages = ceil($total_rows / $no_of_records_per_page);

if (isset($_POST["go"])) {
	$name = $_POST['name'];
	$sql = "SELECT * FROM users WHERE DATE_FORMAT(DOB, '%m-%d') BETWEEN '{$last_six_days}' AND '{$current_month_day}' AND wish_year<>'{$current_year}' AND name LIKE '%$name%' LIMIT $offset, $no_of_records_per_page";
} else {
	$sql = "SELECT * FROM users WHERE DATE_FORMAT(DOB, '%m-%d') BETWEEN '{$last_six_days}' AND '{$current_month_day}' AND wish_year<>'{$current_year}' LIMIT $offset, $no_of_records_per_page";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<body>
	<?php include "navbar.php"; ?>
	<div class='container mt-4'>
		<div class='row'>
			<div class='col-md-12 mx-auto'>
				<h3 class='text-muted text-center pb-3'>🎉 Celebration for this week 🥳</h3>
				<?php
				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
				?>
						<div class='row'>
							<div class="card mb-3 mx-auto" style="max-width: 540px;">
								<div class="row no-gutters">
									<div class="col-md-4">
										<?php
										if (!empty($row["photo"])) {
											$image_name = $row["photo"];
										} else {
											$image_name = 'jagannath.jpg';
										}
										if (file_exists('.' . DIRECTORY_SEPARATOR . 'profile_photo' . DIRECTORY_SEPARATOR . $row["photo"])) {
										?>
											<a href="<?php echo '.' . DIRECTORY_SEPARATOR . 'profile_photo' . DIRECTORY_SEPARATOR . $image_name; ?>" target="_blank">
												<img src="<?php echo '.' . DIRECTORY_SEPARATOR . 'profile_photo' . DIRECTORY_SEPARATOR . $image_name; ?>" alt="<?php echo $row["name"]; ?>" class="card-img">
											</a>
										<?php
										} else {
										?>
											<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
												<path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
												<path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
											</svg>
										<?php
										}
										?>
									</div>
									<div class="col-md-8">
										<div class="card-body">
											<h5 class="card-title">
												<?php
												echo ucwords($row["name"]);
												if ($row["gender"] == 'male') {
													echo '&nbsp;Prabhuji🙏';
												} else {
													echo '&nbsp;Mataji🙏';
												}
												?>
											</h5>
											<p class="card-text">
												Email : <?php echo $row["email"]; ?><br>
												Event Date : <?php echo  date("d-m-Y", strtotime($row["dob"])); ?><br>
												Wish you a Happy Krishna Conscious <strong><?php echo ucfirst($row["event"]); ?></strong>&#127856;&#128144;</p>
										</div>
									</div>
								</div>
							</div>
						</div>
				<?php
					}
				}
				?>
				<div class="row justify-content-center">
					<nav aria-label="Page navigation example">
						<ul class="pagination">
							<li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
							<li class="page-item <?php if ($pageno <= 1) {
														echo 'disabled';
													} ?>">
								<a class="page-link" href="<?php if ($pageno <= 1) {
																echo '#';
															} else {
																echo "?pageno=" . ($pageno - 1);
															} ?>">Prev</a>
							</li>
							<li class="page-item <?php if ($pageno >= $total_pages) {
														echo 'disabled';
													} ?>">
								<a class="page-link" href="<?php if ($pageno >= $total_pages) {
																echo '#';
															} else {
																echo "?pageno=" . ($pageno + 1);
															} ?>">Next</a>
							</li>
							<li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
						</ul>
					</nav>
				</div>
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