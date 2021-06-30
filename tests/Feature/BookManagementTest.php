<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  @test
     *  @return void
     */
    public function book_can_be_added()
    {
        $response = $this->post('/books', [
            'isbn' => 9780840700551,
            'title' => 'Holy Bible',
            'author' => 'Lina Žutautė'
        ]);
        $response->assertStatus(200);
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function title_is_required_to_create_book()
    {
        // given
        $bookData = ['isbn' => 9780840700551, 'title' => ''];
        // when
        $response = $this->post('/books', $bookData);
        // then
        $response->assertStatus(302);
        $response->assertSessionHasErrors('title');
        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function isbn_is_required_to_create_book()
    {
        // given
        $bookData = ['isbn' => '', 'title' => ''];
        // when
        $response = $this->post('/books', $bookData);
        // then
        $response->assertStatus(302);
        $response->assertSessionHasErrors('isbn');
        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function author_is_required_to_create_book()
    {
        // given
        $bookData = ['isbn' => '', 'title' => '', 'author' => ''];
        // when
        $response = $this->post('/books', $bookData);
        // then
        $response->assertStatus(302);
        $response->assertSessionHasErrors('author');
        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function book_can_be_updated()
    {
        // given
        // $this->withoutExceptionHandling();
        $bookData = ['title' => 'Holy Bible', 'isbn' => 9780840700551, 'author' => 'Lina Žutautė'];
        $this->post('/books', $bookData);

        // when
        $updatedBookData = ['title' => 'Anything', 'isbn' => 9780840700551, 'author' => 'Lina Žutautytė'];
        $response = $this->put('/books/' . $updatedBookData['isbn'], $updatedBookData);

        // then
        // var_dump(Book::all());
        $response->assertStatus(200);
        $this->assertCount(1, Book::all());
        $this->assertEquals($updatedBookData['isbn'], Book::first()->isbn);
        $this->assertEquals($updatedBookData['title'], Book::first()->title);
    }
}
