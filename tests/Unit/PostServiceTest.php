<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PostService;
use App\Repositories\PostRepository;
use App\Dto\PostSearchByCriteriaDto;
use Mockery;
use Illuminate\Database\Eloquent\Builder;

class PostServiceTest extends TestCase
{
    public function test_get_posts_with_mock_repository()
    {
        $repositoryMock = Mockery::mock(PostRepository::class);

        $queryMock = Mockery::mock(Builder::class);

        $repositoryMock->shouldReceive('queryPosts')
            ->with([], null, 'asc', null) 
            // [] filters, null sort, 'asc' direction, null commentFilter (pod pretpostavkom da su default vrijednosti)
            ->once()
            ->andReturn($queryMock);

        $queryMock->shouldReceive('count')
            ->once()
            ->andReturn(5);

        $repositoryMock->shouldReceive('applyPagination')
            ->with($queryMock, 1, 10) // default page=1, limit=10
            ->once()
            ->andReturn($queryMock);

        $queryMock->shouldReceive('get')
            ->once()
            ->andReturn(collect([
                (object) ['id' => 1, 'topic' => 'mock post']
            ]));


        $dto = new PostSearchByCriteriaDto();

        $service = new PostService($repositoryMock);
        $result = $service->getPosts($dto);

        // Testiramo rezultat
        $this->assertIsArray($result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertEquals(5, $result['count']);
        $this->assertCount(1, $result['result']);
        $this->assertEquals('mock post', $result['result'][0]->topic);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
