<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
  <?
  $menuItems = [];
  $currentRootIndex = null;

  foreach ($arResult as $arItem) {
    if ($arItem["DEPTH_LEVEL"] == 1) {
      $arItem["CHILDREN"] = [];
      $menuItems[] = $arItem;
      $currentRootIndex = count($menuItems) - 1;
      continue;
    }

    if ($arItem["DEPTH_LEVEL"] == 2 && $currentRootIndex !== null) {
      $menuItems[$currentRootIndex]["CHILDREN"][] = $arItem;
    }
  }
  ?>

  <nav class="header-nav"
       aria-label="Основное меню"
  >
    <ul class="header-nav__list">
      <? foreach ($menuItems as $arItem): ?>
        <?
        $hasChildren = !empty($arItem["CHILDREN"]);
        $hasSelectedChild = false;
        $isDenied = $arItem["PERMISSION"] <= "D";
        $itemClass = "header-nav__item";
        $linkClass = "header-nav__link menu-18";

        if ($hasChildren) {
          foreach ($arItem["CHILDREN"] as $arChild) {
            if ($arChild["SELECTED"]) {
              $hasSelectedChild = true;
              break;
            }
          }
        }

        if ($hasChildren) {
          $itemClass .= " header-nav__item--parent";
          $linkClass .= " header-nav__link--parent";
        }

        if ($arItem["SELECTED"] || $hasSelectedChild) {
          $itemClass .= " header-nav__item--selected";
          $linkClass .= " header-nav__link--selected";
        }

        if ($isDenied) {
          $linkClass .= " header-nav__link--denied";
        }
        ?>

        <li class="<?= $itemClass ?>">
          <a
            class="<?= $linkClass ?>"
            href="<?= $isDenied ? "" : $arItem["LINK"] ?>"
            <? if ($isDenied): ?>title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"<? endif; ?>
          >
            <span class="header-nav__link-text"><?= htmlspecialcharsbx($arItem["TEXT"]) ?></span>
            <? if ($hasChildren): ?>
              <span class="header-nav__icon"
                    aria-hidden="true"
              >
                <svg width="24"
                     height="24"
                     viewBox="0 0 24 24"
                     focusable="false"
                >
                  <path d="M6 9L12 15L18 9" />
                </svg>
              </span>
            <? endif; ?>
          </a>

          <? if ($hasChildren): ?>
            <ul class="header-nav__dropdown">
              <? foreach ($arItem["CHILDREN"] as $arChild): ?>
                <?
                $isChildDenied = $arChild["PERMISSION"] <= "D";
                $childLinkClass = "header-nav__dropdown-link menu-18";

                if ($arChild["SELECTED"]) {
                  $childLinkClass .= " header-nav__dropdown-link--selected";
                }

                if ($isChildDenied) {
                  $childLinkClass .= " header-nav__dropdown-link--denied";
                }
                ?>
                <li class="header-nav__dropdown-item">
                  <a
                    class="<?= $childLinkClass ?>"
                    href="<?= $isChildDenied ? "" : $arChild["LINK"] ?>"
                    <? if ($isChildDenied): ?>title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"<? endif; ?>
                  >
                    <?= htmlspecialcharsbx($arChild["TEXT"]) ?>
                  </a>
                </li>
              <? endforeach; ?>
            </ul>
          <? endif; ?>
        </li>
      <? endforeach; ?>
    </ul>
  </nav>
<? endif; ?>
