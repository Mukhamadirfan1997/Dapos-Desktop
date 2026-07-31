<?php

namespace App\Http\Controllers\Dapos;

use App\Http\Controllers\Controller;
use App\Models\Pembelajaran;
use App\Models\Rombel;
use Illuminate\Http\Request;

class PembelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelajaran::with('rombel');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('mata_pelajaran', 'like', "%{$q}%")
                  ->orWhereHas('rombel', fn($r) => $r->where('nama', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('rombel_id')) {
            $query->where('rombel_id', $request->rombel_id);
        }

        $pembelajaran = $query->orderBy('rombel_id')->paginate(20);
        $rombelList = Rombel::orderBy('tingkat')->orderBy('nama')->get();

        return view('dapos.pembelajaran.index', compact('pembelajaran', 'rombelList'));
    }
}
