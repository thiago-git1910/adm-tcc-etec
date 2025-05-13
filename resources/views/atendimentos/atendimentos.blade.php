@extends('layouts.main')

@section('title', 'Gerenciamento de Denúncias')

@section('contentAdmin')

<div class="main p-3">
    <link rel="stylesheet" href="{{ asset('css/atendimento.css') }}">

    <div class="inicio">
        <div class="header mb-4">
            <p>Olá, <span style="color: #ff6347; font-size:30px">{{ $user->name }}</span></p>
        </div>
    </div>

    <div class="title">
        <p class="titleservico">Gerenciamento de Denúncias</p>
    </div>

    <div class="tudo">
        <!-- Menu lateral para as categorias de denúncias -->
        <div class="containerEstadoCli">
            <button class="btn-aberto active" onclick="showSection('emAberto')">
                <i class="fas fa-folder-open"></i> Em Aberto
            </button>
            <button class="btn-andamento" onclick="showSection('emAndamento')">
                <i class="fas fa-tasks"></i> Em Andamento
            </button>
            <button class="btn-concluidas" onclick="showSection('concluidas')">
                <i class="fas fa-check-circle"></i> Concluídas
            </button>
        </div>

        <div class="linha"></div>

        <!-- Seção de denúncias -->
        <div class="denuncias">
            <!-- Tabela de Denúncias em Aberto -->
            <div id="emAberto-section" class="table-section">
                <h4>Denúncias em Aberto</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Contratante</th>
                            <th>Contratado</th>
                            <th>Descrição</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($denunciasEmAberto as $denuncia)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $denuncia->contratante->imagemContratante }}"
                                     alt="Imagem do Contratante"
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            </td>
                            <td>{{ $denuncia->contratante->nomeContratante ?? 'N/A' }}</td>
                            <td>{{ $denuncia->contratado->nomeContratado }}</td>
                            <td>{{ $denuncia->descricao }}</td>
                            <td>{{ $denuncia->created_at->format('d/m/Y') }}</td>
                            <td>
                                <button
                                    class="btn btn-sm btn-success"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAnalise"
                                    onclick="setModalData(

                                        '{{ $denuncia->contratante->nomeContratante ?? 'N/A' }}',
                                        '{{ $denuncia->descricao }}',
                                        '{{ $denuncia->created_at->format('d/m/Y') }}',
                                        '{{ $denuncia->id }}')">
                                    Analisar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">Nenhuma denúncia em aberto.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tabela de Denúncias em Análise -->
            <div id="emAndamento-section" class="table-section" style="display: none;">
                <h4>Denúncias em Análise</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Contratante</th>
                            <th>Contratado</th>
                            <th>Descrição</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($denunciasEmAndamento as $denuncia)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $denuncia->contratante->imagemContratante }}"
                                     alt="Imagem do Contratante"
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            </td>
                            <td>{{ $denuncia->contratante->nomeContratante ?? 'N/A' }}</td>
                            <td>{{ $denuncia->contratado->nomeContratado }}</td>
                            <td>{{ $denuncia->descricao }}</td>
                            <td>{{ $denuncia->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form action="{{ route('atendimento.send', ['id' => $denuncia->id ?? '']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="acao" value="concluido">
                                    <input type="hidden" name="denuncia_id" id="denunciaIdInputConclusao">
                                    <input type="hidden" name="suspender_profissional" id="suspenderProfissionalInput">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Concluir denúncia
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">Nenhuma denúncia em análise.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tabela de Denúncias Concluídas -->
            <div id="concluidas-section" class="table-section" style="display: none;">
                <h4>Denúncias Concluídas</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Contratante</th>
                            <th>Contratado</th>
                            <th>Descrição</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($denunciasConcluidas as $denuncia)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $denuncia->contratante->imagemContratante }}"
                                     alt="Imagem do Contratante"
                                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            </td>
                            <td>{{ $denuncia->contratante->nomeContratante ?? 'N/A' }}</td>
                            <td>{{ $denuncia->contratado->nomeContratado }}</td>
                            <td>{{ $denuncia->descricao }}</td>
                            <td>{{ $denuncia->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Nenhuma denúncia concluída.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAnalise" tabindex="-1" aria-labelledby="modalAnaliseLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Usando modal-lg para largura maior -->
            <div class="modal-content">
                <!-- Cabeçalho do Modal -->
                <div class="modal-header" style="background-color: #1d4ed8; color: white;">
                    <h5 class="modal-title" id="modalAnaliseLabel">Analisar Denúncia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Corpo do Modal -->
                <div class="modal-body">
                    <!-- Informações da denúncia -->
                    <div class="d-flex align-items-start mb-3">
                        <img src="{{ $denuncia->contratante->imagemContratante }}"
                             alt="Imagem do Contratante"
                             style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 10px;">

                        <div>
                            <h6 class="fw-bold" id="contratanteNome">{{ $denuncia->contratante->nomeContratante }}</h6>
                            <p id="denunciaDescricao">{{ $denuncia->descricao }}</p>
                            <p class="text-muted mb-0">
                                Enviada em <span id="denunciaData">{{ $denuncia->created_at->format('d/m/Y H:i') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Campo para o motivo de suspensão -->
                    <label for="motivoSuspensao" class="form-label">Motivo da Suspensão</label>
                    <textarea class="form-control"
                              id="motivoSuspensao"
                              name="motivo"
                              rows="3"
                              placeholder="Digite o motivo da suspensão (opcional)..."></textarea>

                    <!-- Checkbox para suspender profissional -->
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="1" id="suspenderProfissional" name="suspender_profissional">
                        <label class="form-check-label" for="suspenderProfissional">
                            Suspender profissional associado a esta denúncia
                        </label>
                    </div>
                </div>

                <!-- Rodapé do Modal -->
                <div class="modal-footer">
                    <!-- Botão de "Enviar para análise" -->
                    <form action="{{ route('atendimento.send', ['id' => $denuncia->id ?? '']) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="acao" value="emAnalise">
                        <input type="hidden" name="denuncia_id" id="denunciaIdInput">
                        <button type="submit" class="btn btn-sm btn-success">
                            Enviar para análise
                        </button>
                    </form>

                    <!-- Botão de "Concluir denúncia" -->
                  <!-- Botão de "Concluir denúncia" -->
                  <form action="{{ route('denuncia.toggleSuspension', ['id' => $denuncia->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    @if ($denuncia->contratado->is_suspended)
                        <button type="submit" class="btn btn-sm btn-outline-success" title="Ativar Profissional">
                            <i class="fas fa-check-circle"></i> Ativar Profissional
                        </button>
                    @else
                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Suspender Profissional">
                            <i class="fas fa-ban"></i> Suspender Profissional
                        </button>
                    @endif
                </form>


                </div>
            </div>
        </div>
    </div>



<script>
    function showSection(sectionId) {
        document.querySelectorAll('.table-section').forEach(section => {
            section.style.display = 'none';
        });
        document.querySelectorAll('.btn-aberto, .btn-andamento, .btn-concluidas').forEach(btn => {
            btn.classList.remove('active');
        });

        const targetSection = document.getElementById(sectionId + '-section');
        if (targetSection) {
            targetSection.style.display = 'block';
            document.querySelector(`[onclick="showSection('${sectionId}')"]`).classList.add('active');
        }
    }

    document.getElementById('suspenderProfissional').addEventListener('change', function () {
        const suspenderProfissionalInput = document.getElementById('suspenderProfissionalInput');
        suspenderProfissionalInput.value = this.checked ? '1' : '0'; // Define 1 (true) ou 0 (false)
    });


    function setModalData(contratanteNome, denunciaDescricao, denunciaData, idDenuncia) {
    document.getElementById('contratanteNome').textContent = contratanteNome;
    document.getElementById('denunciaDescricao').textContent = denunciaDescricao;
    document.getElementById('denunciaData').textContent = denunciaData;

    // Atualiza os inputs hidden nos formulários
    document.getElementById('denunciaIdInput').value = idDenuncia;
    document.getElementById('denunciaIdInputConclusao').value = idDenuncia;
}




</script>

@endsection
