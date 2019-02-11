<?php

namespace App\Repositories;

use Waynestate\Api\News;
use Illuminate\Cache\Repository;
use Contracts\Repositories\ArticleRepositoryContract;

class ArticleRepository implements ArticleRepositoryContract
{
    /** @var News */
    protected $newsApi;

    /** @var Repository */
    protected $cache;

    /**
     * Construct the repository.
     *
     * @param news $newsApi
     * @param Repository $cache
     */
    public function __construct(News $newsApi, Repository $cache)
    {
        $this->newsApi = $newsApi;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function listing($application_ids, $limit=5, $page=1, $topics=[])
    {
        if (empty($application_ids)) {
            return ['articles' => []];
        }

        $params = [
            'perPage' =>  $limit,
            'page' => $page,
            'application_ids' => $application_ids,
            'method' => 'articles',
        ];

        if (!empty($topics)) {
            $params['method'] = 'articles/topics';
            $params['topics'] = $topics;
        }

        $articles['articles'] = $this->cache->remember($params['method'].md5(serialize($params)), config('cache.ttl'), function () use ($params) {
            $items = $this->newsApi->request($params['method'], $params);

            if (!empty($items['data'])) {
                $items['data'] = collect($items['data'])->map(function ($item) {
                    return $this->setArticleLink($item);
                })->toArray();
            }

            return $items;
        });

        return $articles;
    }

    /**
     * {@inheritdoc}
     */
    public function find($id, $application_ids)
    {
        $params = [
            'method' => 'articles/'.$id,
            'application_ids' => $application_ids,
        ];

        $article['article'] = $this->cache->remember($params['method'].md5(serialize($params)), config('cache.ttl'), function () use ($params) {
            return $this->newsApi->request($params['method'], $params);
        });

        return $article;
    }

    /**
     * {@inheritdoc}
     */
    public function getImageUrl($article)
    {
        if (!empty($article['hero_image']['url'])) {
            return $article['hero_image']['url'];
        }

        $doc = new \DOMDocument();
        @$doc->loadHTML($article['body']);
        $images = $doc->getElementsByTagName('img');

        if ($images->item(0) !== null) {
            return $images->item(0)->getAttribute('src');
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setArticleLink($item)
    {
        if (empty($item['link'])) {
            $item['link'] = '/'.config('base.news_view_route').'/'.$item['permalink'].'-'.$item['id'];
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaging($meta, $page)
    {
        if (empty($page)) {
            $meta['next_page_url'] = null;
            $meta['prev_page_url'] = ($meta['total'] < $meta['per_page']) ? null : url()->current().'?page=2';
        } elseif ($page == $meta['last_page']) {
            $meta['next_page_url'] = url()->current().'?page='.($page-1);
            $meta['prev_page_url'] = null;
        } else {
            $meta['next_page_url'] = url()->current().(($page-1 == 1) ? '' : '?page='.($page-1));
            $meta['prev_page_url'] = url()->current().'?page='.($page+1);
        }

        return $meta;
    }
}