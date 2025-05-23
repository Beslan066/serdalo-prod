<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Symfony\Component\Yaml\Yaml;

use App\Models\Document;
use App\Models\MaterialTranslation;
use App\Models\PhotoReportage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App;
use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;
use App\Models\LitsalonArticle;
use App\Models\VideoArticle;
use Illuminate\Support\Facades\View;
use Spatie\ArrayToXml\ArrayToXml;

class HomeController extends Controller
{
    protected $is_default_locale = true;

    public function index()
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();

        $posts_main = Post::query()
            ->with(['file', 'thumb'])
            //->where('promote', 1)
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->take(17)
            ->get();


        $material_sticky = Material::
        minimalSelect()
            ->with(['file', 'thumb'])
            ->where('sticky', 1)
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('id', 'desc')
            ->first();

        $materials_main_left = Material::query()
            ->with(['file'])
            ->where('promote', 1)
            ->when($material_sticky, function ($query) use ($material_sticky) {
                $query->where('id', '!=', $material_sticky->id);
            })
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $materials_main_right = Material::query()
            ->with(['file'])
            ->where('promote', 1)
            ->when($material_sticky, function ($query) use ($material_sticky) {
                $query->where('id', '!=', $material_sticky->id);
            })
            ->when($materials_main_left, function ($query) use ($materials_main_left) {
                $query->whereNotIn('id', $materials_main_left->pluck('id'));
            })
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $materials_popular = Material::query()
            ->with(['file', 'thumb'])
            ->where('popular', 1)
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })

            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        if (App::getlocale() === 'ru') {
            $journalism = Material::query()->where('category_id', '=', 70)->latest()->take(5)->get();
        }else {
            $journalism = MaterialTranslation::query()->where('category_id', '=', 70)->orderBy('published_at', 'desc')->take(5)->get();
        }




        if (session('frontend_version') != 'v1') {
            $view = 'frontend.v3.home.index';
        } elseif (session('frontend_version') == 'v2') {
            $view = 'frontend.v2.home.index';
        } else {
            $view = 'frontend.home.index';
        }


