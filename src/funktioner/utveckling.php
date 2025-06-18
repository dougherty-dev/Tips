<?php

declare(strict_types=1);

function dumpa_text(string $text): void {
	file_put_contents(BAS . "/dump.txt", $text, FILE_APPEND);
}

/**
 * Dumpa objekt
 * @SuppressWarnings("PHPMD.DevelopmentCodeFragment")
 */
function dumpa_objekt(object $kod): string {
	ob_start();
	print_r($kod);
	$text = (string) ob_get_contents();
	ob_end_clean();
	return $text;
}
