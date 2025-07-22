<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\Setting;

class GlobalSettingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();

        $setting_data = [
            'app_name' => 'Unique Cargo',
            'copyright_text' => 'Copyright © {year} Unique Cargo. All rights reserved.',
            'version' => '1.0.0',
            'theme' => 1,
            'logo' => 'uploads/website-images/logo_1.png',
            'admin_logo' => 'uploads/website-images/logo_1.png',
            'logo_theme_2' => 'uploads/website-images/logo_2.png',
            'logo_theme_3' => 'uploads/website-images/logo_3.png',
            'footer_logo' => 'uploads/website-images/footer_logo.png',
            'login' => 'uploads/website-images/logo.png',
            'timezone' => 'Asia/Dhaka',
            'date_format' => 'Y-m-d',
            'time_format' => 'h:i A',
            'admin_favicon' => 'uploads/website-images/logo_1.png',
            'favicon' => 'uploads/website-images/favicon.png',

            'default_avatar' => 'uploads/website-images/default-avatar.png',
            'breadcrumb_image' => 'uploads/website-images/breadcrumb-image.jpg',
            'mail_host' => 'sandbox.smtp.mailtrap.io',
            'mail_sender_email' => 'sender@gmail.com',
            'mail_username' => 'mail_username',
            'mail_password' => 'mail_password',
            'mail_port' => 2525,
            'mail_encryption' => 'ssl',
            'mail_sender_name' => 'NarzoTech',
            'contact_message_receiver_mail' => 'receiver@gmail.com',
            'maintenance_mode' => 0,
            'maintenance_image' => 'uploads/website-images/maintenance.jpg',
            'maintenance_title' => 'Website Under maintenance',
            'maintenance_description' => '<p>We are currently performing maintenance on our website to<br>improve your experience. Please check back later.</p>
            <p><a title="NarzoTech" href="https://NarzoTech.com/">NarzoTech</a></p>',
            'last_update_date' => date('Y-m-d H:i:s'),
            'is_queable' => 'inactive',
            'comments_auto_approved' => 'active',
            'contact_team_member' => 'active',
            'search_engine_indexing' => 'active',
            'project' => 'maxland',
            'company_address' => '123 Main Street, City, Country',
            'company_phone' => '+1234567890',
            'company_email' => 'email@example.com',
            'invoice_start' => '0001',
            'invoice_prefix' => 'INV-',
        ];

        foreach ($setting_data as $index => $setting_item) {
            $new_item = new Setting();
            $new_item->key = $index;
            $new_item->value = $setting_item;
            $new_item->save();
        }
    }
}
