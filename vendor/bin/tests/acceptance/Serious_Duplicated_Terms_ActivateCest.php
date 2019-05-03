<?php 

class Serious_Duplicated_Terms_ActivateCest
{
	public function _before(AcceptanceTester $I)
	{
	}

	public function _after(AcceptanceTester $I)
	{

	}


	public function environmentTest(AcceptanceTester $I)
	{
		$I->loginAsAdmin();
		$I->amOnAdminPage('/');
		$I->see('Dashboard');
	}

	public function activateTest(AcceptanceTester $I)
	{
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('serious-duplicated-terms');
		$I->activatePlugin('serious-duplicated-terms');
		$I->amOnAdminPage('/');
		$I->see('Duplicated Terms'); // The menu of the plugin shows up
	}

	public function deactivateTest(AcceptanceTester $I)
	{
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('serious-duplicated-terms');
		$I->activatePlugin('serious-duplicated-terms');
		$I->deactivatePlugin('serious-duplicated-terms');
	}
}
