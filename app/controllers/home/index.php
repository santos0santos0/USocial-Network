<?php
require_once "../../../core/session/SessionManagement.php";
require_once "../../../core/routes/RoutesManagement.php";
require_once "../../../core/views/ViewsManagement.php";
require_once "../../../core/db/DatabaseConnection.php";
require_once "../../services/UserService.php";
require_once "../../services/PostService.php";
$session = SessionManagement::getInstance();
if ($session->logged()) {
	$vm = new ViewsManagement();
	$vm->session = $session;
	$user_service = new UserService();
	$vm->invitations = $user_service->list_friends($session->user->id, 0, 6);
	$vm->friends = $user_service->list_friends($session->user->id, 1, 6);
	if (count($vm->invitations) > 0) {
		$vm->set("panel_invitations", "/app/views/fragments/panel-invitations.php");
	}
	if (count($vm->friends) > 0) {
		$vm->set("panel_friends", "/app/views/fragments/panel-friends.php");
	}
	//
	$post_service = new PostService();
	$vm->posts = $post_service->prepare($post_service->timeline($session->user->id), $user_service);
	if (count($vm->posts) > 0) {
		$vm->set("panel_posts", "/app/views/fragments/panel-posts.php");
	}
	//
	$vm->set("content", "/app/views/home/index.php");
	$vm->render();
} else {
	RoutesManagement::redirect("/app/");
}