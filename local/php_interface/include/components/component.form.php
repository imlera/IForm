<?php

/**
 * @param int $iBlockId ID ИБ
 * @return int|CIBlockResult Запрос на вывод элементов поля
 */
function getQuestionElements(int $iBlockId): int|CIBlockResult
{
    $arOrder = array('SORT' => 'ASC', 'ID' => 'ASC');
    $arSelect = array('ID', 'NAME');
    $arFilter = array('IBLOCK_ID' => $iBlockId, 'ACTIVE' => 'Y');

    return CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
}

/**
 * @param string $questionCode Код вопроса (свойства ИБ)
 * @param int $iBlockId ID ИБ
 * @return bool|int|CIBlockResult|null Запрос на вывод списка свойства
 */
function getQuestionList(string $questionCode, int $iBlockId): null|bool|int|CIBlockResult
{
    $arOrder = array('SORT' => 'ASC', 'ID' => 'ASC');
    $arFilter = array('IBLOCK_ID' => $iBlockId);

    return CIBlockProperty::GetPropertyEnum($questionCode, $arOrder, $arFilter);
}

/**
 * @param string $name Код (имя поля)
 * @param bool|int|string $value Значение поля
 * @return array Массив параметров скрытого поля (вопроса формы)
 */
function formingHiddenQuestion(string $name, bool|int|string $value): array
{
    return array('CODE' => $name, 'VALUE' => $value, 'FIELD_TYPE' => 'hidden');
}