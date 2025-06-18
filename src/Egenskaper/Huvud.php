<?php

/**
 * Egenskap Huvud.
 * @author Niklas Dougherty
 */

declare(strict_types=1);

namespace Tips\Egenskaper;

/**
 * Egenskap Huvud.
 */
trait Huvud {
	use Eka;

	/**
	 * Rendera huvud i HTML-kod.
	 */
	private function huvud(): string {
		return '<!doctype html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="/js/jquery-3.7.1.min.js"></script>
	<script src="/js/jquery-ui.1.13.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.1.13.2.min.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.structure.1.13.2.min.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery-ui.theme.1.13.2.min.css">
	<link rel="stylesheet" type="text/css" href="/css/tips.css?' . $this->eka(VERSIONSDATUM) . '">
	<title>Tips ' . $this->eka(VERSION) . '</title>
</head>';
	}
}
