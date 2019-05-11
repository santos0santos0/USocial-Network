<?php
require_once "../../../core/views/ViewsManagement.php";
require_once "../../../core/session/SessionManagement.php";
require_once "../../../core/routes/RoutesManagement.php";
require_once "../../../core/db/DatabaseConnection.php";
require_once "../../services/UserService.php";
$session = SessionManagement::getInstance();
if ($session->logged()) {
	$conn = DatabaseConnection::getInstance();
	$out = array("error" => false);
	//
	$id = isset($_GET["id"]) ? $_GET["id"] : "";
	$id = (($id !== "") ? $id : $session->user->id);
	//
	$sql = "SELECT * FROM user AS U WHERE U.id = '" . $id . "'";
	$query = $conn->query($sql);
	if ($query->rowCount() > 0) {
		$vm = new ViewsManagement();
		$vm->session = $session;
		$vm->user = $query->fetchObject();
		$user_service = new UserService();
		$vm->friends = $user_service->list_friends($session->user->id);
		$vm->posts = $user_service->list_friends($id);
		$vm->set("panel_friends", "/app/views/fragments/panel-friends.php");
		$vm->set("content", "/app/views/users/index.php");
		$vm->render();
	} else {
		$out["error"] = true;
		$out["message"] = "User not exists.";
		header("Content-type: application/json");
		echo json_encode($out);
		die();
	}
} else {
	RoutesManagement::redirect("/app/");
}