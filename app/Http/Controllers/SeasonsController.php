<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\Serie;
use App\Models\WatchedEpisode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeasonsController extends Controller
{
    public function index(int $serieId)
    {
        $serie = Serie::find($serieId);
        $seasons = $serie->seasons;
        return view('seasons.index', compact('seasons', 'serie'));
    }

    public function destroy(int $serieId, int $seasonId)
    {
        DB::beginTransaction();
        $season = Season::find($seasonId);
        $season->destroy($seasonId);
        $serie = Serie::find($serieId);
        $serie->seasons_qt = $season->count();
        $serie->save();
        DB::commit();
        return redirect('/series')->with('success', "Temporada $season->season_number de $serie->serie_name deletada com sucesso.");
    }

    public function updateWatchedEpisodes(Request $request, int $serieId, int $seasonId)
    {
        DB::beginTransaction();
        $season = Season::find($seasonId);
        $watchedEpisodes = $season->watchedEpisodes;
        $episode = WatchedEpisode::find($watchedEpisodes->id);

        if(
            ($request->op == 'minus' and $episode->watched_episodes_qt == 0)
            or
            ($request->op == 'plus' and $episode->watched_episodes_qt == $episode->total_episodes_qt)
        ) {
            return redirect('/series' . '/' . $serieId . '/seasons');
        }
        if ($request->op == 'plus') {
            $episode->watched_episodes_qt +=  1;
        }
        else if($request->op == 'minus'){
            $episode->watched_episodes_qt -=  1;
        }
        $episode->save();
        $season->save();
        DB::commit();
        return redirect('/series' . '/' . $serieId . '/seasons');
    }
}
