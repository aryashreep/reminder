<?php
function number_suffix($number)
{
	$ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
	if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
		return $number . 'th';
	} else {
		return $number . $ends[$number % 10];
	}
}

$notifications = [];
$current_month_day = date("m-d");
$sql = "SELECT * FROM users WHERE DATE_FORMAT(dob, '%m-%d')='{$current_month_day}'";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
	while ($row = $res->fetch_assoc()) {
		$age = (date("Y") - date("Y", strtotime($row["dob"]))) + 1;
		$name = ucfirst($row["name"]);
		$event = ucfirst($row["event"]);
		$notifications[] = "<i class='fa fa-bell'></i> Wish <b>{$name}</b> a Happy {$event}!<br> This is <b>{$name}</b>'s " . number_suffix($age) . " {$event}. date of {$event} is <b>" . date("d-m-Y", strtotime($row["dob"])) . "</b>";
	}
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="home.php">🎁 Event Reminder</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="home.php"><span class='fa fa-user'></span> Welcome : <?php echo ucfirst($_SESSION["login_info"]["ANAME"]); ?> </a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="home.php"><span class='fa fa-home'></span> Home</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="add_reminder.php"><span class='fa fa-plus'></span> Add Reminder</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="list_reminder.php"><span class='fa fa-list'></span> List Reminder</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<span class='fa fa-bell'></span>(<?php echo count($notifications); ?>)
				</a>
				<?php if (count($notifications) > 0): ?>
					<div class="dropdown-menu dropdown-menu-right p-2" aria-labelledby="navbarDropdown">
						<?php foreach ($notifications as $row): ?>
							<a class="dropdown-item pt-3 pb-3 alert alert-success" href="#"><?php echo $row; ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="logout.php">Logout</a>
			</li>
		</ul>
	</div>
</nav>