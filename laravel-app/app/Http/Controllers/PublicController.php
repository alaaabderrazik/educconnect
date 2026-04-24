<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\SiteService;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\FAQ;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    private function getCommonData()
    {
        return [
            'settings' => SiteSetting::all()->pluck('value', 'key'),
            'services_list' => SiteService::where('is_active', true)->get(),
        ];
    }

    public function home()
    {
        $data = $this->getCommonData();
        $data['page'] = Page::where('page_name', 'home')->get()->keyBy('section');
        $data['services'] = SiteService::where('is_active', true)->take(3)->get();
        $data['testimonials'] = Testimonial::latest()->take(3)->get();
        $data['faqs'] = FAQ::orderBy('order')->take(5)->get();
        
        return view('public.home', $data);
    }

    public function about()
    {
        $data = $this->getCommonData();
        $data['page'] = Page::where('page_name', 'about')->get()->keyBy('section');
        $data['team'] = TeamMember::orderBy('order')->get();
        
        return view('public.about', $data);
    }

    public function services()
    {
        $data = $this->getCommonData();
        $data['page'] = Page::where('page_name', 'services')->get()->keyBy('section');
        $data['services'] = SiteService::where('is_active', true)->get();
        
        return view('public.services', $data);
    }

    public function contact()
    {
        $data = $this->getCommonData();
        $data['page'] = Page::where('page_name', 'contact')->get()->keyBy('section');
        
        return view('public.contact', $data);
    }
}
