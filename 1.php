<?php

namespace Manager;

class User
{
	const limit = 10;

	/**
	 * Возвращает пользователей старше заданного возраста.
	 * @param int $ageFrom
	 * @return array
	 */
	public static function getUsers(int $ageFrom): array
	{
		$ageFrom = (int)trim($ageFrom);

		return \Gateway\User::getUsers($ageFrom);
	}

	/**
	 * Возвращает пользователей по списку имен.
	 * @param array $names
	 * @return array
	 */
	public static function getByNames(array $names): array
	{
		return \Gateway\User::getByNames($names);
	}

	/**
	 * Добавляет пользователей в базу данных.
	 * @param $users
	 * @return array
	 */
	public static function users($users): array {
		$ids = [];
		$db = \Gateway\User::getInstance();
		$db->beginTransaction();
		try {
			foreach ($users as $user) {
				$ids[] = \Gateway\User::add($user['name'], $user['lastName'], $user['age']);
			}
			$db->commit();
		} catch (\Exception $e) {
			$db->rollBack();
		}

		return $ids;
	}
}

