<?php

use App\Models\Pdf;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->withHeader('Accept', 'application/json');
});

it('does not create a PDF without "pdf" field', function () {
    $response = $this->post('/api/pdfs', []);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('does not create a PDF with non-file "pdf" field', function () {
    $response = $this->post(
        '/api/pdfs',
        ['pdf' => 'this is not a file']
    );
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('does not create a PDF with a non-PDF file', function () {
    $response = $this->post(
        '/api/pdfs',
        ['pdf' => UploadedFile::fake()->image('not_pdf.png')]
    );
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
});

it('does not show a PDF that does not exist', function () {
    $this->get('/api/pdfs/non-existent-pdf-id')
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

it('shows an existing PDF', function () {
    /** @var Pdf $pdf */
    $pdf = Pdf::factory(['user_id' => Auth::user()->id])->create();

    $this->get('/api/pdfs/' . $pdf->id)
        ->assertOk()
        ->assertJson($pdf->toArray());
});
