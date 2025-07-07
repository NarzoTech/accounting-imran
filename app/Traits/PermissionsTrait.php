<?php

namespace App\Traits;

use ReflectionClass;

trait PermissionsTrait
{
    public static array $dashboardPermissions = [
        'group_name' => 'dashboard',
        'permissions' => [
            'dashboard.view',
        ],
    ];

    public static array $adminProfilePermissions = [
        'group_name' => 'admin profile',
        'permissions' => [
            'admin.profile.view',
            'admin.profile.update',
        ],
    ];

    public static array $orderManagementPermissions = [
        'group_name' => 'order management',
        'permissions' => [
            'order.management',
        ],
    ];


    public static array $adminPermissions = [
        'group_name' => 'admin',
        'permissions' => [
            'admin.view',
            'admin.create',
            'admin.store',
            'admin.edit',
            'admin.update',
            'admin.delete',
        ],
    ];

    public static array $listingCategoryPermissions = [
        'group_name' => 'property type',
        'permissions' => [
            'property.type.view',
            'property.type.create',
            'property.type.edit',
            'property.type.delete',
        ],
    ];

    public static array $listingPurposePermissions = [
        'group_name' => 'property purpose',
        'permissions' => [
            'property.purpose.view',
            'property.purpose.create',
            'property.purpose.edit',
            'property.purpose.delete',
        ],
    ];
    public static array $listingAmenityPermissions = [
        'group_name' => 'property amenity',
        'permissions' => [
            'property.amenity.view',
            'property.amenity.create',
            'property.amenity.edit',
            'property.amenity.delete',
        ],
    ];
    public static array $listingPermissions = [
        'group_name' => 'property',
        'permissions' => [
            'property.view',
            'property.create',
            'property.edit',
            'property.delete',
            'property.agent.list'
        ],
    ];
    public static array $partnersPermissions = [
        'group_name' => 'partner',
        'permissions' => [
            'partner.list',
            'partner.create',
            'partner.edit',
            'partner.delete',
        ],
    ];
    public static array $countryPermissions = [
        'group_name' => 'country',
        'permissions' => [
            'country.view',
            'country.create',
            'country.edit',
            'country.delete',
        ],
    ];
    public static array $statePermissions = [
        'group_name' => 'state',
        'permissions' => [
            'state.view',
            'state.create',
            'state.edit',
            'state.delete',
        ],
    ];
    public static array $cityPermissions = [
        'group_name' => 'city',
        'permissions' => [
            'city.view',
            'city.create',
            'city.edit',
            'city.delete',
        ],
    ];

    public static array $blogCatgoryPermissions = [
        'group_name' => 'blog category',
        'permissions' => [
            'blog.category.view',
            'blog.category.create',
            'blog.category.translate',
            'blog.category.store',
            'blog.category.edit',
            'blog.category.update',
            'blog.category.delete',
        ],
    ];

    public static array $blogPermissions = [
        'group_name' => 'blog',
        'permissions' => [
            'blog.view',
            'blog.create',
            'blog.translate',
            'blog.store',
            'blog.edit',
            'blog.update',
            'blog.delete',
        ],
    ];

    public static array $blogCommentPermissions = [
        'group_name' => 'blog comment',
        'permissions' => [
            'blog.comment.view',
            'blog.comment.update',
            'blog.comment.replay',
            'blog.comment.delete',
        ],
    ];

    public static array $rolePermissions = [
        'group_name' => 'role',
        'permissions' => [
            'role.view',
            'role.create',
            'role.store',
            'role.assign',
            'role.edit',
            'role.update',
            'role.delete',
        ],
    ];

    public static array $settingPermissions = [
        'group_name' => 'setting',
        'permissions' => [
            'setting.view',
            'setting.update',
        ],
    ];

    public static array $basicPaymentPermissions = [
        'group_name' => 'basic payment',
        'permissions' => [
            'basic.payment.view',
            'basic.payment.update',
        ],
    ];

    public static array $contactMessagePermissions = [
        'group_name' => 'contact message',
        'permissions' => [
            'contact.message.view',
            'contact.message.delete',
        ],
    ];

    public static array $currencyPermissions = [
        'group_name' => 'currency',
        'permissions' => [
            'currency.view',
            'currency.create',
            'currency.store',
            'currency.edit',
            'currency.update',
            'currency.delete',
        ],
    ];

    public static array $customerPermissions = [
        'group_name' => 'customer',
        'permissions' => [
            'customer.view',
            'customer.bulk.mail',
            'customer.create',
            'customer.store',
            'customer.edit',
            'customer.update',
            'customer.delete',
        ],
    ];

    public static array $languagePermissions = [
        'group_name' => 'language',
        'permissions' => [
            'language.view',
            'language.create',
            'language.store',
            'language.edit',
            'language.update',
            'language.delete',
            'language.translate',
            'language.single.translate',
        ],
    ];

    public static array $menuPermissions = [
        'group_name' => 'menu builder',
        'permissions' => [
            'menu.view',
            'menu.create',
            'menu.update',
            'menu.delete',
        ],
    ];

    public static array $pagePermissions = [
        'group_name' => 'page builder',
        'permissions' => [
            'page.view',
            'page.create',
            'page.store',
            'page.edit',
            'page.component.add',
            'page.update',
            'page.delete',
        ],
    ];

    public static array $subscriptionPermissions = [
        'group_name' => 'subscription',
        'permissions' => [
            'subscription.view',
            'subscription.create',
            'subscription.store',
            'subscription.edit',
            'subscription.update',
            'subscription.delete',
        ],
    ];

    public static array $paymentPermissions = [
        'group_name' => 'payment',
        'permissions' => [
            'payment.view',
            'payment.update',
        ],
    ];

    public static array $socialPermission = [
        'group_name' => 'social link management',
        'permissions' => [
            'social.link.management',
        ],
    ];

    public static array $sitemapPermission = [
        'group_name' => 'sitemap management',
        'permissions' => [
            'sitemap.management',
        ],
    ];


    public static array $taxPermission = [
        'group_name' => 'tax management',
        'permissions' => [
            'tax.view',
            'tax.create',
            'tax.translate',
            'tax.store',
            'tax.edit',
            'tax.update',
            'tax.delete',
        ],
    ];

    public static array $newsletterPermissions = [
        'group_name' => 'newsletter',
        'permissions' => [
            'newsletter.view',
            'newsletter.mail',
            'newsletter.delete',
        ],
    ];

    public static array $testimonialPermissions = [
        'group_name' => 'testimonial',
        'permissions' => [
            'testimonial.view',
            'testimonial.create',
            'testimonial.translate',
            'testimonial.store',
            'testimonial.edit',
            'testimonial.update',
            'testimonial.delete',
        ],
    ];

    public static array $faqPermissions = [
        'group_name' => 'faq',
        'permissions' => [
            'faq.view',
            'faq.create',
            'faq.translate',
            'faq.store',
            'faq.edit',
            'faq.update',
            'faq.delete',
        ],
    ];


    private static function getSuperAdminPermissions(): array
    {
        $reflection = new ReflectionClass(__TRAIT__);
        $properties = $reflection->getStaticProperties();

        $permissions = [];
        foreach ($properties as $value) {
            if (is_array($value)) {
                $permissions[] = [
                    'group_name' => $value['group_name'],
                    'permissions' => (array) $value['permissions'],
                ];
            }
        }

        return $permissions;
    }
}
