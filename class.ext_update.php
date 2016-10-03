<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Update class for the extension manager.
 * @package    TYPO3
 * @subpackage gridelementsfix
 */
class ext_update {
    /**
     * Array of flash messages (params) array[][status,title,message]
     * @var array
     */
    protected $messageArray = [];

    /**
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * Constructor
     */
    public function __construct() {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Main update function called by the extension manager.
     * @return string
     */
    public function main() {
        $this->processUpdates();
        return $this->generateOutput();
    }

    /**
     * Called by the extension manager to determine if the update menu entry
     * should by showed.
     * @return bool
     * @todo find a better way to determine if update is needed or not.
     */
    public function access() {
        return TRUE;
    }

    /**
     * The actual update function. Add your update task in here.
     * @return void
     */
    protected function processUpdates() {
        $this->updateTtContent();
    }

    /**
     *  Updates gridelements columns
     * @return void
     */
    protected function updateTtContent() {
        $elements = $this->databaseConnection->exec_SELECTgetRows('uid, l18n_parent', 'tt_content', 'deleted = 0 AND l18n_parent > 0');

        if (!count($elements)) {
            return;
        }

            $proccessed = count($elements);

        foreach ($elements as $element) {
            $gridColumn = $this->databaseConnection->exec_SELECTgetSingleRow('tx_gridelements_columns', 'tt_content', 'uid = ' . $element['l18n_parent']);

            $this->databaseConnection->exec_UPDATEquery('tt_content', 'uid=' . $element['uid'], array(
                'tx_gridelements_columns' => $gridColumn['tx_gridelements_columns'],
            ));
        }

        $message = 'Proccessed ' . $proccessed . ' possibly wrong content elements';
        $status = \TYPO3\CMS\Core\Messaging\FlashMessage::INFO;
        $title = '';
        $this->messageArray[] = [$status, $title, $message];
    }

    /**
     * Generates output by using flash messages
     * @return string
     */
    protected function generateOutput() {
        $output = '';
        foreach ($this->messageArray as $messageItem) {
            /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class, $messageItem[2], $messageItem[1], $messageItem[0]);
            $output .= $flashMessage->render();
        }
        return $output;
    }

}
