<?php global $APPLICATION;
if( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true ) die();
if (! CModule::IncludeModule('iblock')) return;

/** @var array $arParams */
/** @var array $arResult */

$BID =& $arParams['IBLOCK_ID'];
if (!$BID) return;

$arIBlockFields = getArIBlockById($BID);
foreach ($arIBlockFields as $fieldCode => $fieldValue)
    $arResult["IBLOCK_$fieldCode"] = $fieldValue;

$arResult['QUESTIONS'] = getArIBlockPropertiesById($BID);
if ($arResult['QUESTIONS'] && is_array($arResult['QUESTIONS'])) {
    // Присваивание type
    foreach ($arResult['QUESTIONS'] as &$arQuestion) {
        $code =& $arQuestion['CODE'];
        $userType =& $arQuestion['USER_TYPE'];
        $propType =& $arQuestion['PROPERTY_TYPE'];

        if ($propType == 'S') {
            $arQuestion['FIELD_TYPE'] = 'text';
            if ($userType) {
                if ($userType == 'HTML') $arQuestion['FIELD_TYPE'] = 'html';
                elseif ($userType == 'Date') $arQuestion['FIELD_TYPE'] = 'date';
                elseif ($userType == 'DateTime') $arQuestion['FIELD_TYPE'] = 'datetime-local';
            } elseif ($code == 'EMAIL') $arQuestion['FIELD_TYPE'] = 'email';
            elseif ($code == 'PHONE') $arQuestion['FIELD_TYPE'] = 'tel';
        } elseif ($propType == 'N') $arQuestion['FIELD_TYPE'] = 'number';
        elseif ($propType == 'E') $arQuestion['FIELD_TYPE'] = 'elements';
        elseif ($propType == 'L') {
            $listType =& $arQuestion['LIST_TYPE'];
            if ($listType == 'L') $arQuestion['FIELD_TYPE'] = 'list';
            elseif ($listType == 'C') $arQuestion['FIELD_TYPE'] = 'checkbox';
        } elseif ($propType == 'F') $arQuestion['FIELD_TYPE'] = 'file';
        else continue;
    }

    // Добавление статических скрытых полей
    $arResult['QUESTIONS'][] = formingHiddenQuestion('IBLOCK_ID', $BID);
    $arResult['QUESTIONS'][] = formingHiddenQuestion('PAGE', $APPLICATION->GetCurPage());
    $arResult['QUESTIONS'][] = formingHiddenQuestion('EVENT_CODE', $arParams['EVENT_CODE']);

    // Формирование HTML полей
    foreach ($arResult['QUESTIONS'] as &$arQuestion) {
        $name =& $arQuestion['CODE'];
        $type =& $arQuestion['FIELD_TYPE'];
        $value = htmlspecialchars($arQuestion['VALUE']);

        if ($type === 'hidden') {
            $html = "<input type='$type' name='$name' value='$value'/>";
            $arQuestion['HTML'] = $html;
            continue;
        }

        $id = "form_text_$arQuestion[ID]";
        $multiple = $arQuestion['MULTIPLE'] == 'Y' ? 'multiple' : '';
        $required = $arQuestion['IS_REQUIRED'] == 'Y' ? 'required' : '';

        $placeholder = $arParams["PARAM_PLACEHOLDER_$arQuestion[CODE]"];
        if (!$placeholder && $arParams['NAME_LIKE_PLACEHOLDER'] == 'Y') $placeholder = $arQuestion['NAME'];
        if ($placeholder) $placeholder = "placeholder='$placeholder'";

        $html = '';
        if ($type === 'html') $html = "<textarea id='$id' name='$name' rows='3'>$value</textarea>";
        elseif ($type === 'elements') {
            if ($multiple) $name .= '[]';
            $rsElements = getQuestionElements( $arQuestion['LINK_IBLOCK_ID'] );
            $html = "<select id='$id' name='$name' $multiple $placeholder>";
            while ($arElement = $rsElements->Fetch()) {
                $selected = $arElement['DEF'] == 'Y' ? 'selected' : '';
                $html .= "<option value='$arElement[ID]' $selected>$arElement[NAME]</option>";
            }
            $html .= "</select>";
        } elseif ($type === 'list') {
            if ($multiple) $name .= '[]';
            $rsElements = getQuestionList( $arQuestion['CODE'], $arParams['IBLOCK_ID'] );
            $html = "<select id='$id' name='$name' $multiple $placeholder>";
            while ($arElement = $rsElements->Fetch()) {
                $selected = $arElement['DEF'] == 'Y' ? 'selected' : '';
                $html .= "<option value='$arElement[ID]' $selected>$arElement[VALUE]</option>";
            }
            $html .= "</select>";
        } else $html = "<input type='$type' id='$id' name='$name' value='$value' $placeholder $required/>";

        $arQuestion['HTML'] = $html;
    }
}

$this->initComponentTemplate();
$this->IncludeComponentTemplate();