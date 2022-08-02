<?php

namespace App\Services;

use Microsoft\Graph\Generated\Groups\Item\Members\Ref\Ref;
use Microsoft\Graph\Generated\Models\AssignedLicense;
use Microsoft\Graph\Generated\Models\PasswordProfile;
use Microsoft\Graph\Generated\Models\User;
use Microsoft\Graph\Generated\Users\Item\AssignLicense\AssignLicensePostRequestBody;
use Microsoft\Graph\GraphRequestAdapter;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;
use Microsoft\Kiota\Authentication\PhpLeagueAuthenticationProvider;

class MicrosoftGraph
{
    protected function authenticate()
    {
        $tokenRequestContext = new ClientCredentialContext(
            config('services.azure.tenant'),
            config('services.azure.client_id'),
            config('services.azure.client_secret')
        );

        $scopes = ['https://graph.microsoft.com/.default'];

        $authProvider = new PhpLeagueAuthenticationProvider($tokenRequestContext, $scopes);
        $requestAdapter = new GraphRequestAdapter($authProvider);

        return new GraphServiceClient($requestAdapter);
    }

    public function listUsers()
    {
        $graph = $this->authenticate();

        return $graph->users()->get();
    }

    public function getUser(string $userId)
    {
        $graph = $this->authenticate();

        return $graph->usersById($userId)->get();
    }

    public function createUser(bool $accountEnabled, string $displayName, string $mailNickname, string $password, string $userPrincipalName)
    {
        $graph = $this->authenticate();

        $requestBody = new User();

        $requestBody->setAccountEnabled($accountEnabled);
        $requestBody->setDisplayName($displayName);
        $requestBody->setMailNickname($mailNickname);
        $requestBody->setUserPrincipalName($userPrincipalName);

        $passwordProfile = new PasswordProfile();
        $passwordProfile->setForceChangePasswordNextSignIn(true);
        $passwordProfile->setPassword($password);

        $requestBody->setPasswordProfile($passwordProfile);

        return $graph->users()->post($requestBody);
    }

    public function deleteUser(string $userId)
    {
        $graph = $this->authenticate();

        return $graph->usersById($userId)->delete();
    }

    public function listGroups()
    {
        $graph = $this->authenticate();

        return $graph->groups()->get();
    }

    public function getGroup(string $groupId)
    {
        $graph = $this->authenticate();

        return $graph->groupsById($groupId)->get();
    }

    public function deleteGroup(string $groupId)
    {
        $graph = $this->authenticate();

        return $graph->groupsById($groupId)->delete();
    }

    public function listGroupMembers(string $groupId)
    {
        $graph = $this->authenticate();

        return $graph->groupsById($groupId)->members()->get();
    }

    public function addGroupMember(string $groupId, string $userId)
    {
        $graph = $this->authenticate();

        $requestBody = new Ref();
        $requestBody->setAdditionalData([
            '@odata.id' => 'https://graph.microsoft.com/v1.0/users/'.$userId,
        ]);

        return $graph->groupsById($groupId)->members()->ref()->post($requestBody);
    }

    public function removeGroupMember(string $groupId, string $userId)
    {
        $graph = $this->authenticate();

        return $graph->groupsById($groupId)->membersById($userId)->ref()->delete();
    }

    public function listLicenses()
    {
        $graph = $this->authenticate();

        return $graph->subscribedSkus()->get();
    }

    public function getLicense(string $licenseId)
    {
        $graph = $this->authenticate();

        return $graph->subscribedSkusById($licenseId)->get();
    }

    public function listLicensesAssignedToUser(string $userId)
    {
        $graph = $this->authenticate();

        return $graph->usersById($userId)->licenseDetails()->get();
    }

    public function assignLicensesToUser(string $userId, array $licenses)
    {
        $graph = $this->authenticate();

        $requestBody = new AssignLicensePostRequestBody();

        $licensesToAdd = [];

        foreach ($licenses as $license) {
            $assignedLicense = new AssignedLicense();

            $assignedLicense->setAdditionalData($license);

            $licensesToAdd[] = $assignedLicense;
        }

        $requestBody->setAddLicenses($licensesToAdd);

        $requestBody->setRemoveLicenses([]);

        return $graph->usersById($userId)->assignLicense()->post($requestBody);
    }

    public function removeLicensesFromUser(string $userId, array $licenses)
    {
        $graph = $this->authenticate();

        $requestBody = new AssignLicensePostRequestBody();

        $requestBody->setAddLicenses([]);

        $requestBody->setRemoveLicenses($licenses);

        return $graph->usersById($userId)->assignLicense()->post($requestBody);
    }
}
