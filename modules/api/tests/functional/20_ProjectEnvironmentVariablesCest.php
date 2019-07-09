<?php

use Codeception\Scenario;
use Helper\ApiEndpoints;
use Helper\OAuthSteps;
use Helper\ValuesContainer;

class ProjectEnvironmentVariablesCest
{
    public function createVariableFailedIfEnvNotExists(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login();

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/develop', json_encode([
            'key' => 'test1',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertNotEmpty($response->errors);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
    }

    public function createVariableFailedIfInvalidParamsPassed(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login();

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', '');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertNotEmpty($response->errors);
        $I->assertEquals($response->data, null);
    }

    public function createVariableFailedIfVariableExists(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login();

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertNotEmpty($response->errors);
        $I->assertEquals($response->data, null);
    }

    public function createVariableSuccessForAdmin(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userAdmin['email'], ValuesContainer::$userAdmin['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test2',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, true);
        $I->assertEmpty($response->errors);
        $I->haveInDatabase('project_environment_variables', [
            'name' => 'test2',
            'value' => ValuesContainer::$encryptedString['encrypted'],
            'project_environment_id' => ValuesContainer::$projectEnvironments['staging'],
        ]);
    }

    public function createVariableFailedForClient(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userClient['email'], ValuesContainer::$userClient['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test3',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableFailedIfClientNotOwner(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userClient2['email'], ValuesContainer::$userClient2['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test4',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableSuccessForClient(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userClient['email'], ValuesContainer::$userClient['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test5',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, true);
        $I->assertEmpty($response->errors);
        $I->haveInDatabase('project_environment_variables', [
            'name' => 'test5',
            'value' => ValuesContainer::$encryptedString['encrypted'],
            'project_environment_id' => ValuesContainer::$projectEnvironments['master'],
        ]);
    }

    public function createVariableFailedForDev(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userDev['email'], ValuesContainer::$userDev['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test6',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableFailedIfDevNotDev(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userDev2['email'], ValuesContainer::$userDev2['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test7',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableSuccessForDev(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userDev['email'], ValuesContainer::$userDev['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test8',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, true);
        $I->assertEmpty($response->errors);
        $I->haveInDatabase('project_environment_variables', [
            'name' => 'test8',
            'value' => ValuesContainer::$encryptedString['encrypted'],
            'project_environment_id' => ValuesContainer::$projectEnvironments['staging'],
        ]);
    }

    public function createVariableFailedForFin(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userFin['email'], ValuesContainer::$userFin['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test9',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableFailedIfFinNotDev(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userFin2['email'], ValuesContainer::$userFin2['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test10',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableSuccessForFin(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userFin['email'], ValuesContainer::$userFin['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test11',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, true);
        $I->assertEmpty($response->errors);
        $I->haveInDatabase('project_environment_variables', [
            'name' => 'test11',
            'value' => ValuesContainer::$encryptedString['encrypted'],
            'project_environment_id' => ValuesContainer::$projectEnvironments['master'],
        ]);
    }

    public function createVariableFailedForPM(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userPm['email'], ValuesContainer::$userPm['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test12',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableFailedIfPMNotDev(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userPm2['email'], ValuesContainer::$userPm2['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test13',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableSuccessForPM(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userDev['email'], ValuesContainer::$userDev['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test14',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, true);
        $I->assertEmpty($response->errors);
        $I->haveInDatabase('project_environment_variables', [
            'name' => 'test14',
            'value' => ValuesContainer::$encryptedString['encrypted'],
            'project_environment_id' => ValuesContainer::$projectEnvironments['staging'],
        ]);
    }

    public function createVariableFailedForSales(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userSales['email'], ValuesContainer::$userSales['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test15',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableFailedIfSalesNotDev(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userSales2['email'], ValuesContainer::$userSales2['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test16',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }

    public function createVariableSuccessForSales(FunctionalTester $I, Scenario $scenario): void
    {
        $oAuth = new OAuthSteps($scenario);
        $oAuth->login(ValuesContainer::$userSales['email'], ValuesContainer::$userSales['password']);

        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/staging', json_encode([
            'key' => 'test17',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, true);
        $I->assertEmpty($response->errors);
        $I->haveInDatabase('project_environment_variables', [
            'name' => 'test17',
            'value' => ValuesContainer::$encryptedString['encrypted'],
            'project_environment_id' => ValuesContainer::$projectEnvironments['staging'],
        ]);
    }

    public function createVariableFailedForGuest(FunctionalTester $I, Scenario $scenario): void
    {
        $I->sendPOST(ApiEndpoints::PROJECT . '/' . ValuesContainer::$projectWithEnvId . '/env/master', json_encode([
            'key' => 'test18',
            'value' => ValuesContainer::$encryptedString['origin'],
        ]));
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), false);
        $I->assertEquals($response->success, false);
        $I->assertEquals($response->data, null);
        $I->assertNotEmpty($response->errors);
    }
}