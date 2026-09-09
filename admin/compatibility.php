<?php
/* Copyright (C) 2026 Pierre Ardoin <developpeur@lesmetiersdubatiment.fr>
 * SPDX-License-Identifier: GPL-3.0-or-later
 */
$cdavAdminTab = 'compatibility';
require __DIR__.'/_common.php';
print '<div class="div-table-responsive-no-min"><table class="noborder centpercent">';
print '<tr class="liste_titre"><th>'.$langs->trans('Parameter').'</th><th>'.$langs->trans('Value').'</th></tr>';
foreach (array('Dolibarr' => DOL_VERSION, 'PHP' => PHP_VERSION, 'CDavMinimum' => 'Dolibarr '.implode('.', $moduleDescriptor->need_dolibarr_version).' / PHP '.implode('.', $moduleDescriptor->phpmin), 'CDavTarget' => 'Dolibarr 16–24') as $key => $value) {
	print '<tr class="oddeven"><td>'.$langs->trans($key).'</td><td>'.dol_escape_htmltag($value).'</td></tr>';
}
print '</table></div><br>';
print '<div class="div-table-responsive-no-min"><table class="noborder centpercent">';
print '<tr class="liste_titre"><th>'.$langs->trans('CDavCapability').'</th><th>'.$langs->trans('Status').'</th><th>'.$langs->trans('Description').'</th></tr>';
foreach (CDavCompatibility::getFeatures() as $feature) {
	print '<tr class="oddeven"><td>'.$langs->trans($feature['label']).'</td><td>';
	print $langs->trans($feature['available'] ? 'CDavAvailable' : 'CDavUnavailable');
	print '</td><td>'.($feature['available'] ? '' : $langs->trans($feature['reason'])).'</td></tr>';
}
print '</table></div><br>';
print '<p>'.$langs->trans('CDavVersionPolicy').'</p>';
print '<p>'.$langs->trans('CDavManualChecks').'</p>';
print '<p>'.$langs->trans('CDavMulticompanyChecks').'</p>';
print '<p>'.$langs->trans('CDavTransverseMode').': '.$langs->trans(isModEnabled('multicompany') && getDolGlobalInt('MULTICOMPANY_TRANSVERSE_MODE') ? 'Yes' : 'No').'</p>';
print dol_get_fiche_end();
llxFooter();
$db->close();
