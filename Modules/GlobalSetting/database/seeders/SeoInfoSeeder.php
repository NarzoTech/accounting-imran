<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\SeoSetting;

class SeoInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeoSetting::truncate();
        $item1 = new SeoSetting();
        $item1->page_name = 'Home Page';
        $item1->seo_title = 'Home || NarzoTech';
        $item1->seo_description = 'Home || NarzoTech';
        $item1->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'About Page';
        $item2->seo_title = 'About || NarzoTech';
        $item2->seo_description = 'About || NarzoTech';
        $item2->save();

        $item3 = new SeoSetting();
        $item3->page_name = 'Contact Page';
        $item3->seo_title = 'Contact || NarzoTech';
        $item3->seo_description = 'Contact || NarzoTech';
        $item3->save();

        // blog
        $item4 = new SeoSetting();
        $item4->page_name = 'Blog Page';
        $item4->seo_title = 'Blog || NarzoTech';
        $item4->seo_description = 'Blog || NarzoTech';
        $item4->save();

        $item5 = new SeoSetting();
        $item5->page_name = 'Blog Details Page';
        $item5->seo_title = 'Blog Details || NarzoTech';
        $item5->seo_description = 'Blog Details || NarzoTech';
        $item5->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'FAQ Page';
        $item2->seo_title = 'FAQ Page || NarzoTech';
        $item2->seo_description = 'FAQ Page || NarzoTech';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Pricing Page';
        $item2->seo_title = 'Pricing || NarzoTech';
        $item2->seo_description = 'Pricing || NarzoTech';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Cart Page';
        $item2->seo_title = 'Cart || NarzoTech';
        $item2->seo_description = 'Cart || NarzoTech';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Checkout Page';
        $item2->seo_title = 'Checkout || NarzoTech';
        $item2->seo_description = 'Checkout || NarzoTech';
        $item2->save();


        $item2 = new SeoSetting();
        $item2->page_name = 'Payment Page';
        $item2->seo_title = 'Payment || NarzoTech';
        $item2->seo_description = 'Payment || NarzoTech';
        $item2->save();


        $item2 = new SeoSetting();
        $item2->page_name = 'Wishlist Page';
        $item2->seo_title = 'Wishlist || NarzoTech';
        $item2->seo_description = 'Wishlist || NarzoTech';
        $item2->save();


        $item2 = new SeoSetting();
        $item2->page_name = 'Property Page';
        $item2->seo_title = 'Property || NarzoTech';
        $item2->seo_description = 'Property || NarzoTech';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Agent Page';
        $item2->seo_title = 'Agent || NarzoTech';
        $item2->seo_description = 'Agent || NarzoTech';
        $item2->save();
    }
}
