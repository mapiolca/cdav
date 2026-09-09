<?php
/* Copyright (C) 2026 Pierre Ardoin <developpeur@lesmetiersdubatiment.fr>
 * SPDX-License-Identifier: GPL-3.0-or-later
 */

/**
 * Resolve the DAV entity before main.inc.php. No session or query-string override.
 * @param array<string, string> $server HTTP server parameters.
 * @return array{entity: int, segment: string}
 * @throws InvalidArgumentException Malformed entity path.
 */
function cdavParseDavRoute(array $server)
{
	$path = $server['PATH_INFO'] ?? '';
	if ($path === '' && isset($server['REQUEST_URI'], $server['SCRIPT_NAME'])) {
		$uri = explode('?', $server['REQUEST_URI'], 2)[0];
		if (strpos($uri, $server['SCRIPT_NAME']) === 0) {
			$path = substr($uri, strlen($server['SCRIPT_NAME']));
		}
	}
	$first = explode('/', ltrim($path, '/'), 2)[0];
	if ($first === '' || in_array($first, array('principals', 'calendars', 'addressbooks', 'public', 'documents'), true)) {
		return array('entity' => 1, 'segment' => '');
	}
	if (!preg_match('/^[1-9][0-9]{0,8}$/D', $first)) {
		throw new InvalidArgumentException('Invalid DAV entity');
	}
	return array('entity' => (int) $first, 'segment' => '/'.$first);
}
