<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticlesFixture
 */
class ArticlesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'published' => 1,
                'created' => '2023-09-21 13:02:52',
                'modified' => '2023-09-21 13:02:52',
            ],
        ];
        parent::init();
    }
}
