<?php

declare(strict_types=1);

function dumpa_text(string $text): void {
	file_put_contents(BAS . "/dump.txt", $text, FILE_APPEND);
}
