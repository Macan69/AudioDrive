<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusSetting;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function index()
    {
        $settings = [
            'points_per_ruble' => BonusSetting::get('points_per_ruble', '1'),
            'ruble_per_point' => BonusSetting::get('ruble_per_point', '1'),
            'max_bonus_percent' => BonusSetting::get('max_bonus_percent', '30'),
            'registration_bonus' => BonusSetting::get('registration_bonus', '500'),
        ];

        return view('admin.bonus.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'points_per_ruble' => 'required|numeric|min:0',
            'ruble_per_point' => 'required|numeric|min:0.01',
            'max_bonus_percent' => 'required|integer|min:0|max:100',
            'registration_bonus' => 'required|integer|min:0',
        ]);

        foreach ($data as $key => $value) {
            BonusSetting::set($key, (string) $value);
        }

        return back()->with('success', 'Настройки бонусной программы сохранены');
    }
}
