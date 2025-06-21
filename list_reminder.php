<?php
session_start();
require("config.php");

if (!isset($_SESSION["login_info"])) {
	header("location:index.php");
}

if (isset($_POST["frmSearch"])) {
	$listname = $_POST['name'];
} else {
	$listname = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "header.php"; ?>

<body>
	<?php include "navbar.php"; ?>
	<div class='container mt-4'>
		<div class='row'>
			<div class='col-md-12'>
				<h3 class='text-muted text-center'>List Reminder</h3>
				<table class='table table-bordered mt-5'>
					<thead>
						<form name="frmSearch" method="post" action="">
							<tr>
								<td colspan="7" align="right">
									<input type="text" placeholder="Name" name="name" value="<?php echo $listname; ?>" />
									<input type="submit" name="go" class="btnSearch" value="Search">
									<input type="reset" class="btnReset" value="Reset" onclick="window.location='list_reminder.php'">
								</td>
							</tr>
						</form>
						<tr>
							<td>S.No</td>
							<td>Name</td>
							<td>Photo</td>
							<td>Email</td>
							<td>DOB</td>
							<td>Event</td>
							<td>Delete</td>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($_GET['pageno'])) {
							$pageno = $_GET['pageno'];
						} else {
							$pageno = 1;
						}
						$no_of_records_per_page = 10;
						$offset = ($pageno - 1) * $no_of_records_per_page;
						if (isset($_POST["go"])) {
							$name = $_POST['name'];
							$total_pages_sql = "SELECT COUNT(*) FROM users WHERE name LIKE '%$name%'";
						} else {
							$total_pages_sql = "SELECT COUNT(*) FROM users";
						}
						$result = $conn->query($total_pages_sql);
						$total_rows = mysqli_fetch_array($result)[0];
						$total_pages = ceil($total_rows / $no_of_records_per_page);

						if (isset($_POST["go"])) {
							$name = $_POST['name'];
							$sql = "select * from users WHERE name LIKE '%$name%' LIMIT $offset, $no_of_records_per_page";
						} else {
							$sql = "select * from users LIMIT $offset, $no_of_records_per_page";
						}

						$res = $conn->query($sql);

						if ($res->num_rows > 0) {
							$i = 0;
							while ($row = $res->fetch_assoc()) {
								$i++;
						?>
								<tr>
									<td><?php echo $i; ?></td>
									<td>
										<?php
										echo $row["name"];
										if ($row["gender"] == 'male') {
											echo '&nbsp;Prabhuji🙏';
										} else {
											echo '&nbsp;Mataji🙏';
										}
										?>
									</td>
									<td>
										<?php
										if (!empty($row["photo"])) {
											$image_name = $row["photo"];
										} else {
											$image_name = 'noimage.jpg';
										}
										if (file_exists('.' . DIRECTORY_SEPARATOR . 'profile_photo' . DIRECTORY_SEPARATOR . $row["photo"])) {
										?>
											<img src="<?php echo '.' . DIRECTORY_SEPARATOR . 'profile_photo' . DIRECTORY_SEPARATOR . $image_name; ?>" class="img-fluid img-thumbnail" style="width:80px; height:80px; border: 2px solid #cccccc;">
										<?php
										} else {
										?>
											<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
												<path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
												<path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
											</svg>
										<?php
										}
										?>
									</td>
									<td><?php echo $row["email"]; ?></td>
									<td><?php echo  date("d-m-Y", strtotime($row["dob"])); ?></td>
									<td style="color: blue;"><strong><?php echo ucfirst($row["event"]); ?></strong>&#127856;&#128144;</td>
									<td><a href='delete_reminder.php?id=<?php echo $row["id"]; ?>&img=<?php echo $row["photo"]; ?>' class='btn btn-sm btn-danger' onclick="return confirm('Are you sure you want to delete this item?');">Delete</a></td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
					<tr>
						<td colspan="7">
							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center">
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
						</td>
					</tr>
				</table>
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