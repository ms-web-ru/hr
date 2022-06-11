<?php

use Manager\User;

include '1.php';
include '2.php';


$usersNames = isset($_GET['names']) ? $_GET['names'] : false;

if ($usersNames) {
	$usersNames = @json_decode($usersNames, true) ?: [];
	$usersByNames = User::getByNames($usersNames);
	echo '$usersNames: ' . var_export($usersByNames, true) . '<br><br>';
}

$users = User::getUsers(21);
echo '$users: ' . var_export($users, true) . '<br><br>';


$newids = User::users(array(
	array(
		'name' => 'username',
		'lastName' => 'userlastname',
		'age' => 17
	),
	array(
		'name' => 'username2',
		'lastName' => 'userlastname2',
		'age' => 18
	)
));

echo '$newids: ' . var_export($newids, true);


