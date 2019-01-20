<?php
declare(strict_types=1);


namespace Mmal\OpenapiValidator\Tests\OperationFinder;


use Mmal\OpenapiValidator\Operation;
use Mmal\OpenapiValidator\OperationFinder\RequestBasedOperationFinder;
use Mmal\OpenapiValidator\OperationFinder\UnableToFindOperationException;
use PHPUnit\Framework\TestCase;

class RequestBasedOperationFinderTest extends TestCase
{
    public function testShouldLocateByGETMethod()
    {
        $sut = new RequestBasedOperationFinder(
            '/books',
            'GET',
            [
                new Operation(
                    '/books',
                    'POST',
                    'createBook',
                    []
                ),
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                )
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBooks', $operation->getOperationId());
    }

    public function testShouldLocateByPOSTMethod()
    {
        $sut = new RequestBasedOperationFinder(
            '/books',
            'POST',
            [
                new Operation(
                    '/books',
                    'POST',
                    'createBook',
                    []
                ),
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                )
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('createBook', $operation->getOperationId());
    }

    public function testShouldLocateByUrlTemplate()
    {
        $sut = new RequestBasedOperationFinder(
            '/books',
            'GET',
            [
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                ),
                new Operation(
                    '/users',
                    'GET',
                    'getUsers',
                    []
                )
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBooks', $operation->getOperationId());
    }

    public function testShouldLocateByUrlTemplateWithParams()
    {
        $sut = new RequestBasedOperationFinder(
            '/books/123/authors',
            'GET',
            [
                new Operation(
                    '/books/{bookId}/authors',
                    'GET',
                    'getBookAuthors',
                    []
                ),
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                )
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBookAuthors', $operation->getOperationId());
    }


    public function testShouldLocateByUrlTemplateWithManyParams()
    {
        $sut = new RequestBasedOperationFinder(
            '/books/123/authors/777',
            'GET',
            [
                new Operation(
                    '/books/{bookId}/authors',
                    'GET',
                    'getBookAuthors',
                    []
                ),
                new Operation(
                    '/books/{bookId}/authors/{authorId}',
                    'GET',
                    'getBookAuthor',
                    []
                ),
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBookAuthor', $operation->getOperationId());
    }

    public function testShouldLocateByUrlTemplateWithManyParams2()
    {
        $sut = new RequestBasedOperationFinder(
            '/books/123/authors',
            'GET',
            [
                new Operation(
                    '/books/{bookId}/authors',
                    'GET',
                    'getBookAuthors',
                    []
                ),
                new Operation(
                    '/books/{bookId}/authors/{authorId}',
                    'GET',
                    'getBookAuthor',
                    []
                ),
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBookAuthors', $operation->getOperationId());
    }


    public function testShouldIgnoreQueryParameters()
    {
        $sut = new RequestBasedOperationFinder(
            '/books/123/authors',
            'GET',
            [
                new Operation(
                    '/books/{bookId}/authors?foo={foo}&bar={bar}',
                    'GET',
                    'getBookAuthors',
                    []
                ),
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                )
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBookAuthors', $operation->getOperationId());
    }

    public function testShouldIgnoreQueryParametersInRequest()
    {
        $sut = new RequestBasedOperationFinder(
            '/books/123/authors?foo=1&bar=baz',
            'GET',
            [
                new Operation(
                    '/books/{bookId}/authors?foo={foo}&bar={bar}',
                    'GET',
                    'getBookAuthors',
                    []
                ),
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                )
            ]
        );

        $operation = $sut->find();

        $this->assertEquals('getBookAuthors', $operation->getOperationId());
    }


    public function testShouldThrowExceptionOnOperationNotFound()
    {
        $this->expectException(UnableToFindOperationException::class);

        $sut = new RequestBasedOperationFinder(
            '/books/123/authorsss',
            'GET',
            [
                new Operation(
                    '/books/{bookId}/authors?foo={foo}&bar={bar}',
                    'GET',
                    'getBookAuthors',
                    []
                ),
                new Operation(
                    '/books',
                    'GET',
                    'getBooks',
                    []
                )
            ]
        );

        $sut->find();
    }


}