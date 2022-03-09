<?php

namespace App\Http\Controllers;

use App\Models\RecurringExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecurringExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(RecurringExpense::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $recurringExpense = RecurringExpense::create($request->all());
        return response()->json($recurringExpense, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param RecurringExpense $recurringExpense
     * @return JsonResponse
     */
    public function show(RecurringExpense $recurringExpense): JsonResponse
    {
        return response()->json($recurringExpense);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param RecurringExpense $recurringExpense
     * @return JsonResponse
     */
    public function update(Request $request, RecurringExpense $recurringExpense): JsonResponse
    {
        $recurringExpense->update($request->all());
        return response()->json($recurringExpense);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RecurringExpense $recurringExpense
     * @return JsonResponse
     */
    public function destroy(RecurringExpense $recurringExpense): JsonResponse
    {
        $recurringExpense->delete();
        return response()->json(null, 204);
    }
}
