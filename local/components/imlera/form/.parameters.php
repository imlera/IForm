<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
if(!CModule::IncludeModule('iblock')) return;

/** @var array $arCurrentValues */

$arComponentParameters = array();

#region Разделы компонента
$arComponentParameters['GROUPS'] = array(
    'IBLOCK_PARAMS' => array(
        'SORT' => 100,
        'NAME' => GetMessage('GROUP_IBLOCK_PARAMS'),
    ),
    'FORM_PARAMS' => array(
        'SORT' => 200,
        'NAME' => GetMessage('GROUP_FORM_PARAMS'),
    ),
    'PLACEHOLDER_PARAMS' => array(
        'SORT' => 300,
        'NAME' => GetMessage('GROUP_PLACEHOLDER_PARAMS'),
    ),
    'BUTTON_PARAMS' => array(
        'SORT' => 400,
        'NAME' => GetMessage('GROUP_BUTTON_PARAMS'),
    ),
);
#endregion

#region Параметры компонента
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();
$arComponentParameters['PARAMETERS']['IBLOCK_TYPE'] =
    getArParameterList(parent: 'IBLOCK_PARAMS', name: 'PARAM_IBLOCK_TYPE', values: $arIBlockTypes, refresh: 'Y');

$iBlockId = 0;
$iBlockType = $arCurrentValues['IBLOCK_TYPE'] ?? array_keys($arIBlockTypes)[0];
if ($iBlockType) {
    $arIBlockIDs = getArParameterIBlockIDs($iBlockType);
    $iBlockId = (int)($arCurrentValues['IBLOCK_ID'] ?? array_keys($arIBlockIDs)[0]);
    $arComponentParameters['PARAMETERS']['IBLOCK_ID'] =
        getArParameterList(parent: 'IBLOCK_PARAMS', name: 'PARAM_IBLOCK_ID', values: $arIBlockIDs, refresh: 'Y');
}

$arEventCode = getArParameterEventNames();
$arComponentParameters['PARAMETERS']['EVENT_CODE'] =
    getArParameterList(parent: 'FORM_PARAMS', name: 'PARAM_EVENT_CODE', values: $arEventCode);

$arComponentParameters['PARAMETERS']['SHOW_NAME'] =
    getArParameterCheckbox(parent: 'FORM_PARAMS', name: 'PARAM_SHOW_NAME', default: 'Y');
if ($arCurrentValues['SHOW_NAME'] == 'Y' && $iBlockId) {
    $iBlockName = getArIBlockById($iBlockId)['NAME'];
    $arComponentParameters['PARAMETERS']['PARAM_NAME'] =
        getArParameterString(parent: 'FORM_PARAMS', name: 'PARAM_NAME', default: $iBlockName);
}

$arComponentParameters['PARAMETERS']['SHOW_LICENCE'] =
    getArParameterCheckbox(parent: 'FORM_PARAMS', name: 'PARAM_SHOW_LICENCE', default: 'Y');

$arComponentParameters['PARAMETERS']['NAME_LIKE_PLACEHOLDER'] =
    getArParameterCheckbox(parent: 'PLACEHOLDER_PARAMS', name: 'PARAM_NAME_LIKE_PLACEHOLDER', default: 'Y');
if ($iBlockId) {
    $arProperties = getArIBlockPropertiesById($iBlockId);
    foreach ($arProperties as $arProperty)
        $arComponentParameters['PARAMETERS']["PARAM_PLACEHOLDER_$arProperty[CODE]"] =
            getArParameterString(parent: 'PLACEHOLDER_PARAMS', name: $arProperty['NAME']);
}

$arComponentParameters['PARAMETERS']['SEND_BUTTON_NAME'] =
    getArParameterString(parent: 'BUTTON_PARAMS', name: 'PARAM_SEND_BUTTON_NAME', default: 'PARAM_SEND_BUTTON_NAME_DEFAULT');
#endregion