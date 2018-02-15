<?php

namespace Context;

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\Util\ClassUtils;
use Pim\Bundle\NotificationBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityContext extends RawMinkContext implements KernelAwareInterface
{
    /** @var KernelInterface */
    protected $kernel;

    /** @var Client */
    protected $client;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" user group$/
     */
    public function iMakeADirectCallToDeleteTheUserGroup($userGroupLabel)
    {
        $routeName = 'oro_user_group_delete';

        $userGroup = $this->kernel
            ->getContainer()
            ->get('pim_user.repository.group')
            ->findOneByIdentifier($userGroupLabel);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $userGroup->getId()]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" user role$/
     */
    public function iMakeADirectCallToDeleteTheUserRole($role)
    {
        $routeName = 'oro_user_role_delete';

        $userRole = $this->kernel
            ->getContainer()
            ->get('pim_user.repository.role')
            ->findOneByIdentifier($role);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $userRole->getId()]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" user$/
     */
    public function iMakeADirectCallToDeleteTheUser($username)
    {
        $routeName = 'oro_user_user_delete';

        $user = $this->kernel
            ->getContainer()
            ->get('pim_user.repository.user')
            ->findOneByIdentifier($username);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $user->getId()]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the last comment of "([^"]*)" product$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheLastCommentOfProduct($productIdentifier)
    {
        $routeName = 'pim_comment_comment_delete';

        $product = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.product')
            ->findOneByIdentifier($productIdentifier);

        $comments = $this->kernel
            ->getContainer()
            ->get('pim_comment.repository.comment')
            ->getComments(
                ClassUtils::getClass($product),
                $product->getId()
            );

        $lastComment = end($comments);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $lastComment->getId()]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" datagrid view as "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheDatagridView($datagridViewLabel, $username)
    {
        $routeName = 'pim_datagrid_view_remove';

        $view = $this->kernel
            ->getContainer()
            ->get('pim_datagrid.repository.datagrid_view')
            ->findOneBy(['label' => $datagridViewLabel]);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $view->getId()]);

        $this->doCall('DELETE', $url, [], [], $username);
    }

    /**
     * @When /^I make a direct authenticated GET call to mass delete "([^"]*)" product$/
     */
    public function iMakeADirectAuthenticatedGetCallToMassDeleteProduct($productIndentifier)
    {
        $routeName = 'pim_datagrid_mass_action';

        $product = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.product')
            ->findOneByIdentifier($productIndentifier);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['gridName' => 'product-grid', 'actionName' => 'delete']);

        $this->doCall('GET', $url, [
            'inset' => 1,
            'values' => $product->getId()
        ]);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" association type$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAssociationType($associationTypeCode)
    {
        $routeName = 'pim_enrich_associationtype_rest_remove';

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['code' => $associationTypeCode]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" attribute$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAttribute($attributeCode)
    {
        $routeName = 'pim_enrich_attribute_remove';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $attribute->getId()]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" attribute group$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAttributeGroup($attributeGroupCode)
    {
        $routeName = 'pim_enrich_attributegroup_remove';

        $attributeGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_group')
            ->findOneByIdentifier($attributeGroupCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, ['id' => $attributeGroup->getId()]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" attribute in the "([^"]*)" attribute group$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAttributeInTheAttributeGroup($attributeCode, $attributeGroupCode)
    {
        $routeName = 'pim_enrich_attributegroup_removeattribute';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $attributeGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_group')
            ->findOneByIdentifier($attributeGroupCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'groupId' => $attributeGroup->getId(),
                'attributeId' => $attribute->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated POST call to create a "([^"]*)" attribute option for attribute "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedPostCallToCreateAAttributeOptionForAttribute($attributeOptionCode, $attributeCode)
    {
        $routeName = 'pim_enrich_attributeoption_create';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'attributeId' => $attribute->getId(),
            ]);

        $this->doCall('POST', $url, [], [
            'code' => $attributeOptionCode
        ]);
    }

    /**
     * @When /^I make a direct authenticated PUT call to update the "([^"]*)" attribute option for attribute "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedPutCallToUpdateTheAttributeOptionForAttribute($attributeOptionCode, $attributeCode)
    {
        $routeName = 'pim_enrich_attributeoption_update';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $attributeOption = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_option')
            ->findOneByIdentifier(sprintf('%s.%s', $attributeCode, $attributeOptionCode));

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'attributeId' => $attribute->getId(),
                'attributeOptionId' => $attributeOption->getId(),
            ]);

        $this->doCall('PUT', $url, [], [
            'code' => 'csrf_test',
            'id' => $attributeOption->getId()
        ]);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" attribute option for attribute "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAttributeOptionForAttribute($attributeOptionCode, $attributeCode)
    {
        $routeName = 'pim_enrich_attributeoption_delete';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $attributeOption = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_option')
            ->findOneByIdentifier(sprintf('%s.%s', $attributeCode, $attributeOptionCode));

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'attributeId' => $attribute->getId(),
                'attributeOptionId' => $attributeOption->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated PUT call to sort attribute options of attribute "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedPutCallToSortAttributeOptionsOfAttribute($attributeCode)
    {
        $routeName = 'pim_enrich_attributeoption_update_sorting';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'attributeId' => $attribute->getId(),
            ]);

        $attributeOptions = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_option')
            ->getOptions('en_US', $attribute->getId())['results'];

        $attributeOptionIds = array_map(function ($attributeOption) {
            return $attributeOption['id'];
        }, $attributeOptions);

        $attributeOptionIds = array_reverse($attributeOptionIds);

        $this->doCall('PUT', $url, [], $attributeOptionIds);
    }

    /**
     * @When /^I make a direct authenticated POST call to move the "([^"]*)" category into the "([^"]*)" category$/
     */
    public function iMakeADirectAuthenticatedPostCallToMoveTheCategoryIntoTheCategory($childCategoryCode, $parentCategoryCode)
    {
        $routeName = 'pim_enrich_categorytree_movenode';

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName);

        $childCategory = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.category')
            ->findOneByIdentifier($childCategoryCode);

        $parentCategory = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.category')
            ->findOneByIdentifier($parentCategoryCode);

        $this->doCall('POST', $url, [
            'id' => $childCategory->getId(),
            'parent' => $parentCategory->getId(),
            'prev_sibling' => null,
            'position' => 0,
            'copy' => 0,
        ]);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" category$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheCategory($categoryCode)
    {
        $routeName = 'pim_enrich_categorytree_remove';

        $category = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.category')
            ->findOneByIdentifier($categoryCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $category->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" channel$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheChannel($channelCode)
    {
        $routeName = 'pim_enrich_channel_rest_remove';

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'code' => $channelCode,
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" family$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheFamily($familyCode)
    {
        $routeName = 'pim_enrich_family_remove';

        $family = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.family')
            ->findOneByIdentifier($familyCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $family->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" attribute of family "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAttributeOfFamily($attributeCode, $familyCode)
    {
        $routeName = 'pim_enrich_family_removeattribute';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $family = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.family')
            ->findOneByIdentifier($familyCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'familyId' => $family->getId(),
                'attributeId' => $attribute->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" group$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheGroup($groupCode)
    {
        $routeName = 'pim_enrich_group_remove';

        $group = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->findOneByIdentifier($groupCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $group->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" group type$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheGroupType($groupTypeCode)
    {
        $routeName = 'pim_enrich_grouptype_remove';

        $groupType = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group_type')
            ->findOneByIdentifier($groupTypeCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $groupType->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated POST call to create a "([^"]*)" product in the family "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedPostCallToCreateAProduct($productIdentifier, $familyCode)
    {
        $routeName = 'pim_enrich_product_rest_create';

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName);

        $this->doCall('POST', $url, [
            'identifier' => $productIdentifier,
            'family' => $familyCode
        ]);
    }

    /**
     * @When /^I make a direct authenticated POST call to disable the "([^"]*)" product$/
     */
    public function iMakeADirectAuthenticatedPostCallToDisableTheProduct($productIdentifier)
    {
        $routeName = 'pim_enrich_product_rest_post';

        $product = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.product')
            ->findOneByIdentifier($productIdentifier);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $product->getId(),
            ]);

        $this->doCall('POST', $url, [], [
            'enabled' => false,
            'values' => [],
        ]);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" product$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheProduct($productIdentifier)
    {
        $routeName = 'pim_enrich_product_rest_remove';

        $product = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.product')
            ->findOneByIdentifier($productIdentifier);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $product->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" product to remove the "([^"]*)" attribute$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheProductToRemoveTheAttribute($productIdentifier, $attributeCode)
    {
        $routeName = 'pim_enrich_product_remove_attribute_rest';

        $product = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.product')
            ->findOneByIdentifier($productIdentifier);

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $product->getId(),
                'attributeId' => $attribute->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @Given /^I add the attribute "([^"]*)" with value "([^"]*)" to the "([^"]*)" variant group$/
     */
    public function iAddTheAttributeWithValueToTheVariantGroup($attributeCode, $attributeValue, $variantGroupCode)
    {
        $variantGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->findOneByIdentifier($variantGroupCode);

        $this->kernel
            ->getContainer()
            ->get('pim_catalog.updater.variant_group')
            ->update($variantGroup, [
                'code' => $variantGroupCode,
                'values' => [
                    $attributeCode => [
                        ['locale' => null, 'scope' => null, 'data' => $attributeValue]
                    ]
                ]
            ]);

        $this->kernel
            ->getContainer()
            ->get('pim_catalog.saver.group')
            ->save($variantGroup);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" attribute of the "([^"]*)" variant group$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheAttributeOfTheVariantGroup($attributeCode, $variantGroupCode)
    {
        $routeName = 'pim_enrich_variant_group_rest_remove_attribute';

        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'code' => $variantGroupCode,
                'attributeId' => $attribute->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated POST call on the "([^"]*)" variant group to change its "([^"]*)" label to "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedPostCallOnTheVariantGroupToChangeItsLabelTo($variantGroupCode, $localeLabelCode, $labelValue)
    {
        $routeName = 'pim_enrich_variant_group_rest_post';

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'code' => $variantGroupCode,
            ]);

        $this->doCall('POST', $url, [], [
            'code' => $variantGroupCode,
            'values' => [],
            'labels' => [
                $localeLabelCode => $labelValue
            ]
        ]);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" variant group$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheVariantGroup($variantGroupCode)
    {
        $routeName = 'pim_enrich_variant_group_rest_remove';

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'code' => $variantGroupCode,
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" export job profile$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheExportJobProfile($exportJobProfileCode)
    {
        $routeName = 'pim_importexport_export_profile_remove';

        $exportJobProfile = $this->kernel
            ->getContainer()
            ->get('pim_import_export.repository.job_instance')
            ->findOneByIdentifier($exportJobProfileCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $exportJobProfile->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the "([^"]*)" import job profile$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheImportJobProfile($importJobProfileCode)
    {
        $routeName = 'pim_importexport_import_profile_remove';

        $importJobProfile = $this->kernel
            ->getContainer()
            ->get('pim_import_export.repository.job_instance')
            ->findOneByIdentifier($importJobProfileCode);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $importJobProfile->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @Given /^there is a notification for user "([^"]*)"$/
     */
    public function thereIsANotificationForUser($username)
    {
        $notification = new Notification();
        $notification->setType(0)->setMessage(0);

        $this->kernel
            ->getContainer()
            ->get('pim_notification.notifier')
            ->notify($notification, [$username]);
    }

    /**
     * @When /^I make a direct authenticated DELETE call on the last notification of user "([^"]*)"$/
     */
    public function iMakeADirectAuthenticatedDeleteCallOnTheLastNotificationOfUser($username)
    {
        $routeName = 'pim_notification_notification_remove';

        $user = $this->kernel
            ->getContainer()
            ->get('pim_user.repository.user')
            ->findOneBy(['username' => $username]);

        $notification = $this->kernel
            ->getContainer()
            ->get('pim_notification.repository.user_notification')
            ->findOneBy(['user' => $user]);

        $url = $this->kernel
            ->getContainer()
            ->get('router')
            ->generate($routeName, [
                'id' => $notification->getId(),
            ]);

        $this->doCall('DELETE', $url);
    }

    /**
     * @Then /^there should be (\d+) notification for user "([^"]*)"$/
     */
    public function thereShouldBeNotificationForUser($nbNotifications, $username)
    {
        $user = $this->kernel
            ->getContainer()
            ->get('pim_user.repository.user')
            ->findOneBy(['username' => $username]);

        $count = $this->kernel
            ->getContainer()
            ->get('pim_notification.repository.user_notification')
            ->countUnreadForUser($user);

        assertEquals($nbNotifications, $count);
    }

    /**
     * @Then /^there should be a "([^"]*)" export job profile$/
     * @Then /^there should be a "([^"]*)" import job profile$/
     */
    public function thereShouldBeAExportJobProfile($exportJobProfileCode)
    {
        $exportJobProfile = $this->kernel
            ->getContainer()
            ->get('pim_import_export.repository.job_instance')
            ->findOneByIdentifier($exportJobProfileCode);

        assertNotNull($exportJobProfile);
    }

    /**
     * @Then /^there should be a "([^"]*)" variant group$/
     */
    public function thereShouldBeAVariantGroup($variantGroupCode)
    {
        $variantGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->findOneByIdentifier($variantGroupCode);

        assertNotNull($variantGroup);
    }

    /**
     * @Then /^the label of variant group "([^"]*)" should be "([^"]*)"$/
     */
    public function theLabelOfVariantGroupShouldBe($variantGroupCode, $expectedLabel)
    {
        $variantGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->findOneByIdentifier($variantGroupCode);

        $label = $variantGroup->getLabel();

        assertEquals($expectedLabel, $label);
    }

    /**
     * @Then /^the category "([^"]*)" should have "([^"]*)" as parent$/
     */
    public function theCategoryShouldHaveAsParent($childCategoryCode, $parentCategoryCode)
    {
        $childCategory = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.category')
            ->findOneByIdentifier($childCategoryCode);

        assertEquals($parentCategoryCode, $childCategory->getParent()->getCode());
    }

    /**
     * @Then /^the order for attribute options "([^"]*)" of attribute "([^"]*)" should be (\d+)$/
     */
    public function theOrderForAttributeOptionsOfAttributeShouldBe($attributeOptionCode, $attributeCode, $order)
    {
        $attributeOption = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_option')
            ->findOneByIdentifier(sprintf('%s.%s', $attributeCode, $attributeOptionCode));

        assertEquals($order, $attributeOption->getSortOrder());
    }

    /**
     * @Then /^the variant group "([^"]*)" should have the "([^"]*)" attribute$/
     */
    public function theVariantGroupShouldHaveTheAttribute($variantGroupCode, $attributeCode)
    {
        $variantGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->findOneByIdentifier($variantGroupCode);

        $hasAttribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->hasAttribute([$variantGroup->getId()], $attributeCode);

        assertTrue($hasAttribute);
    }

    /**
     * @Then /^there should be a "([^"]*)" group type$/
     */
    public function thereShouldBeAGroupType($groupTypeCode)
    {
        $groupType = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group_type')
            ->findOneByIdentifier($groupTypeCode);

        assertNotNull($groupType);
    }

    /**
     * @Then /^there should be a "([^"]*)" group$/
     */
    public function thereShouldBeAGroup($groupCode)
    {
        $group = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.group')
            ->findOneByIdentifier($groupCode);

        assertNotNull($group);
    }

    /**
     * @Then /^there should be a "([^"]*)" attribute in the "([^"]*)" family$/
     */
    public function thereShouldBeAAttributeInTheFamily($attributeCode, $familyCode)
    {
        $family = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.family')
            ->findOneByIdentifier($familyCode);

        $hasAttribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.family')
            ->hasAttribute($family->getId(), $attributeCode);

        assertTrue($hasAttribute);
    }

    /**
     * @Then /^there should be a "([^"]*)" family$/
     */
    public function thereShouldBeAFamily($familyCode)
    {
        $family = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.family')
            ->findOneByIdentifier($familyCode);

        assertNotNull($family);
    }

    /**
     * @Then /^there should be a "([^"]*)" channel$/
     */
    public function thereShouldBeAChannel($channelCode)
    {
        $channel = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.channel')
            ->findOneByIdentifier($channelCode);

        assertNotNull($channel);
    }

    /**
     * @Then /^there should be a "([^"]*)" association type$/
     */
    public function thereShouldBeAAssociationType($associationTypeCode)
    {
        $associationType = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.association_type')
            ->findOneByIdentifier($associationTypeCode);

        assertNotNull($associationType);
    }

    /**
     * @Then /^there should be a "([^"]*)" category$/
     */
    public function thereShouldBeACategory($categoryCode)
    {
        $category = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.category')
            ->findOneByIdentifier($categoryCode);

        assertNotNull($category);
    }

    /**
     * @Then /^there should be a "([^"]*)" attribute group$/
     */
    public function thereShouldBeAAttributeGroup($attributeGroupCode)
    {
        $attributeGroup = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_group')
            ->findOneByIdentifier($attributeGroupCode);

        assertNotNull($attributeGroup);
    }

    /**
     * @Then /^there should be a "([^"]*)" attribute$/
     */
    public function thereShouldBeAAttribute($attributeCode)
    {
        $attribute = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute')
            ->findOneByIdentifier($attributeCode);

        assertNotNull($attribute);
    }

    /**
     * @Then /^there should be a "([^"]*)" datagrid view$/
     */
    public function thereShouldBeADatagridView($datagridViewLabel)
    {
        $view = $this->kernel
            ->getContainer()
            ->get('pim_datagrid.repository.datagrid_view')
            ->findOneBy(['label' => $datagridViewLabel]);

        assertNotNull($view);
    }

    /**
     * @Then /^there should( not)? be a "([^"]*)" product$/
     */
    public function thereShouldBeAProduct($not, $productIdentifier)
    {
        $product = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.product')
            ->findOneByIdentifier($productIdentifier);

        if ($not) {
            assertNull($product);
        } else {
            assertNotNull($product);
        }
    }

    /**
     * @Then /^there should( not)? be a "([^"]*)" attribute option for attribute "([^"]*)"$/
     */
    public function thereShouldNotBeAAttributeOptionForAttribute($not, $attributeOptionCode, $attributeCode)
    {
        $attributeOption = $this->kernel
            ->getContainer()
            ->get('pim_catalog.repository.attribute_option')
            ->findOneByIdentifier(sprintf('%s.%s', $attributeCode, $attributeOptionCode));

        if ($not) {
            assertNull($attributeOption);
        } else {
            assertNotNull($attributeOption);
        }
    }

    /**
     * @param string $method
     * @param string $url
     */
    protected function doCall($method, $url, $data = [], $content = [], $username = 'Julia')
    {
        $this->logIn($username);
        $this->client->request($method, $url, $data, [], [], json_encode($content));
    }

    /**
     * @param string $username
     */
    protected function logIn($username = 'Julia')
    {
        // http://symfony.com/doc/current/testing/http_authentication.html

        $client = new Client($this->kernel);
        $client->disableReboot();
        $client->followRedirects();
        $this->client = $client;

        $session = $this->client->getContainer()->get('session');

        $user = $this->kernel
            ->getContainer()
            ->get('pim_user.repository.user')
            ->findOneBy(['username' => $username]);

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
