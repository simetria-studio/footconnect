@extends('admin.layout')

@section('title', 'Banners')
@section('page-title', 'Banners da home')
@section('page-subtitle', 'Destaques e anúncios exibidos no app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="small fc-text-secondary mb-0">Cadastre destaques e anúncios para a landing pública do site.</p>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-success btn-sm">Novo banner</a>
</div>

<div class="card fc-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Banner</th>
                    <th>Público</th>
                    <th>Ordem</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr class="{{ ! $banner->is_active ? 'opacity-50' : '' }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($banner->image_url)
                                    <img src="{{ $banner->image_url }}" alt="" class="rounded" style="width: 72px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-secondary d-flex align-items-center justify-content-center" style="width: 72px; height: 40px; font-size: 0.7rem;">Sem img</div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $banner->title }}</div>
                                    @if($banner->subtitle)
                                        <div class="small fc-text-secondary">{{ $banner->subtitle }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $banner->audience_label }}</td>
                        <td class="small">{{ $banner->sort_order }}</td>
                        <td>
                            @if($banner->is_active)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form method="POST" action="{{ route('admin.banners.toggle', $banner) }}">@csrf
                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ $banner->is_active ? 'Desativar' : 'Ativar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Remover este banner?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 fc-text-secondary">Nenhum banner cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($banners->hasPages())
        <div class="p-3">{{ $banners->links() }}</div>
    @endif
</div>
@endsection
