<?php
/**
 * Create By Skynix Team
 * Author: oleksii
 * Date: 11/7/18
 * Time: 6:00 PM
 */

use Helper\ValuesContainer;
use Helper\OAuthSteps;
use Helper\ApiEndpoints;

class ClientCest
{
    public function createClientTest(FunctionalTester $I, \Codeception\Scenario $scenario)
    {

        $I->sendPOST(ApiEndpoints::CLIENTS, json_encode(
            [

                "domain"        => "synpass-agency",
                "name"          => "Synpass LLC Test Agency",
                "first_name"    => "John",
                "last_name"     => "Doe",
                "email"         => "agency@synpass.pro"

            ]
        ));
        $response = json_decode($I->grabResponse());
        $I->assertEmpty($response->errors);
        $I->assertEquals(true, $response->success);

    }

    /**
     * @see    https://jira-v2.skynix.company/browse/SCA-230
     * @param FunctionalTester $I
     */
    public function fetchClientTest(FunctionalTester $I, \Codeception\Scenario $scenario)
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login();

        $I->wantTo('Testing fetch counterparties data');
        $I->sendGET(ApiEndpoints::BUSINESS);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse());
        $I->assertEmpty($response->errors);
        $I->assertEquals(true, $response->success);
        $I->seeResponseMatchesJsonType([
            'data' => [[
                'id' => 'integer',
                'name' => 'string',
                'address' => 'string',
                'is_default' => 'integer|null',
                'director' => [
                    'id' => 'integer',
                    'first_name' => 'string',
                    'last_name' => 'string'
                ]

            ]],
            'errors' => [],
            'success' => 'boolean'
        ]);

    }

    /**
     * @see https://jira.skynix.co/browse/SCA-232
     * @param FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     * @return void
     */
    public function fetchClientForbiddenNotAuthorizedTest(FunctionalTester $I)
    {

        \Helper\OAuthToken::$key = null;

        $I->wantTo('test business create is forbidden for not authorized');
        $I->sendGET(ApiEndpoints::BUSINESS);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse());
        $I->assertNotEmpty($response->errors);
        $I->assertEquals(false, $response->success);
        $I->seeResponseContainsJson([
            "data" => null,
            "errors" => [
                "param" => "error",
                "message" => "You are not authorized to access this action"
            ],
            "success" => false
        ]);


    }
}

