<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Volume;

class VolumeController extends Controller
{

    public function index()
    {
        return csrf_token();
    }

    public function calculateAllVolume()
    {
        $user = auth('sanctum')->user();

        $search = Volume::where('user_id', $user->id)->get();

        if ($search->isEmpty()) {
            return response()->json(['message' => 'Nenhum volume encontrado, adicione exercícios para calcular.'], 404);
        }

        foreach ($search as $volume)
            if (Gate::denies('allvolume', $volume)) {
                return response()->json(['message' => 'Não autorizado!'], 403);
            }

        $series = [];

        foreach ($search as $exercise) {

            if ($exercise->musculo) {
                if (!isset($series[$exercise->musculo])) {
                    $series[$exercise->musculo] = 0;
                }

                $series[$exercise->musculo] += $exercise->series;
            }
        }

        $residualSeries = [];

        foreach ($search as $exercise) {

            if ($exercise->residual) {
                if (!isset($residualSeries[$exercise->residual])) {
                    $residualSeries[$exercise->residual] = 0;
                }

                $residualSeries[$exercise->residual] += $exercise->series * 0.5;
            }
        }

        foreach ($series as $muscle => $count) {
            $series[$muscle] = $count . " séries semanais";
        }

        foreach ($residualSeries as $muscle => $count) {
            $residualSeries[$muscle] = $count . " séries semanais";
        }

        if ($residualSeries == null) {
            $residualSeries = 'Não há volume residual.';
        }

        $response = [
            'Volume semanal por grupo muscular' => $series,
            'Volume semanal residual por grupo muscular' => $residualSeries
        ];

        return response()->json([$response], 200);
    }

    public function calculateOneVolume($musculo)
    {
        $user = auth('sanctum')->user();

        $search = Volume::where('musculo', $musculo)
            ->where('user_id', $user->id)
            ->get();

        if ($search->isEmpty()) {
            return response()->json(['message' => 'Nenhum volume encontrado, adicione exercícios para calcular.'], 404);
        }

        foreach ($search as $volume)
            if (Gate::denies('onevolume', $volume)) {
                return response()->json(['message' => 'Não autorizado!'], 403);
            }

        $exercicios_musculo = $search->pluck('exercicio')->toArray();

        $primarySeries = $search->sum('series');

        $residualSeries = [];

        foreach ($search as $exercise) {

            if ($exercise->residual) {
                if (!isset($residualSeries[$exercise->residual])) {
                    $residualSeries[$exercise->residual] = 0;
                }

                $residualSeries[$exercise->residual] += $exercise->series * 0.5;
            }
        }

        $residualMessages = [];
        foreach ($residualSeries as $residual => $totalSeries) {
            $residualMessages[] = 'O volume residual para o músculo ' . ucfirst($residual) . ' é de: ' . $totalSeries . ' séries semanais.';
        }

        $response = 'O volume atual para o músculo ' . ucfirst($musculo) . ' é de: ' . $primarySeries . ' séries semanais. ' .
            implode(' ', $residualMessages) .
            ' Exercícios no planejamento: ' . implode(', ', $exercicios_musculo) . '.';

        return response()->json(['message' => $response], 200);
    }

    public function show()
    {
        $user = auth('sanctum')->user();

        $search = Volume::where('user_id', $user->id)->get();

        return response()->json($search);
    }

    public function store(Request $request)
    {

        $volume = new Volume;

        $user = auth('sanctum')->user();

        $volume->exercicio = ucwords(strtolower($request->exercicio));
        $volume->musculo = ucwords(strtolower($request->musculo));
        $volume->residual = ucwords(strtolower($request->residual));
        $volume->series = $request->series;
        $volume->data = now();
        $volume->user_id = $user->id;

        if (Gate::denies('create', $volume)) {
            return response()->json(['message' => 'Não autorizado!'], 403);
        }

        $muscleCorrections = [
            'Biceps' => 'Bíceps',
            'Triceps' => 'Tríceps',
            'Quadriceps' => 'Quadríceps',
            'Gluteo' => 'Glúteos',
            'Gluteos' => 'Glúteos'
        ];

        if (array_key_exists($volume->musculo, $muscleCorrections)) {
            $volume->musculo = $muscleCorrections[$volume->musculo];
        }

        $volume->save();

        return response()->json(['message' => 'Exercício criado!'], 200);
    }

    public function update(Request $request, $id)
    {

        $user = auth('sanctum')->user();

        $novo_exercicio = $request->all();

        $search = Volume::where('id', $id)->first();

        if (is_null($search)) {
            return response()->json(['message' => 'ID não encontrado.'], 404);
        }

        if (Gate::denies('update', $search)) {
            return response()->json(['message' => 'Não autorizado!'], 403);
        }

        $search->update($novo_exercicio);

        return response()->json(['message' => 'Exercício atualizado!'], 200);
    }

    public function destroy($id)
    {
        $user = auth('sanctum')->user();

        $search = Volume::where('id', $id)->first();

        if (is_null($search)) {
            return response()->json(['message' => 'ID não encontrado.'], 404);
        }

        if (Gate::denies('delete', $search)) {
            return response()->json(['message' => 'Não autorizado!'], 403);
        }

        $search->delete();

        return response()->json(['message' => 'Exercício deletado!'], 200);
    }

    public function getCurrentUser()
    {

        $user = auth('sanctum')->user();

        return response()->json($user);
    }
}
