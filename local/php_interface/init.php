<?php

/**
 * @param int $iBlockId ID ИБ
 * @return array Массив параметров ИБ
 */
function getArIBlockById(int $iBlockId): array
{
    return CIBlock::GetArrayByID($iBlockId);
}

/**
 * @param int $iBlockId ID ИБ
 * @return array Массив свойств ИБ
 */
function getArIBlockPropertiesById(int $iBlockId): array
{
    $arIBlockProps = array();
    $rsIBlockProps = CIBlock::GetProperties($iBlockId, array('SORT' => 'ASC'), array('ACTIVE' => 'Y'));
    while ($arIBlockProp = $rsIBlockProps->Fetch())
        $arIBlockProps[] = $arIBlockProp;

    return $arIBlockProps;
}

/**
 * @param int $iBlockId ID ИБ
 * @param string $code Код параметра ИБ
 * @return mixed Значение параметра ИБ по переданному коду
 */
function getIBlockFieldByCode(int $iBlockId, string $code): mixed
{
    return getArIBlockById($iBlockId)[$code] ?? null;
}

/**
 * @param int $fileID ID файла
 * @return string Полный путь к файлу от корня сайта
 */
function getFilePatchById(int $fileID): string
{
    return CFile::GetPath($fileID) ?? SITE_TEMPLATE_PATH . '/assets/imgs/not-found.jpg';
}

/**
 * @param array $arFields Параметры элемента
 * @return int ID созданного элемента
 */
function addElement(array $arFields): int
{
    try {
        if (!CModule::IncludeModule('iblock')) throw new ErrorException('No include module');

        $element = new CIBlockElement;
        if ($elementId = $element->Add($arFields)) return (int)$elementId;
        else throw new ErrorException($element->LAST_ERROR);
    } catch (Exception $exception) {
        outputError( $exception->getMessage() );
    }
}

/**
 * @param int $elementId ID элемента
 * @return string Ссылка на детальную страницу элемента в админ панели
 */
function getElementUrlById(int $elementId): string
{
    try {
        if (!CModule::IncludeModule('iblock')) throw new ErrorException('No include module');

        $rsElement = CIBlockElement::GetList(array(), array('ID' => $elementId), false, array('nTopCount' => 1), array('IBLOCK_ID', 'IBLOCK_TYPE_ID'));
        $arElement = $rsElement->Fetch();

        if (!$arElement) throw new ErrorException('Element not found');
        return "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=$arElement[IBLOCK_ID]&type=$arElement[IBLOCK_TYPE_ID]&lang=" . LANGUAGE_ID . "&ID=$elementId";
    } catch (Exception $exception) {
        outputError( $exception->getMessage() );
    }
}


@include ('include/ajax.php');
@include ('include/debug.php');

@include ('include/components/component.php');
@include ('include/components/parameters.php');
@include ('include/components/component.form.php');