@extends('admin.layouts.app')

@section('title', 'Gérer les Épreuves')
@section('page-title', 'Gérer les Épreuves du Pack')

@section('breadcrumbs')
    <span> / </span>
    <span><a href="{{ route('admin.exam-packs.index') }}">Packs d'Épreuves</a></span>
    <span> / </span>
    <span><a href="{{ route('admin.exam-packs.edit', $examPack) }}">{{ $examPack->name }}</a></span>
    <span> / </span>
    <span>Gérer les Épreuves</span>
@endsection

@section('content')
<div class="row">
    <!-- Épreuves Actuelles -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Épreuves du Pack ({{ $examPack->examPapers->count() }})</h3>
            </div>
            <div class="card-body">
                @if($examPack->examPapers->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Matière</th>
                                    <th>Niveau</th>
                                    <th>Année</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examPack->examPapers->sortBy('pivot.display_order') as $paper)
                                    <tr>
                                        <td><strong>{{ $paper->title }}</strong></td>
                                        <td>{{ $paper->subject }}</td>
                                        <td>{{ $paper->level_name }}</td>
                                        <td>{{ $paper->year ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('admin.exam-packs.remove-paper', [$examPack, $paper]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Retirer cette épreuve du pack ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    🗑️ Retirer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        Aucune épreuve dans ce pack. Ajoutez-en depuis la liste ci-contre.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Épreuves Disponibles -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajouter une Épreuve</h3>
            </div>
            <div class="card-body">
                @if($availablePapers->count() > 0)
                    <form action="{{ route('admin.exam-packs.add-paper', $examPack) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exam_paper_id">Sélectionner une épreuve</label>
                            <select name="exam_paper_id" id="exam_paper_id" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($availablePapers as $paper)
                                    <option value="{{ $paper->id }}">
                                        {{ $paper->title }} ({{ $paper->subject }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ➕ Ajouter au Pack
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Toutes les épreuves disponibles sont déjà dans ce pack.
                    </div>
                @endif

                <hr>

                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.exam-papers.create') }}" class="btn btn-secondary" style="width: 100%;">
                        📄 Créer Nouvelle Épreuve
                    </a>
                </div>

                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.exam-packs.edit', $examPack) }}" class="btn btn-outline-secondary" style="width: 100%;">
                        ← Retour au Pack
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
