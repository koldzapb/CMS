<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CommentService;
use App\Repositories\CommentRepository;
use App\Dto\CommentSearchByCriteriaDto;
use Mockery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CommentServiceTest extends TestCase
{
    public function test_get_comments_with_mock_repository()
    {
        $repositoryMock = Mockery::mock(CommentRepository::class);

        $queryMock = Mockery::mock(Builder::class);

        $repositoryMock->shouldReceive('queryComments')
            ->with([], null, 'asc')
            ->once()
            ->andReturn($queryMock);

        $queryMock->shouldReceive('count')
            ->once()
            ->andReturn(10);

        $repositoryMock->shouldReceive('applyPagination')
            ->with($queryMock, 1, 10)
            ->once()
            ->andReturn($queryMock);

        $queryMock->shouldReceive('get')
            ->once()
            ->andReturn(collect([
                (object) ['id' => 1, 'content' => 'mock comment']
            ]));

        
        $dto = new CommentSearchByCriteriaDto();

        $service = new CommentService($repositoryMock);
        $result = $service->getComments($dto);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('count', $result);
        $this->assertEquals(10, $result['count']);
        $this->assertCount(1, $result['result']);
        $this->assertEquals('mock comment', $result['result'][0]->content);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
