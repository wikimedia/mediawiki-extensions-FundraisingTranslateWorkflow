<?php
namespace FundraisingTranslateWorkflow;

/**
 * Modifies message group permissions when the group ID matches configuration.
 */
class TranslateHooks {

	/**
	 * Translate:modifyMessageGroupStates hook handler
	 *
	 * @param string $groupId
	 * @param array &$conf
	 */
	public static function onModifyMessageGroupStates( $groupId, &$conf ) {
		global $wgFundraisingTranslateWorkflowPagePatterns,
			$wgFundraisingTranslateWorkflowPublishRight;

		foreach ( $wgFundraisingTranslateWorkflowPagePatterns as $pattern ) {
			if ( preg_match( $pattern, $groupId ) ) {
				$conf['published']['right'] = $wgFundraisingTranslateWorkflowPublishRight;
				break;
			}
		}
	}

	public static function onRegistration() {
		if ( !class_exists( \TranslateUtils::class ) ) {
			// HACK: Declaring a depency on Translate in extension.json requires Translate to have
			// its own extension.json, which is coming but not yet there.
			throw new \ExtensionDependencyError( [ [
				'msg' => 'FundraisingTranslateWorkflow requires Translate to be installed.',
				'type' => 'missing-phpExtension',
				'missing' => 'Translate',
			] ] );
		}
	}
}
