<?php

namespace App\Services;

use Microsoft\Graph\Generated\Groups\Item\Drive\DriveRequestBuilderGetQueryParameters;
use Microsoft\Graph\Generated\Groups\Item\Drive\DriveRequestBuilderGetRequestConfiguration;
use Microsoft\Graph\Generated\Groups\Item\Members\Ref\Ref;
use Microsoft\Graph\Generated\Models\AssignedLicense;
use Microsoft\Graph\Generated\Models\DirectoryObjectCollectionResponse;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\DriveCollectionResponse;
use Microsoft\Graph\Generated\Models\DriveItem;
use Microsoft\Graph\Generated\Models\DriveItemCollectionResponse;
use Microsoft\Graph\Generated\Models\Group;
use Microsoft\Graph\Generated\Models\GroupCollectionResponse;
use Microsoft\Graph\Generated\Models\LicenseDetailsCollectionResponse;
use Microsoft\Graph\Generated\Models\PasswordProfile;
use Microsoft\Graph\Generated\Models\SubscribedSku;
use Microsoft\Graph\Generated\Models\SubscribedSkuCollectionResponse;
use Microsoft\Graph\Generated\Models\User;
use Microsoft\Graph\Generated\Models\UserCollectionResponse;
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

    public function listUsers(): UserCollectionResponse
    {
        return $this->authenticate()->users()->get()->wait();
    }

    public function getUser(string $userId): User
    {
        return $this->authenticate()->usersById($userId)->get()->wait();
    }

    public function createUser(bool $accountEnabled, string $displayName, string $mailNickname, string $password, string $userPrincipalName)
    {
        $user = new User();

        $user->setAccountEnabled($accountEnabled);
        $user->setDisplayName($displayName);
        $user->setMailNickname($mailNickname);
        $user->setUserPrincipalName($userPrincipalName);

        $passwordProfile = new PasswordProfile();
        $passwordProfile->setForceChangePasswordNextSignIn(true);
        $passwordProfile->setPassword($password);

        $user->setPasswordProfile($passwordProfile);

        return $this->authenticate()->users()->post($user)->wait();
    }

    public function deleteUser(string $userId)
    {
        return $this->authenticate()->usersById($userId)->delete()->wait();
    }

    public function listGroups(): GroupCollectionResponse
    {
        return $this->authenticate()->groups()->get()->wait();
    }

    public function getGroup(string $groupId): Group
    {
        return $this->authenticate()->groupsById($groupId)->get()->wait();
    }

    public function deleteGroup(string $groupId)
    {
        return $this->authenticate()->groupsById($groupId)->delete();
    }

    public function listGroupMembers(string $groupId): DirectoryObjectCollectionResponse
    {
        return $this->authenticate()->groupsById($groupId)->members()->get()->wait();
    }

    public function addGroupMember(string $groupId, string $userId)
    {
        $config = new Ref();
        $config->setAdditionalData([
            '@odata.id' => 'https://graph.microsoft.com/v1.0/users/'.$userId,
        ]);

        return $this->authenticate()->groupsById($groupId)->members()->ref()->post($config);
    }

    public function removeGroupMember(string $groupId, string $userId)
    {
        return $this->authenticate()->groupsById($groupId)->membersById($userId)->ref()->delete()->wait();
    }

    public function listLicenses(): SubscribedSkuCollectionResponse
    {
        return $this->authenticate()->subscribedSkus()->get()->wait();
    }

    public function getLicense(string $licenseId): SubscribedSku
    {
        return $this->authenticate()->subscribedSkusById($licenseId)->get()->wait();
    }

    public function listLicensesAssignedToUser(string $userId): LicenseDetailsCollectionResponse
    {
        return $this->authenticate()->usersById($userId)->licenseDetails()->get()->wait();
    }

    public function assignLicensesToUser(string $userId, array $licenses)
    {
        $config = new AssignLicensePostRequestBody();

        $licensesToAdd = [];

        foreach ($licenses as $license) {
            $assignedLicense = new AssignedLicense();

            $assignedLicense->setAdditionalData($license);

            $licensesToAdd[] = $assignedLicense;
        }

        $config->setAddLicenses($licensesToAdd);

        $config->setRemoveLicenses([]);

        return $this->authenticate()->usersById($userId)->assignLicense()->post($config)->wait();
    }

    public function removeLicensesFromUser(string $userId, array $licenses)
    {
        $config = new AssignLicensePostRequestBody();

        $config->setAddLicenses([]);
        $config->setRemoveLicenses($licenses);

        return $this->authenticate()->usersById($userId)->assignLicense()->post($config)->wait();
    }

    public function listGroupDrives(string $groupId): DriveCollectionResponse
    {
        return $this->authenticate()->groupsById($groupId)->drives()->get()->wait();
    }

    public function getGroupDrive(string $groupId): Drive
    {
        $driveConfig = new DriveRequestBuilderGetRequestConfiguration();
        $driveConfig->queryParameters = new DriveRequestBuilderGetQueryParameters();
        $driveConfig->queryParameters->expand = ['root'];

        return $this->authenticate()->groupsById($groupId)->drive()->get($driveConfig)->wait();
    }

    public function getGroupDriveRoot(string $groupId): DriveItem
    {
        $driveRoot = $this->getGroupDrive($groupId);

        return $driveRoot->getRoot();
    }

    public function listGroupDriveItems(string $groupId): DriveItemCollectionResponse
    {
        $graph = $this->authenticate();

        $driveId = $this->getGroupDrive($groupId)->getId();
        $driveRootId = $this->getGroupDriveRoot($groupId)->getId();

        return $graph->drivesById($driveId)->itemsById($driveRootId)->children()->get()->wait();
    }
}
