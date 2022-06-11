<?php

namespace Gateway;

use PDO;

class User
{
	/**
	 * @var PDO
	 */
	public static $instance;

	/**
	 * Реализация singleton
	 * @return PDO
	 */
	public static function getInstance(): PDO
	{
		if (is_null(self::$instance)) {
			$dsn = 'mysql:dbname=db;host=127.0.0.1';
			$user = 'root';
			$password = 'root';
			self::$instance = new PDO($dsn, $user, $password);
		}

		return self::$instance;
	}

	/**
	 * Возвращает список пользователей старше заданного возраста.
	 * @param int $ageFrom
	 * @return array
	 */
	public static function getUsers(int $ageFrom): array
	{
		$stmt = self::getInstance()->prepare("SELECT `id`, `name`, `lastName`, `from`, `age`, `settings` FROM `Users` WHERE `age` > {$ageFrom} LIMIT " . \Manager\User::limit);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($rows) {
			foreach ($rows as &$row) {
				$row['settings'] = $row['settings'] ? json_decode($row['settings']) : [];
				$row['key'] = $settings['key'] ?? false;
			}
		}
		return $rows ? : [];
	}

	/**
	 * Возвращает пользователя по имени.
	 * @param string $name
	 * @return array|boolean
	 */
	public static function user(string $name): array
	{
		$stmt = self::getInstance()->prepare("SELECT `id`, `name`, `lastName`, `from`, `age`, `settings` FROM `Users` WHERE `name` = ?", [$name]);
		$stmt->execute();
		$user_by_name = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($user_by_name) {
			$settings = $user_by_name['settings'] ? json_decode($user_by_name['settings']) : [];

			$user_by_name['key'] = $settings['key'] ?? false;

			return $user_by_name;
		}
		return false;
	}

	/**
	 * Получение пользователей по именам
	 * @param array $names
	 * @return array
	 */
	public static function getByNames(array $names) {

		$in  = str_repeat('?,', count($names) - 1) . '?';
		$sql = "SELECT `id`, `name`, `lastName`, `from`, `age`, `settings` FROM `Users` WHERE `name` in ($in)";
		$stmt = self::getInstance()->prepare($sql);
		$stmt->execute($names);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($rows) {
			foreach ($rows as &$row) {
				$row['settings'] = $row['settings'] ? json_decode($row['settings']) : [];
				$row['key'] = $settings['key'] ?? false;
			}
		}

		return $rows ? : [];
	}

	/**
	 * Добавляет пользователя в базу данных.
	 * @param string $name
	 * @param string $lastName
	 * @param int $age
	 * @return string
	 */
	public static function add(string $name, string $lastName, int $age): string
	{
		$db = self::getInstance();
		$sth = $db->prepare("INSERT INTO `Users` (`name`, `lastName`, `age`) VALUES (:name, :lastName, :age)");

		$sth->execute(['name' => $name, 'age' => $age, 'lastName' => $lastName]);

		return $db->lastInsertId();
	}

}