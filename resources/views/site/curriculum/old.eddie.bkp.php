<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Currículo</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 30px;
        }

        .header {
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 20px;
        }

        .nome {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }

        .titulo {
            font-size: 14px;
            color: #777;
        }

        .contato {
            margin-top: 10px;
            font-size: 12px;
        }

        .section {
            margin-top: 20px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #ccc;
            margin-bottom: 8px;
        }

        .item {
            margin-bottom: 10px;
        }

        .item-title {
            font-weight: bold;
        }

        .item-sub {
            font-size: 12px;
            color: #777;
        }

        .descricao {
            font-size: 13px;
            margin-top: 3px;
        }

    </style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="footer-repetir footer-tela"style="padding-top:30px;">
        J&Bernardo Silva Associados
    </div> 
    <div class="header">
        <div class="nome">{{ $nome ?? 'Seu Nome' }}</div>
        <div class="titulo">{{ $titulo ?? 'Sua Profissão' }}</div>

        <div class="contato">
            📧 {{ $email ?? 'email@email.com' }} |
            📞 {{ $telefone ?? '(00) 00000-0000' }} |
            📍 {{ $cidade ?? 'Sua cidade' }}
        </div>
    </div>

    <!-- RESUMO -->
    <div class="section">
        <div class="section-title">Resumo Profissional</div>
        <div class="descricao">
            {{ $resumo ?? 'Profissional com experiência na área...' }}
        </div>
    </div>

    <!-- EXPERIÊNCIA -->
    <div class="section">
        <div class="section-title">Experiência Profissional</div>

        @foreach($experiencias ?? [] as $exp)
            <div class="item">
                <div class="item-title">{{ $exp['cargo'] }}</div>
                <div class="item-sub">
                    {{ $exp['empresa'] }} | {{ $exp['periodo'] }}
                </div>
                <div class="descricao">
                    {{ $exp['descricao'] }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- FORMAÇÃO -->
    <div class="section">
        <div class="section-title">Formação Acadêmica</div>

        @foreach($formacoes ?? [] as $form)
            <div class="item">
                <div class="item-title">{{ $form['curso'] }}</div>
                <div class="item-sub">
                    {{ $form['instituicao'] }} | {{ $form['periodo'] }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- HABILIDADES -->
    <div class="section">
        <div class="section-title">Habilidades</div>

        <div class="descricao">
            {{ implode(', ', $habilidades ?? ['Comunicação', 'Trabalho em equipe']) }}
        </div>
    </div>

</div>

</body>
</html>