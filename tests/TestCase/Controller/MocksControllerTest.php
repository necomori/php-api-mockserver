<?php
/**
 * Copyright (c) necomori LLC (https://necomori.asia)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  Copyright (c) necomori LLC (https://necomori.asia)
 * @since      0.1.0
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Test\TestCase\Controller;

use Cake\Datasource\ModelAwareTrait;
use Cake\TestSuite\IntegrationTestCase;
use Cake\Utility\Hash;

/**
 * MocksController に対するテスト
 *
 * @package App\Test\TestCase\Controller
 */
class MocksControllerTest extends IntegrationTestCase
{
    use ModelAwareTrait;

    /** @var \App\Model\Table\ResourcesTable */
    protected $Resources;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.resources',
    ];

    public $autoFixtures = false;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $_SERVER['REQUEST_URI'] = null;
        $_SERVER['REQUEST_METHOD'] = null;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_001_データが登録されていない場合、0件のレスポンスを取得することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $this->get('/mocks');

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->assertResponseEquals('[]');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_002_データが10件登録されている場合、10件のデータを取得することができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/002/Resources.php'
        );

        $this->get('/mocks');

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->assertResponseCount(10);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_003_データが11件登録されている場合、11件のデータを取得することができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/003/Resources.php'
        );

        $this->get('/mocks');

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->assertResponseCount(11);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_004_データが登録されていない、かつ、存在しないデータの詳細を見ようとした場合、ステータスコードが404になる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $this->get('/mock/1');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_005_データが10件登録されている、かつ、存在しないデータの詳細を見ようとした場合、ステータスコードが404になる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/002/Resources.php'
        );

        $this->get('/mocks/11');

        $this->assertResponseCode(404);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_006_データが登録されている場合、指定したデータを取得することができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/002/Resources.php'
        );

        $this->get('/mocks/10');

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $resource = $this->decodeResponse();
        $this->assertSame('/v1/resource10', Hash::get($resource, 'url'));
        $this->assertSame('GET', Hash::get($resource, 'method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_007_データが登録されていない場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find()->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_008_urlの文字列が255文字だった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => str_repeat('a', 255),
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find()->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame(str_repeat('a', 255), Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_009_urlに256文字あった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => str_repeat('a', 256),
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_010_urlが空文字列の場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_011_urlがnullだった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => null,
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_012_urlの指定がない場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_013_methodの指定がPOSTだった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'POST',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('POST', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_014_methodの指定がPUTだった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'PUT',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('PUT', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_015_methodの指定がDELETEだった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users/1',
            'method' => 'DELETE',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users/1', Hash::get($resources, '0.url'));
        $this->assertSame('DELETE', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_016_methodが不正な文字だった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => str_repeat('a', 256),
            'method' => 'abc',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_017_methodが空文字列の場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => '',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_018_methodがnullだった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => null,
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_019_methodの指定がない場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_020_responseに1001字あった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => str_repeat('a', 1001),
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_021_responseにcodeが含まれなかった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_022_responseにbodyが含まれなかった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_023_responseが空文字列の場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => '',
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_024_responseがnullだった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => null,
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_025_responseの指定がない場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_026_データが登録されている場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/jobs',
            'method' => 'POST',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(2, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
        $this->assertSame('/v1/jobs', Hash::get($resources, '1.url'));
        $this->assertSame('POST', Hash::get($resources, '1.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_027_登録済みのデータと同じurlだがmethod違いのものを登録しようとした場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'POST',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(201);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(2, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
        $this->assertSame('/v1/users', Hash::get($resources, '1.url'));
        $this->assertSame('POST', Hash::get($resources, '1.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_028_登録済みのデータと同じurlとmethodのものを登録しようとした場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->post('/mocks', $data);

        $this->assertResponseCode(409);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_029_データが登録されている場合、データの更新を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find()->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_030_urlの文字列が255文字だった場合、データの更新を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => str_repeat('a', 255),
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->patch('/mocks/1', json_encode($data));

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find()->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame(str_repeat('a', 255), Hash::get($resources, '0.url'));
        $this->assertSame('GET', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_031_urlに256文字あった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => str_repeat('a', 256),
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_032_urlが空文字列の場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_033_urlがnullだった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => null,
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_034_urlの指定がない場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_035_methodの指定がPOSTだった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'POST',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('POST', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_036_methodの指定がPUTだった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'PUT',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users', Hash::get($resources, '0.url'));
        $this->assertSame('PUT', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_037_methodの指定がDELETEだった場合、データの登録を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users/1',
            'method' => 'DELETE',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
        $this->assertSame('/v1/users/1', Hash::get($resources, '0.url'));
        $this->assertSame('DELETE', Hash::get($resources, '0.method'));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_038_methodが不正な文字だった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => str_repeat('a', 256),
            'method' => 'abc',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_039_methodが空文字列の場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => '',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_040_methodがnullだった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => null,
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_041_methodの指定がない場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_042_responseに1001字あった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => str_repeat('a', 1001),
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_043_responseにcodeが含まれなかった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_044_responseにbodyが含まれなかった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_045_responseが空文字列の場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => '',
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_046_responseがnullだった場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => null,
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_047_responseの指定がない場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(400);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_048_データが登録されていない場合、更新しようとすると、ステータスコードが400になる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $this->put('/mocks/1');

        $this->assertResponseCode(400);
        $this->assertNoRedirect();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_049_存在しないデータを更新しようとした場合、ステータスコードが400になる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $this->put('/mocks/2');

        $this->assertResponseCode(400);
        $this->assertNoRedirect();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_050_データが登録されていない場合、更新しようとすると、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(404);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(0, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_051_存在しないデータを更新しようとした場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/026/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/2', json_encode($data));

        $this->assertResponseCode(404);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(1, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_052_登録済みのデータと同じurlとmethodのものを上書きしようとした場合、データの更新を行うことができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/052/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/1', json_encode($data));

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(2, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_053_登録済みのデータと同じurlとmethodのものを登録しようとした場合、バリデーションエラーにすることができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/052/Resources.php'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => [
                'code' => 200,
                'body' => 'OK',
            ],
        ];

        $this->put('/mocks/2', json_encode($data));

        $this->assertResponseCode(409);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(2, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_054_データが登録されていない、かつ、存在しないデータを削除しようとした場合、ステータスコードが404になる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $this->delete('/mocks/1');

        $this->assertResponseCode(404);
        $this->assertNoRedirect();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_055_データが10件登録されている、かつ、存在しないデータを削除しようとした場合、ステータスコードが404になる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/002/Resources.php'
        );

        $this->delete('/mocks/11');

        $this->assertResponseCode(404);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(10, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_056_データが登録されている場合、指定したデータを削除することができる()
    {
        $this->loadFixtures(
            'Resources:Controller/MocksController/002/Resources.php'
        );

        $this->delete('/mocks/10');

        $this->assertResponseCode(200);
        $this->assertNoRedirect();

        $this->loadModel('Resources');
        /** @var array $resources */
        $resources = $this->Resources->find('all', [
            'order' => 'Resources.id',
        ])->toArray();

        $this->assertCount(9, $resources);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_057_リソースが登録されていない場合、アクセスすると404のレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users');

        $this->assertContentType('application/json');
        $this->assertResponseCode(404);
        $this->assertResponseEquals('{"status":404,"message":"Not Found"}');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_058_GETのリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => json_encode([
                'code' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'OK',
                ],
            ]),
        ];

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->enableRetainFlashMessages();
        $this->post('/resources/add', $data);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users');

        $this->assertContentType('application/json');
        $this->assertResponseCode(200);
        $this->assertResponseEquals('{"status":200,"message":"OK"}');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_059_POSTのリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'POST',
            'response' => json_encode([
                'code' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'OK',
                ],
            ]),
        ];

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->enableRetainFlashMessages();
        $this->post('/resources/add', $data);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->post('/v1/users');

        $this->assertContentType('application/json');
        $this->assertResponseCode(200);
        $this->assertResponseEquals('{"status":200,"message":"OK"}');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_060_PUTのリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'PUT',
            'response' => json_encode([
                'code' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'OK',
                ],
            ]),
        ];

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->enableRetainFlashMessages();
        $this->post('/resources/add', $data);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->put('/v1/users');

        $this->assertContentType('application/json');
        $this->assertResponseCode(200);
        $this->assertResponseEquals('{"status":200,"message":"OK"}');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_061_DELETEのリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'DELETE',
            'response' => json_encode([
                'code' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'OK',
                ],
            ]),
        ];

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->enableRetainFlashMessages();
        $this->post('/resources/add', $data);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->delete('/v1/users');

        $this->assertContentType('application/json');
        $this->assertResponseCode(200);
        $this->assertResponseEquals('{"status":200,"message":"OK"}');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_062_クエリパラメータ付きのリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users?q=abc',
            'method' => 'GET',
            'response' => json_encode([
                'code' => 200,
                'body' => [
                    'status' => 200,
                    'message' => 'OK',
                ],
            ]),
        ];

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->enableRetainFlashMessages();
        $this->post('/resources/add', $data);

        $_SERVER['REQUEST_URI'] = '/v1/users?q=abc';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users?q=abc');

        $this->assertContentType('application/json');
        $this->assertResponseCode(200);
        $this->assertResponseEquals('{"status":200,"message":"OK"}');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_063_複数階層のリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        for ($i = 1; $i <= 10; $i++) {
            $list = [];
            for ($j = 1; $j <= $i; $j++) {
                $list[] = substr('abcdefghijklmnopqrstuvwxyz', 0, mt_rand(1, 10));
            }

            $url = sprintf('/%s', implode('/', $list));

            $data = [
                'url' => $url,
                'method' => 'GET',
                'response' => json_encode([
                    'code' => 200,
                    'body' => [
                        'status' => 200,
                        'message' => 'OK',
                    ],
                ]),
            ];

            $this->loadModel('Resources');

            /** @var \App\Model\Entity\Resource $resource */
            $resource = $this->Resources->newEntity();
            $resource = $this->Resources->patchEntity($resource, $data);
            $this->Resources->save($resource);

            $_SERVER['REQUEST_URI'] = $url;
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $this->get($url);

            $this->assertContentType('application/json');
            $this->assertResponseCode(200);
            $this->assertResponseEquals('{"status":200,"message":"OK"}');
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_064_クエリパラメータ付きの複数階層のリソースが登録されている場合、そのリソースにアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        for ($i = 1; $i <= 10; $i++) {
            $list = [];
            for ($j = 1; $j <= $i; $j++) {
                $list[] = substr('abcdefghijklmnopqrstuvwxyz', 0, mt_rand(1, 10));
            }

            $url = sprintf('/%s?q=%d', implode('/', $list), $i);

            $data = [
                'url' => $url,
                'method' => 'GET',
                'response' => json_encode([
                    'code' => 200,
                    'body' => [
                        'status' => 200,
                        'message' => 'OK',
                    ],
                ]),
            ];

            $this->loadModel('Resources');

            /** @var \App\Model\Entity\Resource $resource */
            $resource = $this->Resources->newEntity();
            $resource = $this->Resources->patchEntity($resource, $data);
            $this->Resources->save($resource);

            $_SERVER['REQUEST_URI'] = $url;
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $this->get($url);

            $this->assertContentType('application/json');
            $this->assertResponseCode(200);
            $this->assertResponseEquals('{"status":200,"message":"OK"}');
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_065_レスポンスが複数回登録されている場合、そのリソースに初回アクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => json_encode([
                [
                    'code' => 200,
                    'body' => [
                        'status' => 200,
                        'message' => 'message 200',
                    ],
                ],
                [
                    'code' => 302,
                    'body' => [
                        'status' => 302,
                        'message' => 'message 302',
                    ],
                ],
                [
                    'code' => 404,
                    'body' => [
                        'status' => 404,
                        'message' => 'message 404',
                    ],
                ],
            ]),
            'count' => 0,
        ];

        $this->loadModel('Resources');

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->newEntity();
        $resource = $this->Resources->patchEntity($resource, $data);
        $this->Resources->save($resource);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users');

        $code = 200;
        $this->assertContentType('application/json');
        $this->assertResponseCode($code);
        $this->assertResponseEquals(sprintf('{"status":%d,"message":"message %d"}', $code, $code));

        $resource = $this->Resources->find()->first();
        $this->assertSame(1, $resource->count);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_066_レスポンスが3回分登録されている場合、そのリソースに2回めのアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => json_encode([
                [
                    'code' => 200,
                    'body' => [
                        'status' => 200,
                        'message' => 'message 200',
                    ],
                ],
                [
                    'code' => 302,
                    'body' => [
                        'status' => 302,
                        'message' => 'message 302',
                    ],
                ],
                [
                    'code' => 404,
                    'body' => [
                        'status' => 404,
                        'message' => 'message 404',
                    ],
                ],
            ]),
            'count' => 1,
        ];

        $this->loadModel('Resources');

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->newEntity();
        $resource = $this->Resources->patchEntity($resource, $data);
        $this->Resources->save($resource);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users');

        $code = 302;
        $this->assertContentType('application/json');
        $this->assertResponseCode($code);
        $this->assertResponseEquals(sprintf('{"status":%d,"message":"message %d"}', $code, $code));

        $resource = $this->Resources->find()->first();
        $this->assertSame(2, $resource->count);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_067_レスポンスが3回分登録されている場合、そのリソースに3回めのアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => json_encode([
                [
                    'code' => 200,
                    'body' => [
                        'status' => 200,
                        'message' => 'message 200',
                    ],
                ],
                [
                    'code' => 302,
                    'body' => [
                        'status' => 302,
                        'message' => 'message 302',
                    ],
                ],
                [
                    'code' => 404,
                    'body' => [
                        'status' => 404,
                        'message' => 'message 404',
                    ],
                ],
            ]),
            'count' => 2,
        ];

        $this->loadModel('Resources');

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->newEntity();
        $resource = $this->Resources->patchEntity($resource, $data);
        $this->Resources->save($resource);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users');

        $code = 404;
        $this->assertContentType('application/json');
        $this->assertResponseCode($code);
        $this->assertResponseEquals(sprintf('{"status":%d,"message":"message %d"}', $code, $code));

        $resource = $this->Resources->find()->first();
        $this->assertSame(3, $resource->count);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function test_068_レスポンスが3回分登録されている場合、そのリソースに4回めのアクセスすると登録されたレスポンスを返却することができる()
    {
        $this->loadFixtures(
            'Resources'
        );

        $data = [
            'url' => '/v1/users',
            'method' => 'GET',
            'response' => json_encode([
                [
                    'code' => 200,
                    'body' => [
                        'status' => 200,
                        'message' => 'message 200',
                    ],
                ],
                [
                    'code' => 302,
                    'body' => [
                        'status' => 302,
                        'message' => 'message 302',
                    ],
                ],
                [
                    'code' => 404,
                    'body' => [
                        'status' => 404,
                        'message' => 'message 404',
                    ],
                ],
            ]),
            'count' => 3,
        ];

        $this->loadModel('Resources');

        /** @var \App\Model\Entity\Resource $resource */
        $resource = $this->Resources->newEntity();
        $resource = $this->Resources->patchEntity($resource, $data);
        $this->Resources->save($resource);

        $_SERVER['REQUEST_URI'] = '/v1/users';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->get('/v1/users');

        $code = 200;
        $this->assertContentType('application/json');
        $this->assertResponseCode($code);
        $this->assertResponseEquals(sprintf('{"status":%d,"message":"message %d"}', $code, $code));

        $resource = $this->Resources->find()->first();
        $this->assertSame(4, $resource->count);
    }

    /**
     * @param int $count
     * @return void
     */
    protected function assertResponseCount($count)
    {
        $this->assertCount($count, $this->decodeResponse());
    }

    /**
     * @return mixed
     */
    protected function decodeResponse()
    {
        return json_decode($this->_response, true);
    }
}
