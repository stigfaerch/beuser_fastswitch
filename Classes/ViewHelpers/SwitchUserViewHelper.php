<?php

declare(strict_types=1);

namespace JosefGlatz\BeuserFastswitch\ViewHelpers;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Displays 'SwitchUser' link with sprite icon to change current backend user to target backendUser
 *
 * This ViewHelper is basically a clone of \TYPO3\CMS\Beuser\ViewHelpers\SwitchUserViewHelper.
 * As the mentioned core VH is marked as internal, the modified version was added to this
 * extension as an own standalone VH.
 */
class SwitchUserViewHelper extends AbstractViewHelper
{
    /**
     * As this ViewHelper renders HTML, the output must not be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function __construct(
        protected readonly IconFactory $iconFactory,
        protected readonly LanguageServiceFactory $languageServiceFactory,
    ) {
    }

    /**
     * Initializes the arguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('backendUser', BackendUser::class, 'Target backendUser to switch active session to', true);
        $this->registerArgument('class', 'string', 'Css class(es) for <a\\/> tag', false);
    }

    /**
     * Render link with sprite icon to change current backend user to target
     *
     * @return string
     */
    public function render(): string
    {
        $targetUser = $this->arguments['backendUser'];
        $currentUser = $this->getBackendUserAuthentication();

        if ((int)$targetUser->getUid() === (int)($currentUser->user[$currentUser->userid_column] ?? 0)
            || !$targetUser->isActive()
            || !$currentUser->isAdmin()
            || $currentUser->getOriginalUserIdWhenInSwitchUserMode() !== null
        ) {
            return '';
        }

        $targetUserId = (int)$targetUser->getUid();
        $class = htmlspecialchars((string)$this->arguments['class']);
        $icon = $this->iconFactory->getIcon('actions-user-switch', IconSize::SMALL)->render();
        $ll = $this->languageServiceFactory->createFromUserPreferences($currentUser);
        $title = htmlspecialchars($ll->sL('LLL:EXT:beuser_fastswitch/Resources/Private/Language/locallang.xlf:toolbar.beuser.fastswitch.dropdown.user.btn.switch'));

        return '
            <typo3-backend-switch-user targetUser="' . $targetUserId . '">
                <button type="button" class="' . $class . '" title="' . $title . '">'
            . $icon .
            '</button>
            </typo3-switch-user-button>';
    }

    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
