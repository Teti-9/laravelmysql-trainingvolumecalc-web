<?php

namespace App\Http\Controllers;

use MongoDB\BSON\ObjectId;

use App\Models\Volume;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VolumeController extends Controller
{

    public function calculateAllVolume()
    {
        $user = auth('sanctum')->user();

        $search = Volume::where('user_id', $user->id)->get();

        if ($search->isEmpty()) {
            return response()->json(['message' => 'Nenhum volume encontrado, adicione exercícios para calcular.'], 404);
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

        $search = Volume::where('musculo', ucwords(strtolower($musculo)))
            ->where('user_id', $user->id)
            ->get();

        if ($search->isEmpty()) {
            return response()->json(['message' => 'Nenhum volume encontrado, adicione exercícios para calcular.'], 404);
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

    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        try {
            $request->validate([
                'exercicio' => 'required|string|max:255',
                'musculo' => 'required|string|max:255',
                'residual' => 'nullable|string|max:255',
                'series' => 'required|integer|min:1',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Requisição inválida!'], 404);
        }

        $volume = new Volume;

        $volume->exercicio = ucwords(strtolower($request->exercicio));
        $volume->musculo = ucwords(strtolower($request->musculo));
        $volume->residual = ucwords(strtolower($request->residual));
        $volume->series = $request->series;
        $volume->data = now();
        $volume->user_id = $user->id;

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

        if (is_null($user)) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        try {
            $request->validate([
                'exercicio' => 'nullable|string|max:255',
                'musculo' => 'nullable|string|max:255',
                'residual' => 'nullable|string|max:255',
                'series' => 'nullable|integer|min:1',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Requisição inválida!'], 404);
        }

        $novo_exercicio = $request->all();

        // $search = Volume::where('id', $id)
        $search = Volume::where('_id', new ObjectId($id))
            ->where('user_id', $user->id)
            ->first();

        if (is_null($search)) {
            return response()->json(['message' => 'ID não encontrado.'], 404);
        }

        $search->update($novo_exercicio);

        return response()->json(['message' => 'Exercício atualizado!'], 200);
    }

    public function destroy($id)
    {

        $user = auth('sanctum')->user();

        if (is_null($user)) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        // $search = Volume::where('id', $id)
        $search = Volume::where('_id', new ObjectId($id))
            ->where('user_id', $user->id)
            ->first();

        if (is_null($search)) {
            return response()->json(['message' => 'ID não encontrado.'], 404);
        }

        $search->delete();

        return response()->json(['message' => 'Exercício deletado!'], 200);
    }
}
