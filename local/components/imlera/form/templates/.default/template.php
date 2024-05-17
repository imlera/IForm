<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$arQuestions =& $arResult['QUESTIONS'];
if (!$arQuestions) return; ?>

<? if ($arParams['SHOW_NAME'] == 'Y' && $arParams['PARAM_NAME']): ?>
    <? if ($APPLICATION->GetCurPage() == '/' || defined('ERROR_404')): ?>
        <h2 data-aos-once="true" data-aos="fade-right">
            <?= htmlspecialchars_decode($arParams['PARAM_NAME']) ?>
        </h2>
    <? else: ?>
        <h2><?= htmlspecialchars_decode($arParams['PARAM_NAME']) ?></h2>
    <? endif; ?>
<? endif; ?>

<div class="form-footer">
    <form id="form-footer" class="js-form-component">
        <? foreach ($arQuestions as $arQuestion): ?>
            <? if ($arQuestion['FIELD_TYPE'] === 'hidden'): ?>
                <?= $arQuestion['HTML'] ?>
            <? else: ?>
                <div class="wrap-input">
                    <?= $arQuestion['HTML'] ?>
                </div>
            <? endif; ?>
        <? endforeach; ?>

        <button type="submit" class="pointer">
            <?= $arParams['SEND_BUTTON_NAME'] ?>
        </button>

        <? if ($arParams['SHOW_LICENCE'] == 'Y'): ?>
            <div class="warning-text">
                <? includeFile(SITE_DIR . 'include/form/show_licence.php'); ?>
            </div>
        <? endif; ?>
    </form>
</div>
