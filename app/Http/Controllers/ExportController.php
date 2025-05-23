<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\MediaFile;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Tag;
use App\Models\TagTranslation;
use App\Models\Author;
use App\Models\AuthorTranslation;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\PostTranslation;
use App\Models\Material;
use App\Models\MaterialTranslation;
use App\Models\Newspaper;

use App\Jobs\ProcessExport;

class ExportController extends Controller
{
    /*
     * список внутренних моделей данных:
     * Post,
     */
//    public function index()
//    {
//        //$this->setMaterials();
//        //$this->setPostsTags();
//        //ProcessExport::dispatch();
//
//        $this->expTags();
//        $this->expRubrics();
//        $this->expAuthors();
//        $this->expFiles();
//        $this->expPosts();
//        $this->expMaterials();
//        $this->expNewspapers();
//
//
//        //$this->getNodeData(10029);//type=article
//
//        return view('home');
//    }
//    protected function setPostsTags()
//    {
//        $max_post_tag_id = (int)PostTag::max('post_id');
//        $posts = Post::where('id', '>', $max_post_tag_id)->limit(1000)->get();
//        foreach ($posts as $post) {
//            if(!$post->tags || $post->tags === '[]') {
//                continue;
//            }
//            $tags = json_decode($post->tags, true);
//            if(!$tags || !is_array($tags)) {
//                continue;
//            }
//            $post->tags()->sync($tags);
//        }
//    }
//    protected function expFiles()
//    {
//        $max_source = (int)MediaFile::max('source_id');
//        print("MediaFile max_source: {$max_source}<br>");
//
//        $files = DB::
//            connection('mysql-old')
//            ->select("select * from file_managed where fid > {$max_source} limit 100");
//        if(!$files) {
//            return true;
//        }
//
//        foreach ($files as $file) {
//            $mediafile = MediaFile::where('source_id', $file->fid)->first();
//            if($mediafile) {
//                continue;
//            }
//            $mediafile = new MediaFile();
//            $mediafile->source_id = $file->fid;
//            $mediafile->user_id = 1;
//            $mediafile->type = 'undefined';
//            $mediafile->extension = 'undefined';
//            $mediafile->mime = $file->filemime;
//            $mediafile->name = $file->origname;
//            $mediafile->path = $this->getNodeImageURI($file->uri);
//            $mediafile->size = $file->filesize;
//            $mediafile->save();
//        }
//        $this->expFiles();
//    }
//    protected function expMaterials()
//    {
//        $max_source = (int)Material::max('source_id');
//        print("Material max_source: {$max_source}<br>");
//
//
//        $node_field_data_materials = DB::
//            connection('mysql-old')
//            ->table('node_field_data')
//            ->leftJoin('node__field_title_h2', 'node_field_data.nid', '=', 'node__field_title_h2.entity_id')
//            ->select('node_field_data.*', 'node__field_title_h2.*')
//            ->where('node_field_data.type', 'article')
//            ->where('node_field_data.langcode', 'ru')
//            ->where('node_field_data.nid', '>', $max_source)
//            ->where('node_field_data.nid', '!=', 3329)
//            ->where('node_field_data.nid', '!=', 5488)
//            ->whereNotNull('node__field_title_h2.field_title_h2_value')
//            ->take(20)
//            ->get();
//
//        if(!count($node_field_data_materials)) {
//            return true;
//        }
//
//        foreach ($node_field_data_materials as $node_field_data_material) {
//            $material = Material::where('source_id', $node_field_data_material->nid)->first();
//            if($material) {
//                continue;
//            }
//            $data = $this->getNodeData($node_field_data_material->nid);
//            $material = new Material();
//            $material->slug = $data['path'] ?? '';
//            $material->status = $data['status'];
//            $material->promote = $data['promote'];
//            $material->sticky = $data['sticky'];
//            $material->source_id = $node_field_data_material->nid;
//            $material->user_id = 1;
//            $material->category_id = $data['category_id'];
//            $material->author_id = $data['author_id'];
//            $material->title = $data['title'];
//            $material->subtitle = $data['subtitle_html'];
//            $material->file_id = $data['image_id'];
//            $material->lead = $data['lead'];
//            $material->description = $data['content'] ?? '';
//            $material->created_at = $data['created'] ?? '';
//            $material->updated_at = $data['updated'] ?? '';
//            $material->save();
//
//            if($data['translation']) {
//                $translation = new MaterialTranslation();
//
//                $translation->slug = $data['translation']['path'] ?? '';
//                $translation->material_id = $material->id;
//                $translation->language = '';
//                $translation->user_id = 1;
//                $translation->title = $data['translation']['title'];
//                $translation->description = $data['translation']['content'] ?? '';
//                $translation->save();
//            }
//        }
//
//        $this->expMaterials();
//    }
//    protected function expPosts()
//    {
//        $max_source = (int)Post::max('source_id');
//        print("Post max_source: {$max_source}<br>");
//
//        $node_field_data_posts = DB::
//            connection('mysql-old')
//            ->table('node_field_data')
//            ->leftJoin('node__field_title_h2', 'node_field_data.nid', '=', 'node__field_title_h2.entity_id')
//            ->select('node_field_data.*', 'node__field_title_h2.*')
//            ->where('node_field_data.type', 'article')
//            ->where('node_field_data.langcode', 'ru')
//            ->where('node_field_data.nid', '>', $max_source)
//            ->where('node_field_data.nid', '!=', 3329)
//            ->where('node_field_data.nid', '!=', 5488)
//            ->whereNull('node__field_title_h2.field_title_h2_value')
//            ->take(20)
//            ->get();
//
//        if(!count($node_field_data_posts)) {
//            return true;
//        }
//
//        foreach ($node_field_data_posts as $node_field_data_post) {
//            $post = Post::where('source_id', $node_field_data_post->nid)->first();
//            if($post) {
//                continue;
//            }
//            $data = $this->getNodeData($node_field_data_post->nid);
//            $post = new Post();
//            $post->slug = $data['path'] ?? '';
//            $post->status = $data['status'];
//            $post->promote = $data['promote'];
//            $post->sticky = $data['sticky'];
//            $post->source_id = $node_field_data_post->nid;
//            $post->user_id = 1;
//            //$post->tags = $data['tags'];
//            $post->category_id = $data['category_id'];
//            $post->author_id = $data['author_id'];
//            $post->title = $data['title'];
//            $post->file_id = $data['image_id'];
//            $post->lead = $data['lead'];
//            $post->description = $data['content'] ?? '';
//            $post->created_at = $data['created'] ?? '';
//            $post->updated_at = $data['updated'] ?? '';
//            $post->save();
//
//            if($data['translation']) {
//                $translation = new PostTranslation();
//
//                $translation->slug = $data['translation']['path'] ?? '';
//                $translation->post_id = $post->id;
//                $translation->language = '';
//                $translation->user_id = 1;
//                $translation->title = $data['translation']['title'];
//                $translation->description = $data['translation']['content'] ?? '';
//                $translation->save();
//            }
//        }
//
//        //dump($node_field_data_posts);
//        $this->expPosts();
//    }
//    protected function expAuthors()
//    {
//        $node_field_data_authors = DB::connection('mysql-old')->select("select * from node_field_data where type = 'person' and langcode = 'ru'");
//
//        if(!$node_field_data_authors) {
//            return true;
//        }
//        foreach ($node_field_data_authors as $node_field_data_author) {
//            $data = $this->getNodeData($node_field_data_author->nid);
//
//            $author = Author::where('source_id', $data['source_id'])->first();
//            if($author) {
//                continue;
//            }
//            $author = new Author();
//            $author->slug = $data['path'] ?? '';
//            $author->source_id = $data['source_id'];
//            $author->user_id = 1;
//            $author->title = $data['title'];
//            $author->description = '';
//            $author->save();
//
//            if($data['translation']) {
//                $translation = new AuthorTranslation();
//
//                $translation->slug = $data['translation']['path'] ?? '';
//                $translation->author_id = $author->id;
//                $translation->language = '';
//                $translation->user_id = 1;
//                $translation->title = $data['translation']['title'];
//                $translation->save();
//            }
//        }
//
//        //dump($node_field_data_authors);
//    }
//    protected function expTags()
//    {
//        $taxonomy_term_field_data = collect(DB::connection('mysql-old')->select("select * from taxonomy_term_field_data where vid = 'tags' order by tid"));
//        $taxonomy_term_field_data_ru = $taxonomy_term_field_data->where('langcode', 'ru')->all();
//
//        if(!$taxonomy_term_field_data_ru) {
//            return true;
//        }
//        foreach ($taxonomy_term_field_data_ru as $taxonomy_item_ru) {
//            $taxonomy_term_field_data->where('langcode', 'ru')->all();
//            $taxonomy_item_inh = $taxonomy_term_field_data->where('tid', $taxonomy_item_ru->tid)->where('langcode', 'inh')->first();
//
//            $tag = Tag::where('source_id', $taxonomy_item_ru->tid)->first();
//            if($tag) {
//                continue;
//            }
//            $tag = new Tag();
//            $tag->source_id = $taxonomy_item_ru->tid;
//            $tag->title = $taxonomy_item_ru->name;
//            $tag->save();
//
//            if($taxonomy_item_inh && $taxonomy_item_inh->name !== $taxonomy_item_ru->name) {
//                $tag_translation = TagTranslation::where('tag_id', $tag->id)->first();
//                if(!$tag_translation) {
//                    $tag_translation = new TagTranslation();
//                }
//
//                $tag_translation->tag_id = $tag->id;
//                $tag_translation->language = '';
//                $tag_translation->user_id = 1;
//                $tag_translation->title = $taxonomy_item_inh->name;
//                $tag_translation->save();
//            }
//            /*dump([
//                'ru' => $taxonomy_item_ru,
//                'inh' => $taxonomy_item_inh,
//            ]);*/
//        }
//
//        /*
//        dump([
//            'taxonomy_term_field_data' => $taxonomy_term_field_data,
//            'taxonomy_term_field_data_ru' => $taxonomy_term_field_data_ru,
//        ]);*/
//    }
//    protected function expRubrics()
//    {
//        $taxonomy_term_field_data = collect(DB::connection('mysql-old')->select("select * from taxonomy_term_field_data where vid = 'rubrics' order by tid"));
//        $taxonomy_term_field_data_ru = $taxonomy_term_field_data->where('langcode', 'ru')->all();
//
//        if(!$taxonomy_term_field_data_ru) {
//            return true;
//        }
//        foreach ($taxonomy_term_field_data_ru as $taxonomy_item_ru) {
//            $taxonomy_term_field_data->where('langcode', 'ru')->all();
//            $taxonomy_item_inh = $taxonomy_term_field_data->where('tid', $taxonomy_item_ru->tid)->where('langcode', 'inh')->first();
//
//            $category = Category::where('source_id', $taxonomy_item_ru->tid)->first();
//            if($category) {
//                continue;
//            }
//            $category = new Category();
//            $category->source_id = $taxonomy_item_ru->tid;
//            $category->title = $taxonomy_item_ru->name;
//            $category->save();
//
//            if($taxonomy_item_inh && $taxonomy_item_inh->name !== $taxonomy_item_ru->name) {
//                $category_translation = new CategoryTranslation();
//
//                $category_translation->category_id = $category->id;
//                $category_translation->language = '';
//                $category_translation->user_id = 1;
//                $category_translation->title = $taxonomy_item_inh->name;
//                $category_translation->save();
//            }
//            /*dump([
//                'ru' => $taxonomy_item_ru,
//                'inh' => $taxonomy_item_inh,
//            ]);*/
//        }
//
//        /*
//        dump([
//            'taxonomy_term_field_data' => $taxonomy_term_field_data,
//            'taxonomy_term_field_data_ru' => $taxonomy_term_field_data_ru,
//        ]);*/
//    }
//
//    protected function expNewspapers()
//    {
//        $max_source = (int)Newspaper::max('source_id');
//        print("Newspaper max_source: {$max_source}<br>");
//
//        $node_field_data_newspapers = DB::
//            connection('mysql-old')
//            ->table('node_field_data')
//            ->select('node_field_data.*')
//            ->where('node_field_data.type', 'gazeta')
//            ->where('node_field_data.langcode', 'ru')
//            ->where('node_field_data.nid', '>', $max_source)
//            ->take(20)
//            ->get();
//        if(!count($node_field_data_newspapers)) {
//            return true;
//        }
//
//        foreach ($node_field_data_newspapers as $node_field_data_newspaper) {
//            $newspaper = Newspaper::where('source_id', $node_field_data_newspaper->nid)->first();
//            if($newspaper) {
//                continue;
//            }
//            $image_source = DB::
//                connection('mysql-old')->table('file_usage')->select('*')
//                ->where('type', 'node')->where('id', $node_field_data_newspaper->nid)
//                ->first();
//            if(!$image_source) {
//                continue;
//            }
//
//            $source_title = $node_field_data_newspaper->title;
//            $source_title = str_replace('№ ', '', $source_title);
//            $source_title = explode(',', $source_title);
//
//            $source_title_1 = trim($source_title[0]);
//            $source_title_1 = explode(' ', $source_title_1);
//            if($source_title_1[1] ?? null) {
//                $source_title_1 = $source_title_1[0] . ' (' . $source_title_1[1] . ')';
//            } else {
//                $source_title_1 = $source_title_1[0];
//            }
//            if(count($source_title) > 2) {
//                $source_title_1_2 = trim($source_title[1]);
//                $source_title_1_2 = explode(' ', $source_title_1_2);
//                $source_title_1 = $source_title_1 . ', ' .$source_title_1_2[0] . ' (' . $source_title_1_2[1] . ')';
//            }
//
//            $source_title_2 = trim($source_title[count($source_title)-1]);
//            foreach ([
//                'January' => 'Января',
//                'February' => 'Февраля',
//                'March' => 'Марта',
//                'April' => 'Апреля',
//                'May' => 'Мая',
//                'June' => 'Июня',
//                'July' => 'Июля',
//                'August' => 'Августа',
//                'September' => 'Сентября',
//                'October' => 'Октября',
//                'November' => 'Ноября',
//                'December' => 'Декабря',
//            ] as $key => $month) {
//                $source_title_2 = str_replace(mb_strtolower($month, 'UTF-8'), $key, $source_title_2);
//            }
//            $newspaper = new Newspaper();
//            $newspaper->source_id = $node_field_data_newspaper->nid;
//            $newspaper->status = $node_field_data_newspaper->status;
//            $newspaper->user_id = 1;
//            $newspaper->title = $source_title_1;
//            $newspaper->file_id = $this->getImageId($image_source->fid ?? null);
//            $newspaper->release_at = Carbon::parse($source_title_2)->format('Y-m-d');
//            $newspaper->created_at = Carbon::parse($node_field_data_newspaper->created);
//            $newspaper->updated_at = Carbon::parse($node_field_data_newspaper->changed);
//            $newspaper->save();
//        }
//
//        $this->expNewspapers();
//    }
//
//    protected function getNodeData($id)
//    {
//        //$id = 9446;
//        //$id = 10029;
//        //$node = DB::connection('mysql-old')->select("select * from node where nid = {$id}");
//        $node_field_data = DB::connection('mysql-old')->select("select * from node_field_data where nid = {$id} and langcode = 'ru'");
//        //$node_field_revision = DB::connection('mysql-old')->select("select * from node_field_revision where nid = {$id}");
//        //$node_revision = DB::connection('mysql-old')->select("select * from node_revision where nid = {$id}");
//        //$node__comment = DB::connection('mysql-old')->select("select * from node__comment where entity_id = {$id}");
//        $node__field_date = DB::connection('mysql-old')->select("select * from node__field_date where entity_id = {$id} and langcode = 'ru'");
//        $node__field_longread = DB::connection('mysql-old')->select("select * from node__field_longread where entity_id = {$id} and langcode = 'ru'");
//        $node__field_paragraphs = DB::connection('mysql-old')->select("select * from node__field_paragraphs where entity_id = {$id}");
//        $node__field_pub_illustration = DB::connection('mysql-old')->select("select * from node__field_pub_illustration where entity_id = {$id} and langcode = 'ru'");
//        $node__field_tags = collect(DB::connection('mysql-old')->select("select field_tags_target_id from node__field_tags where entity_id = {$id} and langcode = 'ru'"))->pluck('field_tags_target_id')->all();
//        $node__field_rubric = DB::connection('mysql-old')->select("select * from node__field_rubric where entity_id = {$id} and langcode = 'ru'");
//        $node__field_text_author = DB::connection('mysql-old')->select("select * from node__field_text_author where entity_id = {$id} and langcode = 'ru'");
//        $node__field_title_h1 = DB::connection('mysql-old')->select("select * from node__field_title_h1 where entity_id = {$id} and langcode = 'ru'");
//        $node__field_title_h2 = DB::connection('mysql-old')->select("select * from node__field_title_h2 where entity_id = {$id} and langcode = 'ru'");
//        $paragraph__field_author_photo = DB::connection('mysql-old')->select("select * from paragraph__field_author_photo where entity_id = {$id}");
//        $paragraph__field_image = DB::connection('mysql-old')->select("select * from paragraph__field_image where entity_id = {$id}");
//        $paragraph__field_stories_item = DB::connection('mysql-old')->select("select * from paragraph__field_stories_item where entity_id = {$id}");
//        $paragraph__field_text = DB::connection('mysql-old')->select("select * from paragraph__field_text where entity_id = {$id}");
//        $paragraph__field_youtube_video = DB::connection('mysql-old')->select("select * from paragraph__field_youtube_video where entity_id = {$id}");
//        //$path_alias = DB::connection('mysql-old')->select("select * from path_alias where id = {$id}");
//        $path_alias = DB::connection('mysql-old')->select("select * from path_alias where path = '/node/{$id}' and langcode = 'ru'");
//        //$taxonomy_index = DB::connection('mysql-old')->select("select * from taxonomy_index where nid = {$id}");
//
//        $file_usage = [];
//        if($node__field_pub_illustration) {
//            $file_usage = DB::connection('mysql-old')->select("select * from file_usage where id = {$node__field_pub_illustration[0]->field_pub_illustration_target_id}");
//        }
//        $illustration_file = [];
//        if($file_usage) {
//            $illustration_file = DB::connection('mysql-old')->select("select * from file_managed where fid = {$file_usage[0]->fid}");
//        }
//
//        $data_content = $this->getNodeContentData($node__field_paragraphs, 'ru');
//        $data_translation = $this->getNodeTranslation($node_field_data);
//
//        $data = [
//            'source_id' => $id,
//            'lang' => $node_field_data[0]->langcode,
//            'type' => $node_field_data[0]->type,
//            'title' => $node_field_data[0]->title,
//            'status' => $node_field_data[0]->status,
//            'promote' => $node_field_data[0]->promote,
//            'sticky' => $node_field_data[0]->sticky,
//            'title_html' => $node__field_title_h1[0]->field_title_h1_value ?? null,
//            'subtitle_html' => $node__field_title_h2[0]->field_title_h2_value ?? null,
//            'path' => $this->getNodePath($path_alias[0]->alias ?? null),
//            'is_longread' => $node__field_longread[0]->field_longread_value ?? null,
//            'created' => Carbon::parse($node_field_data[0]->created),
//            'updated' => Carbon::parse($node_field_data[0]->changed),
//            //'taxonomy_source_id' => $taxonomy_index[0]->tid ?? null,
//            'author_id' => $this->getAuthorId($node__field_text_author[0]->field_text_author_target_id ?? null),
//            'author_source_id' => $node__field_text_author[0]->field_text_author_target_id ?? null,
//            'category_id' => $this->getCategoryId($node__field_rubric[0]->field_rubric_target_id ?? null),
//            'category_source_id' => $node__field_rubric[0]->field_rubric_target_id ?? null,
//            'tags' => $this->getTagsIds($node__field_tags),
//            'tags_source' => $node__field_tags,
//            'image_id' => $this->getImageId($file_usage[0]->fid ?? null),
//            'image_source_id' => $file_usage[0]->fid ?? null,
//            //'image_path' => $this->getNodeImageURI($illustration_file[0]->uri ?? null),
//            'lead' => $data_content['lead'],
//            'content' => $this->getNodeContentString($data_content['content_items']),
//            'content_items' => $data_content['content_items'],
//            'translation' => $data_translation,
//        ];
//        /*dump($data);
//        dump([
//            //'node' => $node,
//            'node_field_data' => $node_field_data,
//            //'node__field_date' => $node__field_date,
//            //'node__field_longread' => $node__field_longread,
//            'node__field_paragraphs' => $node__field_paragraphs,
//            //'node__field_pub_illustration' => $node__field_pub_illustration,
//            //'node__field_rubric' => $node__field_rubric,
//            //'node__field_text_author' => $node__field_text_author,
//            //'node__field_title_h1' => $node__field_title_h1,
//            //'node__field_title_h2' => $node__field_title_h2,
//            'paragraph__field_author_photo' => $paragraph__field_author_photo,
//            'paragraph__field_image' => $paragraph__field_image,
//            'paragraph__field_stories_item' => $paragraph__field_stories_item,
//            'paragraph__field_text' => $paragraph__field_text,
//            'paragraph__field_youtube_video' => $paragraph__field_youtube_video,
//            //'path_alias' => $path_alias,
//            //'taxonomy_index' => $taxonomy_index,
//        ]);*/
//        return $data;
//    }
//    protected function getNodeImageTag($original_uri)
//    {
//        if(!$original_uri) {
//            return $original_uri;
//        }
//        return '<img src="' . $this->getNodeImageURI($original_uri) . '">';
//    }
//    protected function getNodePath($original_path)
//    {
//        if(!$original_path) {
//            return $original_path;
//        }
//        return substr($original_path, 1);
//    }
//    protected function getNodeImageURI($original_uri)
//    {
//        if(!$original_uri) {
//            return $original_uri;
//        }
//        return str_replace('public://', '/sites/default/files/', $original_uri);
//    }
//    protected function getNodeTranslation($node_field_data)
//    {
//        $node_field_data_inh = DB::connection('mysql-old')->select("select * from node_field_data where nid = {$node_field_data[0]->nid} and langcode = 'inh'");
//        if(!$node_field_data_inh) {
//            return [];
//        }
//        $id = $node_field_data_inh[0]->nid;
//        $node__field_title_h1 = DB::connection('mysql-old')->select("select * from node__field_title_h1 where entity_id = {$id} and langcode = 'inh'");
//        $node__field_title_h2 = DB::connection('mysql-old')->select("select * from node__field_title_h2 where entity_id = {$id} and langcode = 'inh'");
//        $node__field_paragraphs = DB::connection('mysql-old')->select("select * from node__field_paragraphs where entity_id = {$id}");
//        $path_alias = DB::connection('mysql-old')->select("select * from path_alias where path = '/node/{$id}' and langcode = 'inh'");
//
//        $data_content = $this->getNodeContentData($node__field_paragraphs, 'inh');
//
//        $data = [
//            'title' => $node_field_data_inh[0]->title,
//            'title_html' => $node__field_title_h1[0]->field_title_h1_value ?? null,
//            'subtitle_html' => $node__field_title_h2[0]->field_title_h2_value ?? null,
//            'path' => $this->getNodePath($path_alias[0]->alias ?? null),
//            'lead' => $data_content['lead'],
//            'content' => $this->getNodeContentString($data_content['content_items']),
//            'content_items' => $data_content['content_items'],
//        ];
//
//        return $data;
//    }
//    protected function getNodeContentString($content_items)
//    {
//        if(!$content_items) {
//            return '';
//        }
//        $content = '';
//        foreach ($content_items as $content_item) {
//            if( !($content_item['content'] ?? null) ) {
//                continue;
//            }
//            $content .= $content_item['content'];
//        }
//        return $content;
//    }
//    protected function getNodeContentData($node__field_paragraphs, $lang)
//    {
//        $lead = null;
//        $content_items = [];
//        if(!$node__field_paragraphs) {
//            return [
//                'lead' => $lead,
//                'content_items' => $content_items
//            ];
//        }
//        foreach($node__field_paragraphs as $node__field_paragraph) {
//            $paragraphs_item = DB::connection('mysql-old')->select("select * from paragraphs_item where id = {$node__field_paragraph->field_paragraphs_target_id}");
//            if(!$paragraphs_item) {
//                continue;
//            }
//            $content_item = [];
//            if($paragraphs_item[0]->type === 'leader' || $paragraphs_item[0]->type === 'text') {
//                $paragraph__field_text = DB::connection('mysql-old')->select("select * from paragraph__field_text where entity_id = {$paragraphs_item[0]->id} and langcode = '{$lang}'");
//                if($paragraphs_item[0]->type === 'leader') {
//                    if($paragraph__field_text) {
//                        $lead = $paragraph__field_text[0]->field_text_value;
//                    }
//                    continue;
//                }
//                if($paragraph__field_text) {
//                    $content_item['order'] = $node__field_paragraph->delta;
//                    $content_item['type'] = $paragraph__field_text[0]->bundle;
//                    $content_item['content'] = $paragraph__field_text[0]->field_text_value;
//                }
//            } elseif($paragraphs_item[0]->type === 'image') {
//                $paragraph__field_image = DB::connection('mysql-old')->select("select * from paragraph__field_image where entity_id = {$paragraphs_item[0]->id} and langcode = '{$lang}'");
//                if(!$paragraph__field_image) {
//                    continue;
//                }
//                $paragraphs_item_file_usage = DB::connection('mysql-old')->select("select * from file_usage where id = {$paragraphs_item[0]->id}");
//                $paragraphs_item_file = [];
//                if($paragraphs_item_file_usage) {
//                    $paragraphs_item_file = DB::connection('mysql-old')->select("select * from file_managed where fid = {$paragraphs_item_file_usage[0]->fid}");
//                }
//                if(!$paragraphs_item_file) {
//                    continue;
//                }
//                $content_item['order'] = $node__field_paragraph->delta;
//                $content_item['type'] = 'image';
//                $content_item['source_image_id'] = $paragraphs_item_file_usage[0]->fid ?? null;
//                $content_item['content'] = $this->getNodeImageTag($paragraphs_item_file[0]->uri ?? null);
//            }
//            $content_items[] = $content_item;
//        }
//
//        return [
//            'lead' => $lead,
//            'content_items' => $content_items
//        ];
//    }
//    protected function getCategoryId($source_id)
//    {
//        if(!$source_id) {
//            return null;
//        }
//        $data = DB::select("select id from categories where source_id = {$source_id} limit 1");
//        return $data[0]->id ?? null;
//    }
//    protected function getAuthorId($source_id)
//    {
//        if(!$source_id) {
//            return null;
//        }
//        $data = DB::select("select id from authors where source_id = {$source_id} limit 1");
//        return $data[0]->id ?? null;
//    }
//    protected function getImageId($source_id)
//    {
//        if(!$source_id) {
//            return null;
//        }
//        $data = DB::select("select id from files where source_id = {$source_id} limit 1");
//        return $data[0]->id ?? null;
//    }
//    protected function getTagsIds($source_ids)
//    {
//        if(!$source_ids) {
//            return json_encode([]);
//        }
//        $source_ids_str = implode(',', $source_ids);
//        $data = collect(DB::select("select id from tags where source_id in ({$source_ids_str})"))->pluck('id')->all();
//        return json_encode($data ?? []);
//    }
}
