<?php

namespace App\Http\Controllers;

use App\Http\Requests\PdfRequest;
use App\Models\Pdf;
use Auth;
use Illuminate\Http\JsonResponse;
use Imagick;
use Storage;
use Symfony\Component\HttpFoundation\Response;
use function response;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Pdf::class, 'pdf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PdfRequest $request
     * @return JsonResponse
     */
    public function store(PdfRequest $request): JsonResponse
    {
        // Store the uploaded file
        $path = $request->file('pdf')->store('pdfs');

        // Save new PDF record
        $pdf = Auth::user()->pdfs()->create([
            'path' => $path,
        ]);

        // Return ID
        return response()->json($pdf, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Pdf $pdf
     * @return JsonResponse
     */
    public function show(Pdf $pdf): JsonResponse
    {
        return response()->json($pdf);
    }

    public function getPageAsImage(Pdf $pdf, int $page)
    {
        // TODO
        try {
            $imagick = new Imagick();
            $imagick->readImage(Storage::path($pdf->path) . '[' . $page . ']');
            $imagick->writeImage(Storage::path('outputs/' . $pdf->path));
            $imagick->clear();
            $imagick->destroy();
        } catch (\ImagickException $e) {
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Pdf $pdf
     * @return JsonResponse
     */
    public function destroy(Pdf $pdf): JsonResponse
    {
        $pdf->delete();
        return response()->json(null, 204);
    }
}
