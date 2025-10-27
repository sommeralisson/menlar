# 💰 Sistema de Mensalidade Escolar (PHP)

## Autores
@DiogoHBdev
@sommeralisson

## 🏫 *Tema do Projeto*

*Mensalidade Escolar* com cálculo automático de *bolsas, **descontos familiares* e *multas por atraso*.

O sistema foi criado para demonstrar o uso de *Programação Funcional em PHP, aplicando conceitos de **funções puras, **imutabilidade* e *funções de ordem superior* (array_map, array_reduce, array_filter), conforme solicitado no trabalho.

---

## 🧠 *Objetivo*

O objetivo é calcular o valor final que cada aluno deve pagar pela mensalidade escolar considerando:

- *Bolsa de estudos* (percentual de desconto);
- *Desconto familiar* (acumulativo com a bolsa, até o teto máximo de 50%);
- *Multa e juros* em caso de pagamento após a data de vencimento.

O sistema também mostra o *total geral da turma* e os *descontos aplicados por aluno*.

---

## ⚙️ *Regras de Negócio*

| Regra | Descrição |
|-------|------------|
| *Bolsa por perfil e teto de desconto* | O desconto total (bolsa + familiar) não pode ultrapassar *50%* da mensalidade. |
| *Multa/Juros por atraso* | Multa de *2%* e juros de *0,1% por dia* somente após a data limite de pagamento. |
| *Descontos cumulativos* | Bolsa e desconto familiar se somam, mas respeitam o teto máximo de 50%. |
| *Invariantes* | desconto ≤ mensalidade e multa > 0 apenas se houver atraso. |

---

## 🧩 *Conceitos de Programação Funcional Aplicados*

| Conceito | Implementação |
|-----------|---------------|
| *Funções puras* | fValidarValor, fCalcularDesconto, fCalcularMulta, fCalcularTotalAluno — todas retornam novos valores sem alterar variáveis externas. |
| *Imutabilidade* | Nenhuma função modifica diretamente o estado do programa; todas geram novos valores a partir de entradas. |
| *Funções de ordem superior* | array_map (para calcular os totais dos alunos) e array_reduce (para somar o total da turma). |
| *Validação pura* | fValidarValor() verifica se o valor é numérico e positivo sem alterar dados. |

---

## 🚀 Como Executar o Projeto

**Pré-requisito:** Você precisa ter o **PHP** instalado em sua máquina (versão 7.4 ou superior é recomendada).

O projeto consiste em um único arquivo, `index.php`, que contém toda a lógica PHP (backend) e a estrutura HTML/Formulário (frontend).

### Método 1: Servidor Embutido do PHP (Recomendado)

Esta é a forma mais simples e rápida de rodar o projeto sem precisar de softwares adicionais.

1.  Abra seu terminal (Prompt de Comando, PowerShell, Terminal, etc.).
2.  Navegue até a pasta onde o arquivo `index.php` está localizado.
3.  Execute o seguinte comando:
    ```bash
    php -S localhost:8000
    ```
4.  Abra seu navegador de internet e acesse: **`http://localhost:8000`**

### Método 2: Usando XAMPP (ou WAMP, MAMP)

Se você já utiliza um ambiente de servidor local como o XAMPP, siga estes passos:

1.  Certifique-se de que o XAMPP está instalado e o serviço **Apache** está em execução (running).
2.  Copie o arquivo `index.php` para dentro da pasta `htdocs` do seu XAMPP.
    * *Localização comum no Windows:* `C:\xampp\htdocs\`
3.  Se você quiser, pode criar uma pasta para o projeto (ex: `C:\xampp\htdocs\mensalidade\`).
4.  Abra seu navegador e acesse o endereço correspondente:
    * Se colocou na raiz: **`http://localhost/index.php`**
    * Se criou uma pasta `mensalidade`: **`http://localhost/mensalidade/`**
