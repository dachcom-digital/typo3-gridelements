<?php
namespace Dachcom\Gridelementsfix\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class DataHandler implements \TYPO3\CMS\Core\SingletonInterface {
    public function processDatamap_afterAllOperations($reference) {
        $table = 'tt_content';

        if (isset($reference->datamap[$table])) {
            foreach ($reference->datamap[$table] as $recordUid => $content) {
                if (!is_numeric($recordUid) && isset($reference->substNEWwithIDs[$recordUid])) {
                    $recordUid = $reference->substNEWwithIDs[$recordUid];
                }

                $record = BackendUtility::getRecord($table, $recordUid);

                if ((int)$record['l18n_parent'] > 0) {
                    $row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
                        'tx_gridelements_columns',
                        'tt_content',
                        "uid=" . $record['l18n_parent'] . BackendUtility::deleteClause('tt_content')
                    );
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, 'uid=' . $record['uid'], array(
                        'tx_gridelements_columns' => $row['tx_gridelements_columns'],
                    ));
                }
            }
        }
    }
}
