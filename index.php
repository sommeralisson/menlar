<?php

// =============================================================
// Desenvolvido por: Alisson e Diogo
// =============================================================

// -------------------------------------------------------------
// Função pura: validação de valor numérico e positivo
// -------------------------------------------------------------
function fValidarValor($valor) {
  // Retorna true se for numérico e positivo
  return is_numeric($valor) && $valor > 0;
}

// -------------------------------------------------------------
// Função pura: cálculo de desconto (bolsa + desconto familiar)
// Respeita teto máximo de 50%
// -------------------------------------------------------------
function fCalcularDesconto($mensalidade, $bolsa, $desconto_familiar) {
  $total_desconto = $bolsa + $desconto_familiar;
  $desconto_total = min($total_desconto, 0.5); // máximo 50%
  return $mensalidade * $desconto_total;
}

// -------------------------------------------------------------
// Função pura: cálculo da multa (somente se pagamento for após vencimento)
// -------------------------------------------------------------
function fCalcularMultar($data_pagamento, $data_vencimento, $mensalidade) {
  $pag = DateTime::createFromFormat('Y-m-d', $data_pagamento); // <- Formato correto
  $venc = DateTime::createFromFormat('Y-m-d', $data_vencimento); // <- Formato correto

  if ($pag === false || $venc === false) {
    return 0;
  }

  $pag->setTime(0, 0, 0);
  $venc->setTime(0, 0, 0);

  $diff = $venc->diff($pag);
  $diasAtraso = ($pag > $venc) ? $diff->days : 0;

  if ($diasAtraso > 0) {
    $multa = $mensalidade * 0.02; // 2% de multa
    $juros = $mensalidade * 0.001 * $diasAtraso; // 0,1% por dia
    return $multa + $juros;
  } else {
    return 0;
  }
}

// -------------------------------------------------------------
// Função pura: cálculo total de cada aluno
// -------------------------------------------------------------
function fCalcularTotalAluno($aluno) {
  $mensalidade = $aluno['mensalidade_base'];
  $desconto = fCalcularDesconto($mensalidade, $aluno['bolsa'], $aluno['desconto_familiar']);
  $multa = fCalcularMultar($aluno['data_pagamento'], $aluno['data_vencimento'], $mensalidade);
  $total = $mensalidade - $desconto + $multa;

  return [
    'nome' => $aluno['nome'],
    'mensalidade_base' => $mensalidade,
    'desconto_total' => $desconto,
    'multa_total' => $multa,
    'total_a_pagar' => $total
  ];
}

// -------------------------------------------------------------
// Função de ordem superior: array_map
// Aplica o cálculo a todos os alunos
// -------------------------------------------------------------
function fCalcularTurma($alunos) {
  return array_map('fCalcularTotalAluno', $alunos);
}

// -------------------------------------------------------------
// Função de ordem superior: array_reduce
// Calcula o total geral da turma
// -------------------------------------------------------------
function fTotalTurma($resultados) {
  return array_reduce($resultados, function($acc, $aluno) {
    return $acc + $aluno['total_a_pagar'];
  }, 0);
}

// -------------------------------------------------------------
// Função principal: Interface simples (entrada de dados via formulário)
// -------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Recebe os dados do formulário e monta lista de alunos
  $nomes = $_POST['nome'];
  $mensalidades = $_POST['mensalidade_base'];
  $bolsas = $_POST['bolsa'];
  $descontos = $_POST['desconto_familiar'];
  $vencimentos = $_POST['data_vencimento'];
  $pagamentos = $_POST['data_pagamento'];

  $alunos = [];

  for ($i = 0; $i < count($nomes); $i++) {
    if (!fValidarValor($mensalidades[$i])) {
      continue; // validação pura
    }

    $alunos[] = [
      'nome' => $nomes[$i],
      'mensalidade_base' => (float)$mensalidades[$i],
      'bolsa' => (float)$bolsas[$i],
      'desconto_familiar' => (float)$descontos[$i],
      'data_vencimento' => $vencimentos[$i],
      'data_pagamento' => $pagamentos[$i]
    ];
  }

  $resultados = fCalcularTurma($alunos);
  $total_geral = fTotalTurma($resultados);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Mensalidade Escolar</title>

  <link rel="stylesheet" href="./style.css">
</head>
<body>

<h1>Sistema de Mensalidade Escolar</h1>

<form method="post">
  <div class="aluno">
    <h3>Aluno 1</h3>
    <label>Nome: <input type="text" name="nome[]" required></label>
    <label>Mensalidade base (R$): <input type="number" step="0.01" name="mensalidade_base[]" required></label>
    <label>Bolsa (ex: 0.3 = 30%): <input type="number" step="0.01" name="bolsa[]" required></label>
    <label>Desconto Familiar (ex: 0.1 = 10%): <input type="number" step="0.01" name="desconto_familiar[]" required></label>
    <label>Data de Vencimento: <input type="date" name="data_vencimento[]" placeholder="dd/mm/aaaa" required></label>
    <label>Data de Pagamento: <input type="date" name="data_pagamento[]" placeholder="dd/mm/aaaa" required></label>
  </div>

  <div class="aluno">
    <h3>Aluno 2</h3>
    <label>Nome: <input type="text" name="nome[]" required></label>
    <label>Mensalidade base (R$): <input type="number" step="0.01" name="mensalidade_base[]" required></label>
    <label>Bolsa (ex: 0.3 = 30%): <input type="number" step="0.01" name="bolsa[]" required></label>
    <label>Desconto Familiar (ex: 0.1 = 10%): <input type="number" step="0.01" name="desconto_familiar[]" required></label>
    <label>Data de Vencimento: <input type="date" name="data_vencimento[]" placeholder="dd/mm/aaaa" required></label>
    <label>Data de Pagamento: <input type="date" name="data_pagamento[]" placeholder="dd/mm/aaaa" required></label>
  </div>

  <button type="submit">Calcular</button>
</form>

<?php if (!empty($resultados)): ?>
<div class="resultado">
  <h2>Resultados</h2>

  <?php foreach ($resultados as $r): ?>
    <p><strong>Aluno:</strong> <?= htmlspecialchars($r['nome']) ?></p>
    <p>Mensalidade Base: R$ <?= number_format($r['mensalidade_base'], 2, ',', '.') ?></p>
    <p>Desconto Aplicado: R$ <?= number_format($r['desconto_total'], 2, ',', '.') ?></p>
    <p>Multa por Atraso: R$ <?= number_format($r['multa_total'], 2, ',', '.') ?></p>
    <p><strong>Total a Pagar:</strong> R$ <?= number_format($r['total_a_pagar'], 2, ',', '.') ?></p>
    <hr>
  <?php endforeach; ?>

  <h3>Total Geral da Turma: R$ <?= number_format($total_geral, 2, ',', '.') ?></h3>
</div>
<?php endif; ?>

<div class="footer">
  Desenvolvido por: Alisson e Diogo <br>
</div>

</body>
</html>