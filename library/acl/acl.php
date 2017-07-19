<?php
require_once 'Zend/Acl.php';
$acl = new Zend_Acl();

require_once 'Zend/Acl/Role.php';
$acl->addRole(new Zend_Acl_Role('admin'));
$acl->addRole(new Zend_Acl_Role('anunciantes'));
$acl->addRole(new Zend_Acl_Role('editores'));

require_once 'Zend/Acl/Resource.php';
$acl->add(new Zend_Acl_Resource('sistema'));
$acl->add(new Zend_Acl_Resource('vendas'));
$acl->add(new Zend_Acl_Resource('materias'));

$acl->allow('admin'); //this allows the admin role access to all defined resources
$acl->allow('editores', 'materias'); //allow editor role access to materias resource.
$acl->deny('editores', 'sistema'); //deny editor role access to sistema resource.
$acl->deny('editores', 'vendas'); //deny editor role access to vendas resource.

$acl->allow('anunciantes', 'materias'); //allow anunciante role access to materias resource.
$acl->deny('anunciantes', 'sistema'); //deny anunciante role access to doacoes resource.
$acl->deny('anunciantes', 'vendas'); //deny anunciante role access to vendas resource.

require_once 'Zend/Registry.php';
Zend_Registry::set('acl', $acl);
?>
