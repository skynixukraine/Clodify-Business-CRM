<?php
/**
 * Created by SkynixTeam.
 * User: igor
 * Date: 07.11.17
 * Time: 10:00
 */

use Helper\OAuthSteps;
use Helper\ApiEndpoints;
use Helper\ValuesContainer;

/**
 * Class CounterpartiesCest
 */
class CounterpartiesCest
{
    private $counterpartyId;

    /**
     * @see    https://jira.skynix.company/browse/SCA-43
     * @param  FunctionalTester $I
     * @return void
     */
    public function testCounterpartyCreation(FunctionalTester $I, \Codeception\Scenario $scenario)
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login();

        $I->sendPOST(ApiEndpoints::COUNTERPARTY, json_encode([
            "name" => "Project"
        ]));
        $response = json_decode($I->grabResponse());
        $I->assertEmpty($response->errors);
        $I->assertEquals(true, $response->success);
        $counterpartyId = $response->data->counterparty_id;
        $this->counterpartyId = $counterpartyId;
        codecept_debug($counterpartyId);
    }

    /**
     * @see    https://jira.skynix.company/browse/SCA-43
     * @param  FunctionalTester $I
     * @return void
     */
    public function testCounterpartyCreationWithoutName(FunctionalTester $I, \Codeception\Scenario $scenario)
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login();

        $I->sendPOST(ApiEndpoints::COUNTERPARTY);
        $response = json_decode($I->grabResponse());
        $I->assertNotEmpty($response->errors);
        $I->seeResponseContainsJson([
            "data"   => null,
            "errors" => [
                "param"   => "error",
                "message" => "You have to provide name of the counterparty"
            ],
            "success" => false
        ]);
    }

    /**
     * @see    https://jira.skynix.company/browse/SCA-43
     * @param  FunctionalTester $I
     * @return void
     */
    public function testCounterpartyCreationForbiddenForSaleDevClientPm(FunctionalTester $I, \Codeception\Scenario $scenario)
    {

        $I->haveInDatabase('users', array(
            'id' => 4,
            'first_name' => 'saleUsers',
            'last_name' => 'saleUsersLast',
            'email' => 'saleUser@email.com',
            'role' => 'SALES',
            'password' => md5('sales')
        ));

        $I->haveInDatabase('users', array(
            'id' => 5,
            'first_name' => 'devUsers',
            'last_name' => 'devUsersLast',
            'email' => 'devUser@email.com',
            'role' => 'DEV',
            'password' => md5('dev')
        ));

        $I->haveInDatabase('users', array(
            'id' => 6,
            'first_name' => 'pmUsers',
            'last_name' => 'pmUsersLast',
            'email' => 'pmUser@email.com',
            'role' => 'PM',
            'password' => md5('pm')
        ));

        $I->haveInDatabase('users', array(
            'id' => 7,
            'first_name' => 'clientUsers',
            'last_name' => 'clientUsersLast',
            'email' => 'clientUser@email.com',
            'role' => 'CLIENT',
            'password' => md5('client')
        ));


        for ($i = 4; $i < 8; $i++) {
            $email = $I->grabFromDatabase('users', 'email', array('id' => $i));
            $pas = $I->grabFromDatabase('users', 'role', array('id' => $i));

            \Helper\OAuthToken::$key = null;

            $oAuth = new OAuthSteps($scenario);
            $oAuth->login($email, strtolower($pas));

        $I->wantTo('Test that counterparty creation forbidden for  DEV, SALES, PM, CLIENT role');
        $I->sendPOST(ApiEndpoints::COUNTERPARTY );

        \Helper\OAuthToken::$key = null;

        $response = json_decode($I->grabResponse());
        $I->assertNotEmpty($response->errors);
        $I->seeResponseContainsJson([
            "data" => null,
            "errors" => [
                "param" => "error",
                "message" => "You have no permission for this action"
            ],
            "success" => false
        ]);
    }

    }
}