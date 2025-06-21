<?php
session_start();
require("config.php");
$target_dir = '.' . DIRECTORY_SEPARATOR . 'profile_photo' . DIRECTORY_SEPARATOR;

if (!isset($_SESSION["login_info"])) {
	header("location:index.php");
}
$sql = "DELETE FROM users WHERE id='{$_GET["id"]}'";

if ($conn->query($sql)) {
	if ($_GET["img"] != "") {
		unlink($target_dir . $_GET["img"]);
	}
	header("location:list_reminder.php");
	exit;
}
