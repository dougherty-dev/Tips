<?php

declare(strict_types=1);

/**
 * Generera tipsrader rekursivt-generativt-iterativt.
 * Snabbast, effektivast och minnessnålast.
 * @param array<int, int[]> $rymd
 * @return generator<string>
 */
function generera(array $rymd, int $antal = MATCHANTAL): generator {
	if ($antal === 0) {
		return yield '';
	}

	foreach (generera($rymd, $antal - 1) as $tecken1) {
		foreach ($rymd[$antal - 1] as $tecken2) {
			yield $tecken1 . $tecken2;
		}
	}
}
