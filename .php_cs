<?php
$header = <<<EOF
Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);
return Symfony\CS\Config\Config::create()
    // use default SYMFONY_LEVEL and extra fixers:
    ->fixers(array(
        'header_comment',
        'short_array_syntax',
        'ordered_use',
        'php_unit_construct',
        'strict',
        'strict_param',
    ))
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->in(__DIR__.'/src')
    )
;