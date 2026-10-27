<?php

declare(strict_types=1);

/**
 * Global helper usage baseline check.
 *
 * Fails when new calls to helpers defined in helpers.php are added to src/.
 * Run with --update to regenerate tools/helper-baseline.json intentionally.
 */

$root = dirname(__DIR__);
$helpersFile = $root . '/helpers.php';
$baselineFile = $root . '/tools/helper-baseline.json';
$scanPath = $root . '/src';
$update = in_array('--update', $argv, true);

if (!is_file($helpersFile)) {
    fwrite(STDERR, "helpers.php not found: {$helpersFile}\n");
    exit(2);
}

preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', (string) file_get_contents($helpersFile), $matches);
$helpers = array_values(array_unique($matches[1]));
sort($helpers);

$calls = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($scanPath, FilesystemIterator::SKIP_DOTS)) as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $relative = substr($file->getPathname(), strlen($root) + 1);
    $tokens = token_get_all((string) file_get_contents($file->getPathname()));
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        $token = $tokens[$i];
        if (!is_array($token) || $token[0] !== T_STRING) {
            continue;
        }

        $name = strtolower($token[1]);
        if (!in_array($name, $helpers, true)) {
            continue;
        }

        $next = $i + 1;
        while ($next < $count && is_array($tokens[$next]) && in_array($tokens[$next][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
            $next++;
        }
        if ($next >= $count || $tokens[$next] !== '(') {
            continue;
        }

        $prev = $i - 1;
        while ($prev >= 0 && is_array($tokens[$prev]) && in_array($tokens[$prev][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
            $prev--;
        }
        $previous = $prev >= 0 ? $tokens[$prev] : null;
        if (is_array($previous) && in_array($previous[0], [T_FUNCTION, T_NEW, T_OBJECT_OPERATOR, T_DOUBLE_COLON], true)) {
            continue;
        }
        if (!is_array($previous) && in_array($previous, ['->', '::'], true)) {
            continue;
        }

        $calls[] = sprintf('%s:%d %s()', $relative, $token[2], $name);
    }
}

$byHelper = [];
foreach ($calls as $call) {
    preg_match('/\s([a-z_]+)\(\)$/', $call, $m);
    $byHelper[$m[1]] = ($byHelper[$m[1]] ?? 0) + 1;
}
ksort($byHelper);

$current = ['total' => count($calls), 'by_helper' => $byHelper];

if ($update) {
    file_put_contents($baselineFile, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    fwrite(STDOUT, "Updated baseline: {$baselineFile}\n");
    exit(0);
}

$baseline = is_file($baselineFile) ? json_decode((string) file_get_contents($baselineFile), true) : null;
if (!is_array($baseline) || !isset($baseline['total'], $baseline['by_helper'])) {
    fwrite(STDERR, "Invalid baseline: {$baselineFile}\n");
    exit(2);
}

$violations = [];
if ($current['total'] > $baseline['total']) {
    $violations[] = sprintf('total helper calls: %d > baseline %d', $current['total'], $baseline['total']);
}

foreach ($byHelper as $helper => $count) {
    $allowed = (int) ($baseline['by_helper'][$helper] ?? 0);
    if ($count > $allowed) {
        $violations[] = sprintf('%s(): %d > baseline %d', $helper, $count, $allowed);
    }
}

if ($violations !== []) {
    fwrite(STDERR, "Global helper usage increased:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, '  - ' . $violation . "\n");
    }
    fwrite(STDERR, "\nNew calls:\n");
    foreach ($calls as $call) {
        fwrite(STDERR, '  - ' . $call . "\n");
    }
    fwrite(STDERR, "\nUse dependency injection or update tools/helper-baseline.json intentionally.\n");
    exit(1);
}

fwrite(STDOUT, sprintf("OK: %d helper call(s), baseline %d\n", $current['total'], $baseline['total']));
if ($current['total'] < $baseline['total']) {
    fwrite(STDOUT, "Tip: baseline can be lowered with --update.\n");
}
