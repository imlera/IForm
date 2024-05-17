<?php

function pre(array $arValues): void
{
    echo '<pre>';
    print_r($arValues);
    echo '<pre>';
}

function boolToString(mixed $value): string
{
    return $value ? 'Y' : 'N';
}