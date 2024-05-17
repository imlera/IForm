<? use Bitrix\Main\Config\Option, Bitrix\Main\Mail\Event;
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

try {
    if (!$_POST['EVENT_CODE']) throw new ErrorException('Не найден тип почтового события');

    #region Добавление элемента
    $elementDate = date('d.m.Y');
    $arElementFields['NAME'] = "Сообщение формы от $elementDate";
    $arElementFields['IBLOCK_ID'] = $_POST['IBLOCK_ID'];
    $arElementFields['PROPERTY_VALUES'] = $_POST;

    $elementId = addElement($arElementFields);
    #endregion

    if (!$elementId) throw new ErrorException('Не удалось создать элемент результата');

    #region Отправка почтового шаблона
    $arEventFields['DETAIL_PAGE'] = getElementUrlById($elementId);
    $arEventFields['EMAIL'] = Option::get('main', 'email_from');
    $arEventFields = array_merge($arEventFields, $_POST);

    $rsEvent = Event::send(array(
        'LID' => 's1',
        'EVENT_NAME' => $_POST['EVENT_CODE'],
        'C_FIELDS' => $arEventFields,
    ));

    if (!$rsEvent->GetId()) {
        $message = implode(', ', $rsEvent->getErrorMessages());
        throw new ErrorException($message);
    }
    #endregion

    outputSuccess();
} catch (Exception|ErrorException $exception) {
    outputError($exception->getMessage());
}