//        $photo_articles = PhotoReportage::orderBy('created_at', 'desc')->limit(2)->get();


        $photo_articles = PhotoReportage::query()
            ->with(['file'])
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->take(2)
            ->get();


        return view($view, [
            'categories' => $categories,
            'posts_main' => $posts_main,
            'material_sticky' => $material_sticky,
            'materials_main_left' => $materials_main_left,
            'materials_main_right' => $materials_main_right,
            'photo_articles' => $photo_articles,
            'video_articles' => $this->getVideoArticles(),
            'materials_popular' => $materials_popular,
            'video_studio_section_articles' => $this->getVideoStudioSectionArticles(),
            'litsalon_section_articles' => $this->getLitsalonArticles(),
            'journalism' => $journalism,
        ]);
    }

    public function museum()
    {

        $categories = Category::all();

        if (session('frontend_version') != 'v1') {
            $view = 'frontend.v3.museum';
        } else {
            $view = 'frontend.museum';
        }
        return view($view, compact('categories'));
    }

    public function litsalon()
    {
        $categories = Category::all();
        if (session('frontend_version') != 'v1') {
            $view = 'frontend.v3.litsalon';
        } else {
            $view = 'frontend.litsalon';
        }
        return view($view, compact('categories'));
    }

    public function videostudio()
    {

        $categories = Category::all();
        if (session('frontend_version') != 'v1') {
            $view = 'frontend.v3.videostudio';
        } else {
            $view = 'frontend.videostudio';
        }
        return view($view, compact('categories'));
    }


    protected function getVideoArticles()
    {
        $posts = Post::
        minimalSelect()
            ->with(['file', 'thumb'])
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->whereHas('file', function ($query) {
                $query->where('type', 'video');
            })
            ->orderBy('published_at', 'desc')
            ->take(2)
            ->get();

        return $posts;
    }

    protected function getLitsalonArticles()
    {
        $posts = LitsalonArticle::
        minimalSelect()
            ->with(['file'])
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->whereHas('file')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return $posts;
    }

    protected function getVideoStudioSectionArticles()
    {
        $posts = VideoArticle::
        with(['file'])
            ->when(!$this->is_default_locale, function ($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->whereHas('file')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();

        return $posts;
    }

    public function generateYandexNews()
    {
        // Получить данные новостей и материалов из базы данных
        $posts = Post::orderBy('published_at', 'desc')->take(50)->get();
        $materials = Material::query()->where('category_id', '!=', 43)
            ->where('category_id', '!=', 70)
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();


        // Создать объект SimpleXMLElement для формирования XML
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0"></rss>');

        $channel = $xml->addChild('channel');
        foreach ($posts as $post) {
            // Создать элемент <item> для каждой новости
            $item = $channel->addChild('item');
            $item->addAttribute('turbo', 'true');

            $publishedDate = strtotime($post->published_at);
            $pubDate = date('D, d M Y H:i:s O', $publishedDate);

            // Добавить поля title, description, file и slug как дочерние элементы в <item>
            $item->addChild('title', $post->title);
            $item->addChild('description', $post->lead);
            $descriptionValue = $post->lead . ' ' . $post->description;
            $fullTextNode = $item->addChild('yandex:full-text', strip_tags($descriptionValue, '<p>'), 'http://news.yandex.ru');
            $item->addChild('pubDate', $pubDate);

            if ($post->file) {

                if ($post->file->type == "video") {
                    $mediaGroup = $item->addChild('media:group', '', 'http://search.yahoo.com/mrss/');
                    $mediaContent = $mediaGroup->addChild('media:content');
                    $mediaContent->addAttribute('url', $post->file->full_path);
                    $mediaContent->addAttribute('type', 'video/mp4');
                    $mediaThumbnail = $mediaGroup->addChild('media:thumbnail');
                    $mediaThumbnail->addAttribute('url', $post->thumb->full_preview_path);

                    $enclosure = $item->addChild('enclosure');
                    $enclosure->addAttribute('url', $post->thumb->full_preview_path);

                    $enclosureType = $post->thumb->type . '/' . $post->thumb->extension;
                    $enclosure->addAttribute('type', $enclosureType);
                } elseif ($post->file->type == "image") {
                    $materialFile = $post->file->full_preview_path;
                    $enclosure = $item->addChild('enclosure');
                    $enclosure->addAttribute('url', $materialFile);
                    $enclosureType = $post->file->type . '/' . $post->file->extension;
                    $enclosure->addAttribute('type', $enclosureType);
                }
            }

            // Создать ссылку с префиксом "https://serdalo.ru/post" и слагом новости
            $link = 'https://serdalo.ru/' . $post->slug;
            $item->addChild('link', $link);
        }

        foreach ($materials as $material) {
            // Создать элемент <item> для каждого материала
            $item = $channel->addChild('item');
            $item->addAttribute('turbo', 'true');

            $publishedDate = strtotime($material->published_at);
            $pubDate = date('D, d M Y H:i:s O', $publishedDate);

            // Добавить поля title, description, file и slug как дочерние элементы в <item>
            $item->addChild('title', $material->title);
            $item->addChild('description', $material->lead);
            $materialDescriptionValue = $material->lead . ' ' . $material->description;
            $fullTextNode = $item->addChild('yandex:full-text', strip_tags($materialDescriptionValue, '<p>'), 'http://news.yandex.ru');
            $item->addChild('pubDate', $pubDate);

            if ($material->file) {

                if ($material->file->type == "video") {
                    $mediaGroup = $item->addChild('media:group', '', 'http://search.yahoo.com/mrss/');
                    $mediaContent = $mediaGroup->addChild('media:content');
                    $mediaContent->addAttribute('url', $material->file->full_preview_path);
                    $mediaContent->addAttribute('type', 'video/mp4');
                    $mediaThumbnail = $mediaGroup->addChild('media:thumbnail');
                    $mediaThumbnail->addAttribute('url', $material->thumb->full_preview_path);


                    $enclosure = $item->addChild('enclosure');
                    $enclosure->addAttribute('url', $mediaThumbnail);
                    $enclosureType = $material->file->type . '/' . $material->file->extension;
                    $enclosure->addAttribute('type', $enclosureType);
                } elseif ($material->file->type == "image") {
                    $materialFile = $material->file->full_preview_path;
                    $enclosure = $item->addChild('enclosure');
                    $enclosure->addAttribute('url', $materialFile);
                    $enclosureType = $material->file->type . '/' . $material->file->extension;
                    $enclosure->addAttribute('type', $enclosureType);
                }
            }

            // Создать ссылку с префиксом "https://serdalo.ru/material" и слагом материала
            $link = 'https://serdalo.ru/material/' . $material->slug;
            $item->addChild('link', $link);
        }

        // Добавить пространство имен для yandex в корневой элемент
        $xml->addAttribute('xmlns:yandex', 'http://news.yandex.ru');

        // Преобразовать XML в строку
        $xmlString = $xml->asXML();

        $xmlString = str_replace('&nbsp;', '&#160;', $xmlString);
        // Записать XML-строку в файл yandex-news.xml
        Storage::disk('public')->put('yandex-news.xml', $xmlString);

        // Вернуть ответ с XML-файлом
        return response($xmlString, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }


    public function generateYml()
    {
        $news = Post::orderBy('published_at', 'desc')->limit(15)->get();
        
        $items = [];
        
        foreach ($news as $item) {
            $xmlItem = new \SimpleXMLElement('<item turbo="true"></item>');
            
            $xmlItem->addChild('link', 'https://serdalo.ru/news/' . $item->id);
            $xmlItem->addChild('title', htmlspecialchars($item->title));
            $xmlItem->addChild('description', htmlspecialchars($item->description));
            $xmlItem->addChild('pubDate', $item->published_at);
            $xmlItem->addChild('image', $item->file->full_path);
            
            // Create turbo:content element
            $turboContent = $xmlItem->addChild('turbo:content', '', 'http://turbo.yandex.ru');
            $turboContent->addAttribute('xmlns:turbo', 'http://turbo.yandex.ru');
    
            // Add title, lead, and image to turbo:content
            $turboContent->addChild('turbo:title', htmlspecialchars($item->title), 'http://turbo.yandex.ru');
            $turboContent->addChild('turbo:lead', htmlspecialchars($item->lead), 'http://turbo.yandex.ru');
            $turboContent->addChild('turbo:image', $item->file->full_path, 'http://turbo.yandex.ru');
            
            $items[] = $xmlItem;
        }
        
        $xmlData = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss></rss>');
        $xmlData->addAttribute('version', '2.0');
        
        $channel = $xmlData->addChild('channel');
        $channel->addChild('turboPages', 'true', 'http://turbo.yandex.ru');
        
        foreach ($items as $item) {
            $xmlItem = $channel->addChild('item');
            $xmlItem->addAttribute('turbo', 'true');
            $xmlItem->addChild('link', $item->link);
            $xmlItem->addChild('title', $item->title);
            $xmlItem->addChild('description', $item->description);
            $xmlItem->addChild('pubDate', $item->pubDate);
            $xmlItem->addChild('image', $item->image);
            
            // Add turbo:content element with text content
            $turboContent = $xmlItem->addChild('turbo:content', $item->content, 'http://turbo.yandex.ru');
            $turboContent->addAttribute('xmlns:turbo', 'http://turbo.yandex.ru');
        }
        
        $xmlContent = $xmlData->asXML();
        $xmlContent = str_replace('&nbsp;', '&#160;', $xmlContent);
        
        return response($xmlContent, 200)->header('Content-Type', 'application/xml');
    }

    public function getDocuments()
    {

        $categories = Category::all();
        $documents = Document::orderBy('id', 'desc')->paginate(10);

        return view('frontend.v3.documents.index', compact('documents', 'categories'));
    }


    public function getDocumentSingle(Document $document)
    {


        $categories = Category::all();



        return view('frontend.v3.documents.single', compact('document', 'categories'));
    }

}
