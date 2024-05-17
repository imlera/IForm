<?php
use JetBrains\PhpStorm\ExpectedValues;
use Bitrix\Main\Mail\Internal\EventTypeTable,
    Bitrix\Main\SystemException,
    Bitrix\Main\ArgumentException,
    Bitrix\Main\ObjectPropertyException;

/**
 * @param string $parent Код группы параметров
 * @param string|null $name Название параметра
 * @param string $type Тип элемента управления
 * @param string|null $default Значение по умолчанию
 * @param string $refresh Перезагружать ли настройки после выбора
 * @param string $multiple Содержит ли множество значений
 * @param string $additionalValues Показывать ли поле для значений, вводимых вручную
 * @param array $values Массив значений для списка
 * @param array $additional Массив дополнительных параметров
 * @return array Массив параметра компонента
 */
function getArParameter(
    string $parent = 'ADDITIONAL_SETTINGS',
    ?string $name = null,
    #[ExpectedValues(['STRING', 'CHECKBOX', 'FILE', 'LIST', 'COLORPICKER', 'CUSTOM'])] string $type = 'STRING',
    ?string $default = null,
    #[ExpectedValues(['Y', 'N'])] string $refresh = 'N',
    #[ExpectedValues(['Y', 'N'])] string $multiple = 'N',
    #[ExpectedValues(['Y', 'N'])] string $additionalValues = 'N',
    array $values = array(),
    array $additional = array(),
): array
{
    return array(
        'PARENT' => $parent,
        'NAME' => $name,
        'TYPE' => $type,
        'DEFAULT' => $default,
        'REFRESH' => $refresh,
        'MULTIPLE' => $multiple,
        'ADDITIONAL_VALUES' => $additionalValues,
        'VALUES' => $values,
        ...$additional
    );
}

/**
 * @param string $parent Код группы параметров
 * @param string|null $name Заголовок параметра
 * @param string|null $default Значение по умолчанию
 * @param array|null $values Массив значений списка
 * @param string $refresh Перезагружать ли настройки после выбора
 * @param string $multiple Содержит ли множество значений
 * @param string $additionalValues Показывать ли поле для значений, вводимых вручную
 * @return array Массив параметра списка
 */
function getArParameterList(
    string $parent = 'ADDITIONAL_SETTINGS',
    ?string $name = null,
    ?string $default = null,
    ?array $values = array(),
    #[ExpectedValues(['Y', 'N'])] string $refresh = 'N',
    #[ExpectedValues(['Y', 'N'])] string $multiple = 'N',
    #[ExpectedValues(['Y', 'N'])] string $additionalValues = 'N',
): array
{
    $name = GetMessage($name) ?? $name;
    $default = GetMessage($default) ?? $default;

    return getArParameter(parent: $parent, name: $name, type: 'LIST', default: $default, refresh: $refresh, multiple: $multiple, additionalValues: $additionalValues, values: $values);
}

/**
 * @param string $parent Код группы параметров
 * @param string|null $name Заголовок параметра
 * @param string $default Значение по умолчанию
 * @return array Массив параметра checkbox
 */
function getArParameterCheckbox(
    string $parent = 'ADDITIONAL_SETTINGS',
    ?string $name = null,
    #[ExpectedValues(['Y', 'N'])] string $default = 'N',
): array
{
    $name = GetMessage($name) ?? $name;
    return getArParameter(parent: $parent, name: $name, type: 'CHECKBOX', default: $default, refresh: 'Y');
}

/**
 * @param string $parent Код группы параметров
 * @param string|null $name Заголовок параметра
 * @param string|null $default Значение по умолчанию
 * @return array Массив параметра изображения
 */
function getArParameterPicture(
    string $parent = 'ADDITIONAL_SETTINGS',
    ?string $name = null,
    ?string $default = null,
): array
{
    $name = GetMessage($name) ?? $name;
    $default = GetMessage($default) ?? $default;
    $arAdditional = array(
        'FD_TARGET' => 'F',
        'FD_UPLOAD' => true,
        'FD_USE_MEDIALIB' => true,
        'FD_EXT' => 'jpg,jpeg,gif,png,webp,avif',
        'FD_MEDIALIB_TYPES' => array('image'),
    );

    return getArParameter(parent: $parent, name: $name, type: 'FILE', default: $default, additional: $arAdditional);
}

/**
 * @param string $parent Код группы параметров
 * @param string|null $name Заголовок параметра
 * @param string|null $default Значение по умолчанию
 * @return array Массив параметра описания
 */
function getArParameterTextarea(
    string $parent = 'ADDITIONAL_SETTINGS',
    ?string $name = null,
    ?string $default = null,
): array
{
    $name = GetMessage($name) ?? $name;
    $default = GetMessage($default) ?? $default;
    $arAdditional = array(
        'JS_FILE' => '/local/templates/sprod/assets/js/parameters.js',
        'JS_EVENT' => 'OnTextAreaConstruct',
    );

    return getArParameter(parent: $parent, name: $name, type: 'CUSTOM', default: $default, additional: $arAdditional);
}

/**
 * @param string $parent Код группы параметров
 * @param string|null $name Название параметра
 * @param string|null $default Значение по умолчанию
 * @return array
 */
function getArParameterString(
    string $parent = 'ADDITIONAL_SETTINGS',
    ?string $name = null,
    ?string $default = null,
): array
{
    $name = GetMessage($name) ?? $name;
    $default = GetMessage($default) ?? $default;

    return getArParameter(parent: $parent, name: $name, default: $default);
}

/**
 * @return array Массив кодов почтовых событий
 */
function getArParameterEventNames(): array
{
    $arEventNames = array();

    try {
        $rsEventNames = EventTypeTable::getList(array('select' => array('EVENT_NAME', 'NAME')));
        while ($arEventName = $rsEventNames->fetch())
            $arEventNames[$arEventName['EVENT_NAME']] = "[$arEventName[EVENT_NAME]] $arEventName[NAME]";
    } catch (ObjectPropertyException|ArgumentException|SystemException $exception) {
        global $APPLICATION;
        $APPLICATION->ThrowException($exception->getMessage());
        return array();
    }

    return $arEventNames;
}

/**
 * @param string $iBlockType Тип ИБ
 * @return array Массив ID ИБ внутри типа ИБ
 */
function getArParameterIBlockIDs(string $iBlockType): array
{
    $arIBlocks = array();
    $rsIBlocks = CIBlock::GetList(array('SORT' => 'ASC'), array('TYPE' => $iBlockType, 'ACTIVE' => 'Y'));
    while ($arIBlock = $rsIBlocks->Fetch()) $arIBlocks[ $arIBlock['ID'] ] = "[$arIBlock[CODE]] $arIBlock[NAME]";

    return $arIBlocks;
}