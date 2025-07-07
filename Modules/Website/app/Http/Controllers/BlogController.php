<?php

namespace Modules\Website\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogTranslation;
use Modules\GlobalSetting\app\Models\SeoSetting;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('translation', 'category.translation')->orderBy('id', 'desc')->where(['status' => 1])->take(3);

        if (request()->search) {
            $blogIds = BlogTranslation::where('title', 'like', '%' . request()->search . '%')->pluck('blog_id');
            $blogs = $blogs->whereIn('id', $blogIds);
        } elseif (request()->category) {
            $category = BlogCategory::with('translation')->where('slug', request()->category)->first();
            $blogs = $blogs->where('blog_category_id', $category->id);
        } elseif (request()->tag) {
            $blogs = $blogs->where('tags', 'like', '%' . request()->tag . '%');
        }

        $perPage = cache('CustomPagination');
        $perPage = $perPage->blog_list;
        $blogs = $blogs->paginate($perPage);

        $seo_setting = SeoSetting::where('page_name', 'Blog Page');

        return view('website::pages.blog.index', compact('blogs', 'seo_setting'));
    }

    public function details(string $slug)
    {
        $blog = Blog::with('category', 'category.translation', 'translation')->where("slug", $slug)->first();

        if (!$blog) {
            return to_route('website.404');
        }
        // social share links
        $shareLinks = (object) \Share::currentPage()
            ->facebook()
            ->linkedin()
            ->twitter()
            ->pinterest()
            ->getRawLinks();

        $popular_post = Blog::latest()->whereNot("slug", $slug)->where('is_popular', 1)->take(3)->get();
        $categories = BlogCategory::with('blogs')->whereHas('blogs')->get();
        $popularTags = $this->getPopularTags();

        $perPage = cache('CustomPagination')->blog_comment;
        $comments = $blog
            ->comments()
            ->where('status', 1)
            ->where('parent_id', 0)
            ->with('children')
            ->paginate($perPage);

        $seo_setting = SeoSetting::where('page_name', 'Blog Details Page');
        return view('website::pages.blog.details', compact('blog', 'shareLinks', 'popular_post', 'categories', 'popularTags', 'comments', 'seo_setting'));
    }

    public function getPopularTags()
    {
        $blogs = Blog::all();

        // flatten and count tags
        $tags = $blogs->pluck('tags')->flatten()->countBy();
        // sort tags by count in desending order
        $tags = $tags->sortDesc()->keys();
        return $tags;
    }
}
