<?php

namespace App\Services;

use Microsoft\Graph\Generated\Drives\Item\Items\Item\DriveItemItemRequestBuilderGetQueryParameters;
use Microsoft\Graph\Generated\Drives\Item\Items\Item\DriveItemItemRequestBuilderGetRequestConfiguration;
use Microsoft\Graph\Generated\Drives\Item\Items\Item\Invite\InvitePostRequestBody;
use Microsoft\Graph\Generated\Drives\Item\Items\Item\Invite\InviteResponse;
use Microsoft\Graph\Generated\Groups\Item\Drive\DriveRequestBuilderGetQueryParameters;
use Microsoft\Graph\Generated\Groups\Item\Drive\DriveRequestBuilderGetRequestConfiguration;
use Microsoft\Graph\Generated\Groups\Item\Members\Ref\Ref;
use Microsoft\Graph\Generated\Models\AssignedLicense;
use Microsoft\Graph\Generated\Models\DirectoryObjectCollectionResponse;
use Microsoft\Graph\Generated\Models\Drive;
use Microsoft\Graph\Generated\Models\DriveCollectionResponse;
use Microsoft\Graph\Generated\Models\DriveItem;
use Microsoft\Graph\Generated\Models\DriveItemCollectionResponse;
use Microsoft\Graph\Generated\Models\DriveRecipient;
use Microsoft\Graph\Generated\Models\Group;
use Microsoft\Graph\Generated\Models\GroupCollectionResponse;
use Microsoft\Graph\Generated\Models\LicenseDetailsCollectionResponse;
use Microsoft\Graph\Generated\Models\PasswordProfile;
use Microsoft\Graph\Generated\Models\Permission;
use Microsoft\Graph\Generated\Models\PermissionCollectionResponse;
use Microsoft\Graph\Generated\Models\SubscribedSku;
use Microsoft\Graph\Generated\Models\SubscribedSkuCollectionResponse;
use Microsoft\Graph\Generated\Models\User;
use Microsoft\Graph\Generated\Models\UserCollectionResponse;
use Microsoft\Graph\Generated\Users\Item\AssignLicense\AssignLicensePostRequestBody;
use Microsoft\Graph\Generated\Users\UsersRequestBuilderGetQueryParameters;
use Microsoft\Graph\Generated\Users\UsersRequestBuilderGetRequestConfiguration;
use Microsoft\Graph\GraphRequestAdapter;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;
use Microsoft\Kiota\Authentication\PhpLeagueAuthenticationProvider;

class MicrosoftGraph
{
    protected ClientCredentialContext $tokenRequestContext;

    protected array $scopes;

    protected PhpLeagueAuthenticationProvider $authProvider;

    protected GraphRequestAdapter $requestAdapter;

    protected GraphServiceClient $graph;

    public function __construct()
    {
        $this->tokenRequestContext = new ClientCredentialContext(
            config('services.azure.tenant'),
            config('services.azure.client_id'),
            config('services.azure.client_secret')
        );

        $this->scopes = ['https://graph.microsoft.com/.default'];

        $this->authProvider = new PhpLeagueAuthenticationProvider($this->tokenRequestContext, $this->scopes);
        $this->requestAdapter = new GraphRequestAdapter($this->authProvider);

        $this->graph = new GraphServiceClient($this->requestAdapter);
    }

    public function listUsers(): UserCollectionResponse
    {
        $config = new UsersRequestBuilderGetRequestConfiguration();

        $queryParameters = new UsersRequestBuilderGetQueryParameters();
        $queryParameters->top = 999;

        $config->queryParameters = $queryParameters;

        return $this->graph->users()->get($config)->wait();
    }

    public function getUser(string $userId): User
    {
        return $this->graph->usersById($userId)->get()->wait();
    }

    public function createUser(
        bool $accountEnabled,
        string $displayName,
        string $mailNickname,
        string $password,
        string $userPrincipalName
    ) {
        $user = new User();

        $user->setAccountEnabled($accountEnabled);
        $user->setDisplayName($displayName);
        $user->setMailNickname($mailNickname);
        $user->setUserPrincipalName($userPrincipalName);

        $passwordProfile = new PasswordProfile();
        $passwordProfile->setForceChangePasswordNextSignIn(true);
        $passwordProfile->setPassword($password);

        $user->setPasswordProfile($passwordProfile);

        return $this->graph->users()->post($user)->wait();
    }

    public function deleteUser(string $userId)
    {
        return $this->graph->usersById($userId)->delete()->wait();
    }

    public function listUserGroups(string $userId): DirectoryObjectCollectionResponse
    {
        return $this->graph->usersById($userId)->memberOf()->get()->wait();
    }

    public function listGroups(): GroupCollectionResponse
    {
        return $this->graph->groups()->get()->wait();
    }

    public function getGroup(string $groupId): Group
    {
        return $this->graph->groupsById($groupId)->get()->wait();
    }

    public function deleteGroup(string $groupId)
    {
        return $this->graph->groupsById($groupId)->delete();
    }

