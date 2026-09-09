<?php
/* Copyright (C) 2026 Pierre Ardoin <developpeur@lesmetiersdubatiment.fr>
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

// Shared bootstrap for these five module pages only; never a public entry point.
if (!isset($cdavAdminTab) || !in_array($cdavAdminTab, array('setup', 'carddav', 'caldav', 'compatibility', 'about'), true)) {
	http_response_code(404);
	exit;
}
$res = 0;
foreach (array(__DIR__.'/../../main.inc.php', __DIR__.'/../../../main.inc.php') as $mainfile) {
	if (is_file($mainfile)) {
		$res = require $mainfile;
		break;
	}
}
if (!$res && !empty($_SERVER['CONTEXT_DOCUMENT_ROOT']) && is_file($_SERVER['CONTEXT_DOCUMENT_ROOT'].'/main.inc.php')) {
	$res = require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/main.inc.php';
}
if (!$res) {
	http_response_code(503);
	exit('Dolibarr bootstrap unavailable');
}
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/ajax.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formsetup.class.php';
require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';
require_once __DIR__.'/../lib/cdav_admin.lib.php';
require_once __DIR__.'/../class/cdavcompatibility.class.php';
require_once __DIR__.'/../core/modules/modCDav.class.php';
$langs->loadLangs(array('admin', 'other', 'companies', 'projects', 'members', 'interventions', 'categories', 'cdav@cdav'));
if (!$user->admin || !empty($user->socid) || !isModEnabled('cdav')) {
	accessforbidden();
}
$action = GETPOST('action', 'aZ09');
$hookmanager->initHooks(array('cdavsetup', 'globalsetup'));
$settings = cdavSettingsDefinition($cdavAdminTab);
$submitted = array();
$errors = array();
if ($action === 'update') {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		accessforbidden();
	}
	// main.inc.php checks the CSRF token before reaching this action.
	foreach ($settings as $key => $definition) {
		if (GETPOSTISSET($key)) {
			$value = GETPOST($key, 'alphanohtml');
			$submitted[$key] = is_string($value) ? trim($value) : '!invalid!';
			if ($submitted[$key] === '-1' && in_array($definition['type'], array('service', 'contactcategory', 'productcategory', 'role'), true)) $submitted[$key] = '';
		}
	}
	$errors = cdavValidateSettings($db, $cdavAdminTab, $submitted);
	if (!$errors) {
		$db->begin();
		foreach ($submitted as $key => $value) {
			if (dolibarr_set_const($db, $key, $value, 'chaine', 0, '', (int) $conf->entity) < 0) {
				$errors[] = $langs->trans('SetupNotSaved');
				break;
			}
		}
		if ($errors) {
			$db->rollback();
		} else {
			$db->commit();
			setEventMessages($langs->trans('SetupSaved'), null, 'mesgs');
			header('Location: '.dol_buildpath('/cdav/admin/'.$cdavAdminTab.'.php', 1));
			exit;
		}
	}
	setEventMessages('', $errors, 'errors');
}
$moduleDescriptor = new modCDav($db);
$title = $langs->trans('CDavSetup');
$linkback = '<a href="'.DOL_URL_ROOT.'/admin/modules.php?search_keyword=cdav">'.$langs->trans('BackToModuleList').'</a>';
llxHeader('', $title);
print load_fiche_titre($title, $linkback, 'title_setup');
print dol_get_fiche_head(cdavAdminPrepareHead(), $cdavAdminTab, $title, -1, 'technic');
if (isModEnabled('multicompany')) {
	print '<div class="opacitymedium">'.$langs->trans('CDavEntitySettings', (int) $conf->entity).'</div><br>';
}
