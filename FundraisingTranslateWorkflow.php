<?php

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'FundraisingTranslateWorkflow',
	'author' => array(
		'Adam Roses Wight',
	),
	'version' => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:FundraisingTranslateWorkflow',
	'descriptionmsg' => 'fundraising-translate-workflow-desc',
	'license-name' => 'GPLv2',
);

$wgMessagesDirs['FundraisingTranslateWorkflow'] = __DIR__ . '/i18n';

$wgAutoloadClasses['FundraisingTranslateWorkflow\FundraisingMessageGroup'] = __DIR__ . '/FundraisingMessageGroup.php';

$wgHooks['TranslatePostInitGroups'][] = 'FundraisingTranslateWorkflow\FundraisingMessageGroup::onTranslatePostInitGroups';

/**
 * Message group titles matching these regexes will be wrapped by our hideous thing.
 */
$wgFundraisingTranslateWorkflowPagePatterns = array(
	'|^page-Fundraising/|',
);

/**
 * Permission needed to set our message groups to "published".
 */
$wgFundraisingTranslateWorkflowPublishRight = 'translate-manage';
