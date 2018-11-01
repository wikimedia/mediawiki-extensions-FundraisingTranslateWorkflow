<?php
namespace FundraisingTranslateWorkflow;

use \IContextSource;
use \MessageGroup;
use \MessageGroupStates;
use \WikiPageMessageGroup;

/**
 * Wrapper to modify message group behavior
 *
 * FIXME: all a kludge.  Square peg, jagged hole.
 */
class FundraisingMessageGroup
	extends WikiPageMessageGroup
	// TODO: ideally would be "implements MessageGroup"
{

	/** @var MessageGroup */
	private $group;

	/**
	 * TranslatePostInitGroups hook handler
	 *
	 * Wrap translation groups we want to mutate.
	 *
	 * @param array &$groups
	 * @param array &$deps
	 * @param array &$autoload
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

	/**
	 * @param MessageGroup $group
	 */
	public function __construct( $group ) {
		$this->group = $group;
	}

	/**
	 * Modify workflow permissions
	 * @return MessageGroupStates
	 */
	public function getMessageGroupStates() {
		global $wgFundraisingTranslateWorkflowPublishRight;

		$states = $this->group->getMessageGroupStates()->getStates();
		$conditions = $this->group->getMessageGroupStates()->getConditions();

		// The whole point of this extension:
		$states['published']['right'] = $wgFundraisingTranslateWorkflowPublishRight;

		$states[MessageGroupStates::CONDKEY] = $conditions;

		return new MessageGroupStates( $states );
	}

	/**
	 * Delegate everything else to the pseudo-parent object
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function __call( $method, $args ) {
		return call_user_func_array( [ $this->group, $method ], $args );
	}

	public function getConfiguration() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getId() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getLabel( IContextSource $context = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getDescription( IContextSource $context = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getIcon() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getNamespace() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function isMeta() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function exists() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getFFS() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getChecker() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getMangler() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function initCollection( $code, $unique = false ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function load( $code ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getDefinitions() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getTags( $type = null ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getMessage( $key, $code, $flags = self::READ_LATEST ) {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getSourceLanguage() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getTranslatableLanguages() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getTranslationAids() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getTitle() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}

	public function getInsertablesSuggester() {
		return $this->__call( __FUNCTION__, func_get_args() );
	}
}
