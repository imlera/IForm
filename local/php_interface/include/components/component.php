<?php

/**
 * @param string $pathname Полный путь к файлу, относительно корня сайта
 * @param string $mode Режим редактирования
 * @param array $arProps Передаваемые параметры в подключаемый файл
 * @return void Подключение статических файлов
 */
function includeFile(string $pathname, string $mode = 'html', array $arProps = array()): void
{
    global $APPLICATION;
    $APPLICATION->IncludeFile($pathname, $arProps, array('MODE' => $mode));
}

/**
 * @return void Подключение компонента хлебных крошек
 */
function includeBreadcrumbComponent(): void
{
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        'bitrix:breadcrumb',
        'dash',
        array(
            'PATH' => '',
            'SITE_ID' => 's1',
            'START_FROM' => '0'
        )
    );
}