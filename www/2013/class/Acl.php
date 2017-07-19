<?php
include_once CLASS_PATH . '/Db.php';

/**
 * Acl system based in: http://net.tutsplus.com/tutorials/php/a-better-login-system/
 * Luciano Marinho - 2012-06-22 16:42
 */
class Acl extends Db {
	var $perms = array();  //Array : Stores the permissions for the user
	var $userId = 0;   //Integer : Stores the ID of the current user
	var $userRoles = array(); //Array : Stores the roles of the current user

	function __constructor($userId = '') {
		if(!empty($userId)) { $this->setUserId($userId); }
		$this->userRoles = $this->getUserRoles();
		$this->buildAcl();
	}

	function Acl($userId = '') {
		$this->__constructor($userId);
	}

	function setUserId($id) {
		if(!empty($id)) { $this->userId = $id; }
	}

	function getUserId() {
		if(!empty($this->userId)) { return $this->userId; }
	}

	function buildACL() {
		//first, get the rules for the user's role
		if (count($this->userRoles) > 0) {
			$this->perms = array_merge($this->perms, $this->getRolePerms($this->userRoles));
		}
		//then, get the individual user permissions
		$this->perms = array_merge($this->perms, $this->getUserPerms($this->userId));
	}

	function getUserRoles() {
		$sql = "SELECT * FROM `acl_users_roles` WHERE `userId` = " . $this->userId . " ORDER BY `cdate` ASC";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$resp = array();

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$resp[] = $data['roleId'];
		}
		return $resp;
	}

	function getAllRoles($format = 'ids') {
		$format = strtolower($format);
		$sql = "SELECT * FROM `acl_roles` ORDER BY `roleName` ASC";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$resp = array();

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			if ($format == 'full') {
				$resp[] = array("id" => $data['id'], "roleName" => $data['roleName']);
			} else {
				$resp[] = $row['id'];
			}
		}
		return $resp;
	}

	function getPermKeyFromID($permId) {
		$sql = "SELECT `permKey` FROM `acl_permissions` WHERE `id` = " . $permId . " LIMIT 1";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return $data['permKey'];
	}

	function getPermNameFromID($permId) {
		$sql = "SELECT `permName` FROM `acl_permissions` WHERE `id` = " . $permId . " LIMIT 1";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return $data['permName'];
	}

	function getRoleNameFromID($roleId) {
		$sql = "SELECT `roleName` FROM `acl_roles` WHERE `id` = " . $roleId . " LIMIT 1";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		return $data['roleName'];
	}

	function getRoleIDFromName($roleName) {
		$return='';
		$sql = "SELECT `id` FROM `acl_roles` WHERE `roleName` = " . $roleName . " LIMIT 1";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		if(!empty($data['id'])) {
			$return=$data['id'];
		}
		return $return;
	}

	function getRolePerms($role) {
		if (is_array($role)) {
			$sql="SELECT * FROM `acl_roles_permissions` WHERE `roleId` IN (" . implode(",", $role) . ") ORDER BY `id` ASC";
		} else {
			$sql="SELECT * FROM `acl_roles_permissions` WHERE `roleId` = " . $role . " ORDER BY `id` ASC";
		}

		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$perms = array();

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$pK = strtolower($this->getPermKeyFromID($data['permId']));
			if ($pK == '') {
				continue;
			}
			if ($data['value'] === '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK, 'inheritted' => true, 'value' => $hP, 'Name' => $this->getPermNameFromID($data['permId']), 'id' => $data['permId']);
		}
		return $perms;
	}

	function getUserPerms($userId) {
		$sql = "SELECT * FROM `acl_users_permissions` WHERE `userId` = " . $userId . " ORDER BY `cdate` ASC";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$perms = array();

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$pK = strtolower($this->getPermKeyFromID($data['permId']));
			if ($pK == '') {
				continue;
			}
			if ($data['value'] == '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK, 'inheritted' => false, 'value' => $hP, 'Name' => $this->getPermNameFromID($data['permId']), 'id' => $data['permId']);
		}
		return $perms;
	}

	function getAllPerms($format = 'ids') {
		$format = strtolower($format);
		$sql = "SELECT * FROM `acl_permissions` ORDER BY `permName` ASC";
		$conn = Db_Instance::getInstance();
		$q = $conn->prepare($sql);
		$q->execute();
		$resp = array();

		while($data = $q->fetch(PDO::FETCH_ASSOC)) {
			if ($format == 'full') {
				$resp[$data['permKey']] = array('id' => $data['id'], 'permName' => $data['permName'], 'permKey' => $data['permKey']);
			} else {
				$resp[] = $data['id'];
			}
		}
		return $resp;
	}

	function userHasRole($roleId) {
		foreach ($this->userRoles as $k => $v) {
			if ($v === $roleId) {
				return true;
			}
		}
		return false;
	}

	function hasPermission($permKey) {
		//echo '<b>$this->perms' . "</b><br><pre>";
		//print_r($this->perms);
		//echo "</pre>";

		$permKey = strtolower($permKey);
		if (array_key_exists($permKey, $this->perms)) {
			if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}