    public function listGroupMembers(string $groupId): DirectoryObjectCollectionResponse
    {
        return $this->graph->groupsById($groupId)->members()->get()->wait();
    }

    public function addGroupMember(string $groupId, string $userId)
    {
        $config = new Ref();
        $config->setAdditionalData([
            '@odata.id' => 'https://graph.microsoft.com/v1.0/users/'.$userId,
        ]);

        return $this->graph->groupsById($groupId)->members()->ref()->post($config);
    }

    public function removeGroupMember(string $groupId, string $userId)
    {
        return $this->graph->groupsById($groupId)->membersById($userId)->ref()->delete()->wait();
    }

    public function listLicenses(): SubscribedSkuCollectionResponse
    {
        return $this->graph->subscribedSkus()->get()->wait();
    }

    public function getLicense(string $licenseId): SubscribedSku
    {
        return $this->graph->subscribedSkusById($licenseId)->get()->wait();
    }

    public function listLicensesAssignedToUser(string $userId): LicenseDetailsCollectionResponse
    {
        return $this->graph->usersById($userId)->licenseDetails()->get()->wait();
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

        return $this->graph->usersById($userId)->assignLicense()->post($config)->wait();
    }

    public function removeLicensesFromUser(string $userId, array $licenses)
    {
        $config = new AssignLicensePostRequestBody();

        $config->setAddLicenses([]);
        $config->setRemoveLicenses($licenses);

        return $this->graph->usersById($userId)->assignLicense()->post($config)->wait();
    }

    public function listGroupDrives(string $groupId): DriveCollectionResponse
    {
        return $this->graph->groupsById($groupId)->drives()->get()->wait();
    }

    public function getGroupDrive(string $groupId): Drive
    {
        $driveConfig = new DriveRequestBuilderGetRequestConfiguration();
        $driveConfig->queryParameters = new DriveRequestBuilderGetQueryParameters();
        $driveConfig->queryParameters->expand = ['root'];

        return $this->graph->groupsById($groupId)->drive()->get($driveConfig)->wait();
    }

    public function getGroupDriveRoot(string $groupId): DriveItem
    {
        return $this->getGroupDrive($groupId)->getRoot();
    }

    public function listDriveItems(string $groupId): DriveItemCollectionResponse
    {
        $driveId = $this->getGroupDrive($groupId)->getId();
        $driveRootId = $this->getGroupDriveRoot($groupId)->getId();

        return $this->graph->drivesById($driveId)->itemsById($driveRootId)->children()->get()->wait();
    }

    public function getDriveFolder(string $groupId, string $folderId): DriveItem
    {
        $driveConfig = new DriveItemItemRequestBuilderGetRequestConfiguration();
        $driveConfig->queryParameters = new DriveItemItemRequestBuilderGetQueryParameters();
        $driveConfig->queryParameters->expand = ['children'];

        $driveId = $this->getGroupDrive($groupId)->getId();

        return $this->graph->drivesById($driveId)->itemsById($folderId)->get($driveConfig)->wait();
    }

    public function getDriveFolderItems(string $groupId, string $folderId): DriveItemCollectionResponse
    {
        $driveId = $this->getGroupDrive($groupId)->getId();
        $folderId = $this->getDriveFolder($groupId, $folderId)->getId();

        return $this->graph->drivesById($driveId)->itemsById($folderId)->children()->get()->wait();
    }

    public function getGroupRecordingsFolder(string $groupId, string $channelFolder = 'General', string $recordingsFolder = 'Recordings')
    {
        $rootItems = $this->listDriveItems($groupId)->getValue();

        foreach ($rootItems as $rootItem) {
            if ($rootItem->getName() === $channelFolder) {
                $folderItems = $this->getDriveFolderItems($groupId, $rootItem->getId())->getValue();

                foreach ($folderItems as $folderItem) {
                    if ($folderItem->getName() === $recordingsFolder) {
                        return $folderItem;
                    }
                }
            }
        }
    }

    public function listDriveItemPermissions(string $driveId, string $itemId): PermissionCollectionResponse
    {
        return $this->graph->drivesById($driveId)->itemsById($itemId)->permissions()->get()->wait();
    }

    public function getDriveItemPermission(string $driveId, string $itemId, string $permissionId): Permission
    {
        return $this->graph->drivesById($driveId)->itemsById($itemId)->permissionsById($permissionId)->get()->wait();
    }

    public function addDriveItemPermissions(string $driveId, string $itemId, array $userIds, array $roles): InviteResponse
    {
        $recipients = [];

        foreach ($userIds as $userId) {
            $recipient = new DriveRecipient();
            $recipient->setEmail($userId);

            $recipients[] = $recipient;
        }

        $config = new InvitePostRequestBody();
        $config->setRecipients($recipients);
        $config->setRoles($roles);
        $config->setRequireSignIn(true);

        return $this->graph->drivesById($driveId)->itemsById($itemId)->invite()->post($config)->wait();
    }

    public function removeDriveItemPermissions(string $driveId, string $itemId, string $permissionId)
    {
        return $this->graph->drivesById($driveId)->itemsById($itemId)->permissionsById($permissionId)->delete()->wait();
    }
}
