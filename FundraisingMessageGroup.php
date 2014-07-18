<?php
namespace FundraisingTranslateWorkflow;

use \IContextSource;
use \MessageGroup;
use \MessageGroupStates;
use \WikiPageMessageGroup;

/**
 * Wrapper to modify message group behavior
 */
class FundraisingMessageGroup
	extends WikiPageMessageGroup
	// TODO: ideally would be "implements MessageGroup"
{
	/**
	 * TranslatePostInitGroups hook handler
	 *
	 * Wrap translation groups we want to mutate.
	 *
	 * @param array $list
	 * @return bool
	 */
	public static function onTranslatePostInitGroups( &$groups, &$deps, &$autoload ) {
		global $wgFundraisingTranslateWorkflowPagePatterns;

		foreach ( $groups as $name => &$group ) {
			foreach ( $wgFundraisingTranslateWorkflowPagePatterns as $pattern ) {
				if ( preg_match( $pattern, $name ) ) {
					$group = new FundraisingMessageGroup( $group );
					break;
				}
			}
		}

		return true;
	}

	public function __construct( $group ) {
		$this->group = $group;
	}

	/**
	 * Modify workflow permissions
	 */
	public function getMessageGroupStates() {
		global $wgFundraisingTranslateWorkflowPublishRight;

		$states = $this->group->getMessageGroupStates()->getStates();

		$states['published']['right'] = $wgFundraisingTranslateWorkflowPublishRight;

		return new MessageGroupStates( $states );
	}

	/**
	 * Delegate everything else to the pseudo-parent object
	 */
	public function __call( $method, $args ) {
		return call_user_func_array( array( $this->group, $method ), $args );
	}

    function getConfiguration() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getId() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getLabel( IContextSource $context = null ) { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getDescription( IContextSource $context = null ) { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getIcon() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getNamespace() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function isMeta() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function exists() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getFFS() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getChecker() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getMangler() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function initCollection( $code ) { return $this->__call( __FUNCTION__, func_get_args() ); }
    function load( $code ) { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getDefinitions() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getTags( $type = null ) { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getMessage( $key, $code ) { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getSourceLanguage() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getTranslatableLanguages() { return $this->__call( __FUNCTION__, func_get_args() ); }
    function getTranslationAids() { return $this->__call( __FUNCTION__, func_get_args() ); }
}
