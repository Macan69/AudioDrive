<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(20);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.form', ['promotion' => new Promotion]);
    }

    public function store(Request $request)
    {
        $this->save(new Promotion, $request);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция создана');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.form', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $this->save($promotion, $request);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция обновлена');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return back()->with('success', 'Акция удалена');
    }

    private function save(Promotion $promotion, Request $request): void
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:promotions,code,'.$promotion->id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'bonus_points_reward' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data['code'] = $data['code'] ? strtoupper($data['code']) : null;
        $data['auto_apply'] = $request->boolean('auto_apply');
        $data['is_active'] = $request->boolean('is_active');

        $promotion->fill($data);
        $promotion->save();
    }
}
