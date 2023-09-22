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

        I18n::setLocale('en_AR');
    }

    public function index()
    {
        // Paginate the ORM table.
        // $this->set('articles', $this->paginate($this->Articles));

        // Paginate a partially completed query
        // $query = $this->Articles->find();
        $this->set('articles', $this->paginate($this->Articles->find('translations')));
    }

    public function view($slug = null)
    {
        I18n::setLocale('es_AR');

        // Update retrieving tags with contain()
        $article = $this->Articles
            ->findBySlug($slug)

            // ->find('translations')
            // ->where(['slug' => $slug])

            ->contain('Tags')
            ->firstOrFail();

        // pr($article);
        // exit;

        $this->set(compact('article'));
    }


    public function add2()
    {
        $this->add();
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

    public function edit($slug)
    {
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
