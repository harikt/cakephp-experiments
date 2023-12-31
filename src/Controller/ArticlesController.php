<?php
// src/Controller/ArticlesController.php
namespace App\Controller;

use Cake\I18n\I18n;

/**
 * @property \App\Model\Table\ArticlesTable $Articles
 **/
class ArticlesController extends AppController
{
    protected array $paginate = [
        'limit' => 25,
        // 'order' => [
        //     'Articles.title' => 'asc'
        // ]
    ];

    public function initialize(): void
    {
        parent::initialize();

        $lang = $this->request->getQuery('lang', 'es_AR');
        I18n::setLocale($lang);
    }

    public function index()
    {
        // Paginate the ORM table.
        // $this->set('articles', $this->paginate($this->Articles));

        // Paginate a partially completed query
        // $query = $this->Articles->find('translations');
        $this->set('articles', $this->paginate($this->Articles->find()));
    }

    public function view($slug = null)
    {
        $lang = I18n::getLocale();

        // Update retrieving tags with contain()
        $article = $this->Articles
            // ->findBySlug($slug)

            ->find('translations')
            ->where(['slug' => $slug])

            ->contain('Tags')
            ->firstOrFail();

        // pr("Current language $lang ->title : " . $article->title);

        // // This will print the details
        // pr("Using ->translation('es_AR')->title : " . $article->translation('es_AR')->title);

        // // This is not printing the details
        // pr("Using ->translation('en_GB')->title : " . $article->translation('en_GB')->title);

        // pr($article);
        // exit;

        $this->set(compact('article'));
    }


    public function add()
    {
        I18n::setLocale('en_GB');

        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list')->all();

        // Set tags to the view context
        $this->set('tags', $tags);

        $this->set('article', $article);
    }


    /**
     * Individual edit for language
     * Language is passed as a query param. @see initialize()
     */
    public function edit2($slug)
    {
        $article = $this->Articles
            // ->find('translations')
            // ->where(['slug' => $slug])
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list')->all();


        // Set tags to the view context
        $this->set('tags', $tags);

        $this->set('article', $article);
    }


    public function edit($slug)
    {
        // If there are multiple translations updated in a single call
        // 1. Your default language should be set to locale
        // 2. The default language field will be title, rest of the fields will be _translations.<lang>.<field> format in the view
        I18n::setLocale('en_GB');

        $article = $this->Articles
            ->find('translations')
            ->where(['slug' => $slug])
            ->contain('Tags')
            ->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list')->all();


        // Set tags to the view context
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->where(['slug' => $slug])->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function tags()
    {
        // The 'pass' key is provided by CakePHP and contains all
        // the passed URL path segments in the request.
        $tags = $this->request->getParam('pass');

        // Use the ArticlesTable to find tagged articles.
        $articles = $this->Articles->find('tagged', tags: $tags)
            ->all();

        // Pass variables into the view template context.
        $this->set([
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}
