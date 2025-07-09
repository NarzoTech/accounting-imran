<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::truncate();
        $templates = [
            [
                'name' => 'password_reset',
                'subject' => 'Password Reset',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Do you want to reset your password? Please Click the following link and Reset Your Password.</p>',
            ],
            [
                'name' => 'contact_mail',
                'subject' => 'Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Email: {{email}}</p>
                <p>Phone: {{phone}}</p>
                <p>Subject: {{subject}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name' => 'property_contact_mail',
                'subject' => 'Property Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Property Name: {{property_name}}</p>
                <p>Time: {{time}}</p>
                <p>Date: {{date}}</p>
                <p>Email: {{email}}</p>
                <p>Phone: {{phone}}</p>
                <p>Subject: {{subject}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name' => 'subscribe_notification',
                'subject' => 'Subscribe Notification',
                'message' => '<p>Hi there, Congratulations! Your Subscription has been created successfully. Please Click the following link and Verified Your Subscription. If you will not approve this link, you can not get any newsletter from us.</p>',
            ],
            [
                'name' => 'social_login',
                'subject' => 'Social Login',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Welcome to {{app_name}}! Your account has been created successfully.</p>
                <p>Your password: {{password}}</p>
                <p>You can log in to your account at <a href="https://narzotech.com">https://narzotech.com</a></p>
                <p>Thank you for joining us.</p>',
            ],

            [
                'name' => 'user_verification',
                'subject' => 'User Verification',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Congratulations! Your account has been created successfully. Please click the following link to activate your account.</p>',
            ],
            [
                'name' => 'Subscription Successful',
                'subject' => 'Subscription Successful',
                'message' => '<h4>Dear <b>{{user_name}}</b>,</h4>
<p> Thanks for your new order. Your order ID has been submitted.</p>
<p><span style="font-size: 1rem;">Payment Method :</span><b style="font-size: 1rem;"> {{payment_method}}</b></p>
<p>Total amount: <b>{{amount}}</b></p>
<p>Payment Status: <b>{{payment_status}}</b></p>
<p>Order Status: <b>{{order_status}}</b></p>
<p>Order Date: <b>{{order_date}}</b></p>
<p><b>{{order_details}}</b></p>',
            ],

        ];

        foreach ($templates as $index => $template) {
            $new_template = new EmailTemplate();
            $new_template->name = $template['name'];
            $new_template->subject = $template['subject'];
            $new_template->message = $template['message'];
            $new_template->save();
        }
    }
}
