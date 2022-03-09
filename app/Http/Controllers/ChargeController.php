<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\RecurringExpense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param RecurringExpense $recurringExpense
     * @return JsonResponse
     */
    public function index(RecurringExpense $recurringExpense): JsonResponse
    {
        return response()->json($recurringExpense->charges());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param RecurringExpense $recurringExpense
     * @return JsonResponse
     */
    public function store(Request $request, RecurringExpense $recurringExpense): JsonResponse
    {
        return response()->json($recurringExpense->charges()->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param RecurringExpense $recurringExpense
     * @param Charge $charge
     * @return JsonResponse
     */
    public function show(RecurringExpense $recurringExpense, Charge $charge): JsonResponse
    {
        return response()->json($charge);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param RecurringExpense $recurringExpense
     * @param Charge $charge
     * @return JsonResponse
     */
    public function update(Request $request, RecurringExpense $recurringExpense, Charge $charge): JsonResponse
    {
        $charge->update($request->all());
        return response()->json($charge);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RecurringExpense $recurringExpense
     * @param Charge $charge
     * @return JsonResponse
     */
    public function destroy(RecurringExpense $recurringExpense, Charge $charge): JsonResponse
    {
        $charge->delete();
        return response()->json(null, 204);
    }
}
