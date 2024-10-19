<?php

namespace FundraisingTranslateWorkflow\Tests;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Tests\Api\ApiTestCase;
use WikiMessageGroup;

/**
 * @coversDefaultClass FundraisingTranslateWorkflow\TranslateHooks
 *
 * @group Database
 * @group medium
 */
class HooksTest extends ApiTestCase {

	private const NORMAL_MESSAGE_GROUP = 'testgroup';
	private const FUNDRAISING_MESSAGE_GROUP = 'page-Fundraising/Foo';

	public function setUp(): void {
		parent::setUp();

		if ( !ExtensionRegistry::getInstance()->isLoaded( 'Translate' ) ) {
			$this->markTestSkipped( 'Can only run test with Extension:Translate enabled' );
		}

		$this->overrideConfigValue( 'TranslateWorkflowStates', [
			'progress' => [ 'color' => 'd33' ],
			'published' => [ 'color' => 'aea' ],
		] );

		// Populate message group fixtures.
		$this->setTemporaryHook(
			'TranslatePostInitGroups',
			static function ( &$groups, &$deps, &$autoload ) {
				$normalGroup = new WikiMessageGroup( self::NORMAL_MESSAGE_GROUP, 'wewgweg' );
				$frGroup = new WikiMessageGroup( self::FUNDRAISING_MESSAGE_GROUP, 'wewgweg' );
				$groups[self::NORMAL_MESSAGE_GROUP] = $normalGroup;
				$groups[self::FUNDRAISING_MESSAGE_GROUP] = $frGroup;
			}
		);

		// Everyone can do normal review.
		$this->setGroupPermissions( 'user', 'translate-groupreview', true );
		$publishRight = $this->getConfVar( 'FundraisingTranslateWorkflowPublishRight' );
		// Nobody can do fundraising review.
		$this->setGroupPermissions( 'user', $publishRight, false );
	}

	/**
	 * Normal message groups are unaffected.
	 *
	 * @covers ::onModifyMessageGroupStates
	 */
	public function testOnModifyMessageGroupStates_nonMatch() {
		[ $result ] = $this->doApiRequestWithToken( [
			'action' => 'groupreview',
			'group' => self::NORMAL_MESSAGE_GROUP,
			'language' => 'es',
			'state' => 'published',
		] );
		$expectedResult = [
			'group' => self::NORMAL_MESSAGE_GROUP,
			'language' => 'es',
			'state' => 'published',
		];
		$this->assertEquals( $expectedResult, $result['groupreview']['review'] );
	}

	/**
	 * Users without the right can't publish fundraising messages.
	 *
	 * @covers ::onModifyMessageGroupStates
	 */
	public function testOnModifyMessageGroupStates_matchDeny() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'You don\'t have permission to manage message groups.' );
		[ $result ] = $this->doApiRequestWithToken( [
			'action' => 'groupreview',
			'group' => self::FUNDRAISING_MESSAGE_GROUP,
			'language' => 'es',
			'state' => 'published',
		] );
	}

	/**
	 * We have special permission to edit this group.
	 *
	 * @covers ::onModifyMessageGroupStates
	 */
	public function testOnModifyMessageGroupStates_matchAllow() {
		$publishRight = $this->getConfVar( 'FundraisingTranslateWorkflowPublishRight' );
		$this->setGroupPermissions( 'user', $publishRight, true );

		[ $result ] = $this->doApiRequestWithToken( [
			'action' => 'groupreview',
			'group' => self::FUNDRAISING_MESSAGE_GROUP,
			'language' => 'es',
			'state' => 'published',
		] );
		$expectedResult = [
			'group' => self::FUNDRAISING_MESSAGE_GROUP,
			'language' => 'es',
			'state' => 'published',
		];
		$this->assertEquals( $expectedResult, $result['groupreview']['review'] );
	}

}
