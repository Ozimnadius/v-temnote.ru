<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="video-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$videoSrc = "";
			$videoProperty = $arItem["DISPLAY_PROPERTIES"]["UF_VIDEO"] ?? $arItem["PROPERTIES"]["UF_VIDEO"] ?? null;

			if (is_array($videoProperty)) {
				$videoSrc = $videoProperty["VALUE"] ?? $videoProperty["DISPLAY_VALUE"] ?? "";
			}

			if (is_array($videoSrc)) {
				$videoSrc = reset($videoSrc);
			}

			if (is_numeric($videoSrc) && class_exists("CFile")) {
				$videoSrc = CFile::GetPath((int)$videoSrc);
			}

			$posterSrc = "";
			if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"]) && !empty($arItem["PREVIEW_PICTURE"]["SRC"])) {
				$posterSrc = $arItem["PREVIEW_PICTURE"]["SRC"];
			}
			?>
      <?
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
      ?>
			<?if($videoSrc):?>
				<article class="video-list__item"
                 id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]):?>
						<h2 class="video-list__title"><?=($arItem["~NAME"])?></h2>
					<?endif;?>

					<div class="video-list__row">
						<div class="video-list__media">
							<video class="video-list__video"
								<?if($posterSrc):?> poster="<?=($posterSrc)?>"<?endif;?>
								controls=""
								playsinline
								preload="metadata"
							>
								<source src="<?=($videoSrc)?>" type="video/mp4">
							</video>
              
              <svg class="video-list__play" width="104" height="104" viewBox="0 0 104 104" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M43.3327 71.5001V32.5001L69.3327 52.0001M51.9993 8.66675C46.3087 8.66675 40.6738 9.7876 35.4164 11.9653C30.159 14.143 25.3819 17.3349 21.3581 21.3588C13.2315 29.4854 8.66602 40.5074 8.66602 52.0001C8.66602 63.4928 13.2315 74.5148 21.3581 82.6414C25.3819 86.6652 30.159 89.8572 35.4164 92.0349C40.6738 94.2126 46.3087 95.3334 51.9993 95.3334C63.4921 95.3334 74.5141 90.7679 82.6406 82.6414C90.7672 74.5148 95.3327 63.4928 95.3327 52.0001C95.3327 46.3095 94.2118 40.6746 92.0341 35.4171C89.8564 30.1597 86.6645 25.3827 82.6406 21.3588C78.6168 17.3349 73.8397 14.143 68.5823 11.9653C63.3249 9.7876 57.69 8.66675 51.9993 8.66675Z" fill="currentColor"/>
              </svg>

            </div>

						<?if($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"]):?>
							<div class="video-list__text text-16">
								<?=$arItem["PREVIEW_TEXT"]?>
							</div>
						<?endif;?>
					</div>
				</article>
			<?endif;?>
		<?endforeach;?>
	</div>
<?endif;?>
