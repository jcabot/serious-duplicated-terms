<?php 

class Serious_Duplicated_Terms_SettingsCest
{
	public function _before(AcceptanceTester $I)
	{
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('serious-duplicated-terms');
		$I->activatePlugin('serious-duplicated-terms');
	}

	// tests
	public function testingGoalConfigurationPage(AcceptanceTester $I)
	{
		$I->amOnAdminPage('admin.php?page=duplicated-configuration');
		$I->see('Configuration Duplicated Terms');
		$I->checkOption('duplicated-configuration[tags]');
		$I->checkOption('duplicated-configuration[categories]');
		$I->checkOption('duplicated-configuration[levenshtein]');
		$I->checkOption('duplicated-configuration[strict]');
		$I->fillField('duplicated-configuration[maxDistance]',3);
		$I->click('Save Changes');

		//Now I moved out of the page and come back again to see if the values were properly saved
		$I->amOnAdminPage('/');
		$I->amOnAdminPage('admin.php?page=duplicated-configuration');
		$I->seeCheckboxIsChecked('duplicated-configuration[tags]');
		$I->seeCheckboxIsChecked('duplicated-configuration[categories]');
		$I->seeCheckboxIsChecked('duplicated-configuration[levenshtein]');
		$I->seeCheckboxIsChecked('duplicated-configuration[strict]');
		$I->seeInField('duplicated-configuration[maxDistance]',3);

	}
}
