@extends('layouts.app')

@section('content')
    <h2>Novo Compromisso</h2>
    <form action="{{ route('agenda.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Pessoa</label>
            <select name="pessoa_id" class="form-control">
                @foreach ($pessoas as $p)
                    <option value="{{ $p->id }}">{{ $p->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Data e Hora</label>
            <input type="datetime-local" name="data_hora" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Local</label>
            <input type="text" name="local" class="form-control">
        </div>
        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>
        <button class="btn btn-success">Salvar</button>
    </form>
@endsection