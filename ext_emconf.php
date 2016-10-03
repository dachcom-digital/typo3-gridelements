<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Gridelementsfix',
	'description' => 'Fix for Gridelements translation handling',
	'category' => 'misc',
    'author' => 'DACHCOM.DIGITAL AG',
    'author_email' => 'digital-development@dachcom.ch',
    'author_company' => 'DACHCOM.DIGITAL AG',
    'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '0.2.0',
	'constraints' => array(
    	'depends' => array(
            'typo3' => '7.6.0-7.6.99',
            'gridelements' => '*'
        ),
    	'conflicts' => array(
    	),
    	'suggests' => array(
    	),
	),
);
