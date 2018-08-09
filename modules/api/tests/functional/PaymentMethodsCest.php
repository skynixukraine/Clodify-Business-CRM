<?php
/**
 * Created by Skynix Team
 * Date: 03.04.17
 * Time: 12:37
 */

use Helper\OAuthSteps;
use Helper\ValuesContainer;

class PaymentMethodsCest
{

    /**
     * @see    https://jira-v2.skynix.company/browse/SI-946
     * @param FunctionalTester $I
     */
    public function testCreatePaymentMethods(FunctionalTester $I, \Codeception\Scenario $scenario)
    {

        $I->wantTo('test payment method creation is forbidden for DEV, PM, CLIENT role');
        $email = $I->grabFromDatabase('users', 'email', array('id' => ValuesContainer::$userDev['id']));
        $pas = ValuesContainer::$userDev['password'];

        \Helper\OAuthToken::$key = null;

        $oAuth = new OAuthSteps($scenario);
        $oAuth->login($email, $pas);

        $I->wantTo('test payment method creation is forbidden for DEV role');
        $I->sendPOST('/api/businesses/1/methods', json_encode([
            'name' => 'p24',
            'address' => 'Kyiv 22, ap 33',
            'represented_by' => 'Privat 24',
            'bank_information' => 'The P24 is a large bank in Ukraine',
            'is_default' => 0,
            'business_id' => 1
        ]));

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

        $I->wantTo('test payment method creation is forbidden for SALES role');
        $email = $I->grabFromDatabase('users', 'email', array('id' => ValuesContainer::$userSales['id']));
        $pas = ValuesContainer::$userSales['password'];

        $oAuth = new OAuthSteps($scenario);
        $oAuth->login($email, $pas);

        $I->sendPOST('/api/businesses/1/methods', json_encode([
            'name' => 'p24',
            'address' => 'Kyiv 22, ap 33',
            'represented_by' => 'Privat 24',
            'bank_information' => 'The P24 is a large bank in Ukraine',
            'is_default' => 0,
            'business_id' => 1
        ]));

        \Helper\OAuthToken::$key = null;

        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();

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

        $I->wantTo('test payment method creation is forbidden for CLIENT role');
        $email = $I->grabFromDatabase('users', 'email', array('id' => ValuesContainer::$userClient['id']));
        $pas = ValuesContainer::$userClient['password'];
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login($email, $pas);

        $I->sendPOST('/api/businesses/1/methods', json_encode([
            'name' => 'p24',
            'address' => 'Kyiv 22, ap 33',
            'represented_by' => 'Privat 24',
            'bank_information' => 'The P24 is a large bank in Ukraine',
            'is_default' => 0,
            'business_id' => 1
        ]));

        \Helper\OAuthToken::$key = null;

        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();

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


        $I->wantTo('test payment method creation is forbidden for PM role');
        $email = $I->grabFromDatabase('users', 'email', array('id' => ValuesContainer::$userPm['id']));
        $pas = ValuesContainer::$userPm['password'];
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login($email, $pas);

        $I->sendPOST('/api/businesses/1/methods', json_encode([
            'name' => 'p24',
            'address' => 'Kyiv 22, ap 33',
            'represented_by' => 'Privat 24',
            'bank_information' => 'The P24 is a large bank in Ukraine',
            'is_default' => 0,
            'business_id' => 1
        ]));

        \Helper\OAuthToken::$key = null;

        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();

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

        $I->wantTo('test payment method creation is allowed for ADMIN role');
        $email = $I->grabFromDatabase('users', 'email', array('id' => ValuesContainer::$userAdmin['id']));
        $pas = ValuesContainer::$userAdmin['password'];
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login($email, $pas);

        $I->sendPOST('/api/businesses/1/methods', json_encode([
            'name' => 'p24',
            'address' => 'Kyiv 22, ap 33',
            'represented_by' => 'Privat 24',
            'bank_information' => 'The P24 is a large bank in Ukraine',
            'is_default' => 0,
            'business_id' => 1
        ]));

        \Helper\OAuthToken::$key = null;

        $I->seeResponseCodeIs('200');
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse());
        $I->assertEmpty($response->errors);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'payment_method_id' => 'integer',
            ],
            'errors' => 'array',
            'success' => 'boolean'
        ]);



    }

}