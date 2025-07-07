<?php

namespace App\Enums;

enum RouteList
{
    public static function getAll(): object
    {
        $route_list = [
            (object) [
                'name' => __('Dashboard'),
                'route' => route('admin.dashboard'),
                'permission' => 'dashboard.view',
            ],
            (object) [
                'name' => __('Blog Category List'),
                'route' => route('admin.blog-category.index'),
                'permission' => 'blog.category.view',
            ],
            (object) [
                'name' => __('Blog List'),
                'route' => route('admin.blogs.index'),
                'permission' => 'blog.view',
            ],
            (object) [
                'name' => __('Blog Comments'),
                'route' => route('admin.blog-comment.index'),
                'permission' => 'blog.comment.view',
            ],
            (object) [
                'name' => __('Subscription Plan'),
                'route' => route('admin.subscription-plan.index'),
                'permission' => 'subscription.view',
            ],
            (object) [
                'name' => __('Subscription History'),
                'route' => route('admin.subscription-history'),
                'permission' => 'subscription.view',
            ],
            (object) [
                'name' => __('Transaction History'),
                'route' => route('admin.plan-transaction-history'),
                'permission' => 'subscription.view',
            ],
            (object) [
                'name' => __('Pending Transaction'),
                'route' => route('admin.pending-plan-transaction'),
                'permission' => 'subscription.view',
            ],
            (object) [
                'name' => __('Assign Plan'),
                'route' => route('admin.assign-plan'),
                'permission' => 'subscription.view',
            ],
            (object) [
                'name' => __('All Customers'),
                'route' => route('admin.all-customers'),
                'permission' => 'customer.view',
            ],
            (object) [
                'name' => __('Active Customer'),
                'route' => route('admin.active-customers'),
                'permission' => 'customer.view',
            ],
            (object) [
                'name' => __('Non verified'),
                'route' => route('admin.non-verified-customers'),
                'permission' => 'customer.view'
            ],
            (object) [
                'name' => __('Banned Customer'),
                'route' => route('admin.banned-customers'),
                'permission' => 'customer.view',
            ],
            (object) [
                'name' => __('Customer Send bulk mail'),
                'route' => route('admin.send-bulk-mail'),
                'permission' => 'customer.view',
            ],
            (object) [
                'name' => __('Our Team'),
                'route' => route('admin.ourteam.index'),
                'permission' => 'team.management',
            ],
            (object) [
                'name' => __('Menu Builder'),
                'route' => route('admin.custom-menu.index'),
                'permission' => 'menu.view',
            ],
            (object) [
                'name' => __('Customizable Page'),
                'route' => route('admin.custom-pages.index'),
                'permission' => 'page.view',
            ],
            (object) [
                'name' => __('FAQS'),
                'route' => route('admin.faq.index'),
                'permission' => 'faq.view',
            ],
            (object) [
                'name' => __('Social Links'),
                'route' => route('admin.social-link.index'),
                'permission' => 'social.link.management',
            ],
            (object) [
                'name' => __('Coupon List'),
                'route' => route('admin.coupon.index'),
                'permission' => 'coupon.management',
            ],
            (object) [
                'name' => __('Coupon History'),
                'route' => route('admin.coupon-history'),
                'permission' => 'coupon.management',
            ],
            (object) [
                'name' => __('Order History'),
                'route' => route('admin.orders'),
                'permission' => 'order.management',
            ],
            (object) [
                'name' => __('Pending Order'),
                'route' => route('admin.pending-orders'),
                'permission' => 'order.management',
            ],
            (object) [
                'name' => __('Pending Payment'),
                'route' => route('admin.pending-payment'),
                'permission' => 'order.management',
            ],
            (object) [
                'name' => __('Rejected Payment'),
                'route' => route('admin.rejected-payment'),
                'permission' => 'order.management',
            ],
            (object) [
                'name' => __('Refund History'),
                'route' => route('admin.refund-request'),
                'permission' => 'refund.management',
            ],
            (object) [
                'name' => __('Pending Refund'),
                'route' => route('admin.pending-refund-request'),
                'permission' => 'refund.management',
            ],
            (object) [
                'name' => __('Rejected Refund'),
                'route' => route('admin.rejected-refund-request'),
                'permission' => 'refund.management',
            ],
            (object) [
                'name' => __('Complete Refund'),
                'route' => route('admin.complete-refund-request'),
                'permission' => 'refund.management',
            ],
            (object) [
                'name' => __('Withdraw Method'),
                'route' => route('admin.withdraw-method.index'),
                'permission' => 'payment.withdraw.management',
            ],
            (object) [
                'name' => __('Withdraw list'),
                'route' => route('admin.withdraw-list'),
                'permission' => 'payment.withdraw.management',
            ],
            (object) [
                'name' => __('Pending Withdraw'),
                'route' => route('admin.pending-withdraw-list'),
                'permission' => 'payment.withdraw.management',
            ],
            (object) [
                'name' => __('Wallet History'),
                'route' => route('admin.wallet-history'),
                'permission' => 'wallet.management',
            ],
            (object) [
                'name' => __('Pending Request'),
                'route' => route('admin.pending-wallet-payment'),
                'permission' => 'wallet.management',
            ],
            (object) [
                'name' => __('Rejected Request'),
                'route' => route('admin.rejected-wallet-payment'),
                'permission' => 'wallet.management',
            ],
            (object) [
                'name' => __('Configuration'),
                'route' => route('admin.clubpoint-setting'),
                'permission' => 'clubpoint.management',
            ],
            (object) [
                'name' => __('Clubpoint History'),
                'route' => route('admin.clubpoint-history'),
                'permission' => 'clubpoint.management',
            ],
            (object) [
                'name' => __('Support Tickets'),
                'route' => route('admin.support.ticket'),
                'permission' => 'support.ticket.view',
            ],
            (object) [
                'name' => __('Subscriber List'),
                'route' => route('admin.subscriber-list'),
                'permission' => 'newsletter.view',
            ],
            (object) [
                'name' => __('Subscriber Send bulk mail'),
                'route' => route('admin.send-mail-to-newsletter'),
                'permission' => 'newsletter.view',
            ],
            (object) [
                'name' => __('Testimonial'),
                'route' => route('admin.testimonial.index'),
                'permission' => 'testimonial.view',
            ],
            (object) [
                'name' => __('Contact Messages'),
                'route' => route('admin.contact-messages'),
                'permission' => 'contact.message.view',
            ],
            (object) [
                'name' => __('General Settings'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'general_tab',
            ],
            (object) [
                'name' => __('Time & Date Setting'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'website_tab',
            ],
            (object) [
                'name' => __('Logo & Favicon'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'logo_favicon_tab',
            ],
            (object) [
                'name' => __('Cookie Consent'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'custom_pagination_tab',
            ],
            (object) [
                'name' => __('Default avatar'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'default_avatar_tab',
            ],
            (object) [
                'name' => __('Breadcrumb image'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'breadcrump_img_tab',
            ],
            (object) [
                'name' => __('Copyright Text'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'copyright_text_tab',
            ],
            (object) [
                'name' => __('Maintenance Mode'),
                'route' => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab' => 'mmaintenance_mode_tab',
            ],
            (object) [
                'name' => __('Credential Settings'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'google_recaptcha_tab',
            ],
            (object) [
                'name' => __('Google reCaptcha'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'google_recaptcha_tab'
            ],
            (object) [
                'name' => __('Google Tag Manager'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'googel_tag_tab',
            ],
            (object) [
                'name' => __('Google Analytic'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'google_analytic_tab',
            ],
            (object) [
                'name' => __('Facebook Pixel'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'facebook_pixel_tab',
            ],
            (object) [
                'name' => __('Social Login'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'social_login_tab',
            ],
            (object) [
                'name' => __('Tawk Chat'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'tawk_chat_tab',
            ],
            (object) [
                'name' => __('Pusher'),
                'route' => route('admin.credential-setting'),
                'permission' => 'setting.view',
                'tab' => 'pusher_tab',
            ],
            (object) [
                'name' => __('Email Configuration'),
                'route' => route('admin.email-settings'),
                'permission' => 'setting.view',
                'tab' => 'setting_tab',
            ],
            (object) [
                'name' => __('Email Template'),
                'route' => route('admin.email-settings'),
                'permission' => 'setting.view',
                'tab' => 'email_template_tab',
            ],
            (object) [
                'name' => __('SEO Setup'),
                'route' => route('admin.seo-setting'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Sitemap'),
                'route' => route('admin.sitemap.index'),
                'permission' => 'sitemap.management',
            ],
            (object) [
                'name' => __('Custom CSS'),
                'route' => route('admin.custom-code', ['type' => 'css']),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Custom JS'),
                'route' => route('admin.custom-code', ['type' => 'js']),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Clear cache'),
                'route' => route('admin.cache-clear'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Database Clear'),
                'route' => route('admin.system.cleanup'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('System Update'),
                'route' => route('admin.system-update.index'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Manage Addons'),
                'route' => route('admin.addons.view'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Manage Language'),
                'route' => route('admin.languages.index'),
                'permission' => 'language.view',
            ],
            (object) [
                'name' => __('Basic Payment'),
                'route' => route('admin.payment'),
                'permission' => 'basic.payment.view',
            ],
            (object) [
                'name' => __('Multi Currency'),
                'route' => route('admin.currency.index'),
                'permission' => 'currency.view',
            ],
            (object) [
                'name' => __('Tax'),
                'route' => route('admin.tax.index'),
                'permission' => 'tax.view',
            ],
            (object) [
                'name' => __('Manage Admin'),
                'route' => route('admin.admin.index'),
                'permission' => 'admin.view',
            ],
            (object) [
                'name' => __('Role & Permissions'),
                'route' => route('admin.role.index'),
                'permission' => 'role.view',
            ],
        ];
        usort($route_list, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return (object) $route_list;
    }
}
