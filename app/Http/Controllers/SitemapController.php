<?php

namespace App\Http\Controllers;

use App\Models\offers;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $sitemap .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // Homepage
        $sitemap .= $this->addUrl('/', '1.0', 'daily', now());

        // Static pages
        $sitemap .= $this->addUrl('/store', '0.9', 'daily', now());
        $sitemap .= $this->addUrl('/about', '0.5', 'monthly', now());
        $sitemap .= $this->addUrl('/contact', '0.6', 'monthly', now());

        // Categories
        $categories = Categorie::all();
        foreach ($categories as $category) {
            $sitemap .= $this->addUrl("/store?category={$category->id}", '0.8', 'weekly', $category->updated_at);
        }

        // Products
        $products = offers::where('status', 'active')
            ->where('quantity', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($products as $product) {
            $imageUrl = null;
            if ($product->images && is_array(json_decode($product->images))) {
                $images = json_decode($product->images);
                if (!empty($images)) {
                    $imageUrl = asset('storage/' . $images[0]);
                }
            }

            $sitemap .= $this->addUrl(
                "/product/{$product->id}", 
                '0.7', 
                'weekly', 
                $product->updated_at,
                $imageUrl,
                $product->name
            );
        }

        $sitemap .= '</urlset>';

        return Response::make($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=utf-8'
        ]);
    }

    private function addUrl($loc, $priority = '0.5', $changefreq = 'monthly', $lastmod = null, $imageUrl = null, $imageTitle = null)
    {
        $url = '  <url>' . "\n";
        $url .= '    <loc>' . url($loc) . '</loc>' . "\n";
        
        if ($lastmod) {
            $url .= '    <lastmod>' . $lastmod->format('Y-m-d\TH:i:s+00:00') . '</lastmod>' . "\n";
        }
        
        $url .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $url .= '    <priority>' . $priority . '</priority>' . "\n";

        // Add image if provided
        if ($imageUrl && $imageTitle) {
            $url .= '    <image:image>' . "\n";
            $url .= '      <image:loc>' . htmlspecialchars($imageUrl) . '</image:loc>' . "\n";
            $url .= '      <image:title>' . htmlspecialchars($imageTitle) . '</image:title>' . "\n";
            $url .= '    </image:image>' . "\n";
        }

        $url .= '  </url>' . "\n";

        return $url;
    }
}
