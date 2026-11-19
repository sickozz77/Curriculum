<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    // Exibe o formulário de cadastro
    public function create()
    {
        return view('candidate.create');
    }

    // Salva o candidato e todos os seus blocos relacionados (Experiência, Formação e Skills)
    public function storeWeb(Request $request)
    {
        // Validação dos dados principais
        $request->validate([
            'name' => 'required|string|max:150',
            'birth_date' => 'required|date',
            'cpf' => 'required|string|size:11|unique:candidates,cpf',
            'email' => 'required|email|unique:candidates,email',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
        ]);

        // Usa Database Transaction para garantir que só salve se TUDO estiver correto
        DB::transaction(function () use ($request) {
            // 1. Cria o Candidato
            $candidate = Candidate::create($request->only([
                'name', 'birth_date', 'cpf', 'email', 'phone', 'linkedin', 'city', 'state', 'summary'
            ]));

            // 2. Salva as Experiências (se houver)
            if ($request->has('experiences')) {
                foreach ($request->experiences as $exp) {
                    if (!empty($exp['company']) && !empty($exp['role'])) {
                        $candidate->experiences()->create([
                            'company' => $exp['company'],
                            'role' => $exp['role'],
                            'start_date' => $exp['start_date'],
                            'end_date' => $exp['end_date'] ?? null,
                            'is_current' => isset($exp['is_current']) ? 1 : 0,
                            'description' => $exp['description'] ?? null,
                        ]);
                    }
                }
            }

            // 3. Salva a Formação Acadêmica (se houver)
            if ($request->has('education')) {
                foreach ($request->education as $edu) {
                    if (!empty($edu['institution']) && !empty($edu['course'])) {
                        $candidate->education()->create([
                            'institution' => $edu['institution'],
                            'course' => $edu['course'],
                            'degree' => $edu['degree'],
                            'start_date' => $edu['start_date'],
                            'end_date' => $edu['end_date'] ?? null,
                            'status' => $edu['status'],
                        ]);
                    }
                }
            }

            // 4. Salva as Competências (se houver)
            if ($request->has('skills')) {
                foreach ($request->skills as $skill) {
                    if (!empty($skill['name'])) {
                        $candidate->skills()->create([
                            'name' => $skill['name'],
                            'level' => $skill['level'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('candidate.create')->with('success', 'Currículo cadastrado com sucesso!');
    }
}
