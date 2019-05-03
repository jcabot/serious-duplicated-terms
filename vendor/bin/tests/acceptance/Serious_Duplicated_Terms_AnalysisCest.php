<?php 

class Serious_Duplicated_Terms_AnalysisCest
{
	public function _before(AcceptanceTester $I)
	{
		$I->haveHttpHeader('X-Requested-With', 'Codeception');
		
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('serious-duplicated-terms');
		$I->activatePlugin('serious-duplicated-terms');

		//Preparing the plugin configuration for the test
		$I->amOnAdminPage('admin.php?page=duplicated-configuration');
		$I->checkOption('duplicated-configuration[tags]');
		$I->checkOption('duplicated-configuration[categories]');
		$I->checkOption('duplicated-configuration[levenshtein]');
		$I->fillField('duplicated-configuration[maxDistance]',3);
		$I->click('Save Changes');

	}


	// tests
	/*public function detectedDuplicates(AcceptanceTester $I)
	{
		//testing tag duplicated detection
		$I->factory()->term->create(array('name' => 'testname', 'taxonomy' => 'category'));
		$I->factory()->term->create(array('name' => 'testrname', 'taxonomy' => 'category'));
		$I->factory()->term->create(array('name' => 'taga', 'taxonomy' => 'post_tag'));
		$I->factory()->term->create(array('name' => 'unrelated', 'taxonomy' => 'post_tag'));
		$I->factory()->term->create(array('name' => 'tagaa', 'taxonomy' => 'post_tag'));
		$I->amOnAdminPage('admin.php?page=duplicated-analysis');
		$I->see("testname");
		$I->see("testrname");
		$I->see("taga");
		$I->see("tagaa");
		$I->dontSee("unrelated");

	}*/


	// tests
	public function removeDuplicatesNotInPosts(AcceptanceTester $I)
	{
	    // Testing removal of duplicate categories
		$cat1 = $I->factory()->term->create(array('name' => 'testname', 'taxonomy' => 'category'));
		$cat2 = $I->factory()->term->create(array('name' => 'testrname', 'taxonomy' => 'category'));
		$I->amOnAdminPage('admin.php?page=duplicated-analysis');
		$I->checkOption('term'.$cat1);
		$I->click('Save Changes');
		$I->amOnAdminPage('admin.php?page=duplicated-analysis');
		$I->see("No categories to merge");
		$I->seeTermInDatabase(['term_id' => $cat1, 'taxonomy' => 'category']);
		$I->dontSeeTermInDatabase(['term_id' => $cat2, 'taxonomy' => 'category']);

		// Testing removal of duplicate terms
		$tag1 = $I->factory()->term->create(array('name' => 'testname', 'taxonomy' => 'post_tag'));
		$tag2 = $I->factory()->term->create(array('name' => 'testrname', 'taxonomy' => 'post_tag'));
		$I->amOnAdminPage('admin.php?page=duplicated-analysis');
		$I->checkOption('term'.$tag1);
		$I->click('Save Changes');
		$I->amOnAdminPage('admin.php?page=duplicated-analysis');
		$I->see("No tags to merge");
		$I->seeTermInDatabase(['term_id' => $tag1, 'taxonomy' => 'category']);
		$I->dontSeeTermInDatabase(['term_id' => $tag2, 'taxonomy' => 'category']);

	}
}
