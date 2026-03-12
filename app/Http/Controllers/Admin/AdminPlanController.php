<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = InvestmentPlan::withCount('investments')->orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.form', ['plan' => new InvestmentPlan()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:100',
            'description'        => 'nullable|string',
            'roi_percent'        => 'required|numeric|min:1|max:100',
            'duration_months'    => 'required|integer|min:1',
            'min_amount'         => 'required|numeric|min:100',
            'max_amount'         => 'nullable|numeric',
            'commission_percent' => 'required|numeric|min:1|max:50',
            'sort_order'         => 'integer|min:0',
        ]);

        InvestmentPlan::create($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created!');
    }

    public function edit(InvestmentPlan $plan)
    {
        return view('admin.plans.form', compact('plan'));
    }

    public function update(Request $request, InvestmentPlan $plan)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:100',
            'description'        => 'nullable|string',
            'roi_percent'        => 'required|numeric|min:1|max:100',
            'duration_months'    => 'required|integer|min:1',
            'min_amount'         => 'required|numeric|min:100',
            'max_amount'         => 'nullable|numeric',
            'commission_percent' => 'required|numeric|min:1|max:50',
            'is_active'          => 'boolean',
            'sort_order'         => 'integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $plan->update($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated!');
    }

    public function toggleStatus(InvestmentPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return back()->with('success', 'Plan status updated.');
    }
}
