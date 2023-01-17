<?php

namespace Cgrio\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Hamcrest\Type\IsArray;

class CgrioCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgrio:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cgrio Crud operações crud de vários modelos';

    private string $fornecedor;
    private string $modulo;
    private string $pacote;
    private string $modelo;
    private string $nomeTabela;
    private string $modeloPlural;
    private string $campos;
    private string $camposProtegidos;
    private string $camposExibidos;
    private string $caminhoView;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Bem-vindo a geração de código otimizado CGRIO Crud, Para cancelar pressione Control + C a qualquer momento.');

        while ($this->pacote != "App" and $this->pacote != "Pacote") {
            $this->pacote  = $this->choice(
                'Onde? Digite 1 Para Pacote ou 2 Para Aplicação:',
                ["Pacote" ,"App"],
                "Pacote"
            );
            if ($this->pacote == 1) {
                $this->fornecedor  = $this->ask('Informe o Nome do Fornecedor do Pacote');
                $modulo  = $this->ask('Informe o Nome do Módulo do Pacote');
                $this->modulo = Str::studly($modulo);
            }
        }
        $this->line("Você escolheu " . $this->pacote);

        $usarBancoDeDados = "";
        while ($usarBancoDeDados != "s" and $usarBancoDeDados != "n") {
            $usarBancoDeDados = $this->ask('Usar tabela de Banco de Dados? s para Sim e n para Não');
        }

        if ($usarBancoDeDados == 's') {
            $lote = "";
            while ($lote != "s" and $lote != "n") {
                $lote = $this->ask('Gerar crud para todas as tabelas exisitentes? s para Sim e n para Não');
                if ($lote == "n") {
                    $this->line("Geração em lote ignorada ...");
                } else {
                    $this->geracaoLote();
                    exit();
                }
            }
        }





        $this->modelo = '';
        while ($this->modelo == '') {
            $this->modelo  = $this->ask('Digite o nome do Modelo a ser gerado, Primeiras Letras maiúscula e no singular Ex.: CategoriaProduto');
        }
        $this->modelo =  Str::studly($this->modelo);
        $this->line("Modelo informado" . $this->modelo);
        //define o nome das tabelas com todas as palavras no plural, snake case e caixa baixa

        $this->nomeTabela = $this->converterMinusculoPluralSnake($this->modelo);
        $this->line("O nome da tabela no banco será" . $this->nomeTabela);
        $this->modeloPlural = Str::studly($this->nomeTabela);

        $this->info('Procurando tabela no Banco de Dados -> ' . $this->nomeTabela);

        if ($this->pacote == "App") {
            $this->caminhoView = strtolower(Str::snake($this->modeloPlural));
        } else {
            $this->info('Pacote informado -> ' . $this->pacote);
            $this->pacote = Str::studly($this->fornecedor) . '\\' . $this->modulo;
            $this->fornecedor = Str::studly($this->fornecedor);
            $this->caminhoView = strtolower($this->modulo) . "." . strtolower(Str::snake($this->modeloPlural));
        }

        if (Schema::hasTable($this->nomeTabela)) {
            $this->info("Tabela encontrada");
            $this->obterCamposTabelaBD();
            $confirmação = "";
            while ($confirmação != "s" and $confirmação != "n") {
                $confirmação = $this->ask('Quer gerar o modelo s para sim / n para não');
                if ($confirmação == "n") {
                    $this->line("Ok vamos continuar outra hora então. Aplicação encerrada");
                    exit();
                }
            }


            if ($this->pacote != "App") {
                $this->package();
                $this->line("Não esqueça de registrar o pacote no arquivo composer.json principal");
                $this->line("Registre o arquivo de Providers no arquivo app.config.php seção providers e edite para adicionar os elementos as serem registrados na aplicação");
            }

            $this->view();
            $this->model();
            $this->controller();
            $this->request();
            \File::append(base_path('routes/api.php'), 'Route::resource(\'' . strtolower($this->modeloPlural) . "', '{$this->modelo}Controller');");
        } else {
            $this->info("Tabela não encontrada");
            $migrar = $this->choice("Criar arquivo de migração ?", ['sim', 'não'], 'não');
            if ($migrar == 'sim') {
                if ($this->pacote != "App") {
                    $this->package();
                    $this->line("Não esqueça de registrar o pacote no arquivo composer.json principal");
                    $this->line("Registre o arquivo de Providers no arquivo app.config.php seção providers e edite para adicionar os elementos as serem registrados na aplicação");
                }
                $this->migration();
            }
        }
    }
    private function obterCamposTabelaBD()
    {
        $this->campos = [];

        if (Schema::hasTable($this->nomeTabela)) {
            $esquema = DB::select("describe $this->nomeTabela");
            dump($esquema);
            foreach ($esquema as $e) {
                /* estrutura da variavel campos
        ... => array:5 [
                "nome" => "remember_token"
                "tipo" => "varchar"
                "tamanho" => "100"
                "obrigatorio" => false
                "default" => null
            ] */
                $this->campos[] = [
                    "nome" => $e->Field,
                    "tipo" => strpos($e->Type, "(") > 0 ? substr($e->Type, 0, strpos($e->Type, "(")) : $e->Type,
                    "tamanho" => strpos($e->Type, "(") > 0 ? substr($e->Type, strpos($e->Type, "(") + 1, strpos($e->Type, ")") - strpos($e->Type, "(") - 1) : 0,
                    "obrigatorio" => $e->Null == "NO" ? true : false,
                    "default" => $e->Default,
                    'chave' => $e->Key,
                    'extra' => $e->Extra
                ];
            }
            return true;
        } else {
            return false;
        }
    }

    private function converterMinusculoPluralSnake($palavra)
    {
        //explode cria um array independente de ter ou não a string separadora
        $mod = explode('_', Str::snake($this->modelo));
        for ($i = 0; $i < count($mod); $i++) {
            $mod[$i] = Str::plural($mod[$i]);
        }
        return implode("_", $mod);
    }

    private function converterMaiusculoStudly($palavra)
    {
        //explode cria um array independente de ter ou não a string separadora
        $mod = explode('_', $palavra);
        for ($i = 0; $i < count($mod); $i++) {
            $mod[$i] = ucfirst(Str::singular($mod[$i]));
        }
        return implode("", $mod);
    }




    protected function getStub($type)
    {
        return file_get_contents(base_path("packages/cgrio/crud/src/stubs/$type.stub"));
    }




    protected function model()
    {
        $this->info('Gerando model');

        $campos_protegidos = ['id', "created_at", "updated_at"];
        $this->camposExibidos = array_diff(array_column($this->campos, 'nome'), $campos_protegidos);
        $this->camposProtegidos = array_intersect(array_column($this->campos, 'nome'), $campos_protegidos);
        $caminhoArquivoDestino = '';
        if ($this->pacote == "App") {
            $nameSpace = $this->pacote;
            $caminhoArquivoDestino = 'app/';
        } else {
            $nameSpace = $this->pacote . "\Models";
            $caminhoArquivoDestino = 'packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/Models";
        }

        $validacao = '';

        foreach ($this->campos as $c) {
            $regra = [];

            if ($c['nome'] != 'id') {
                if ($c['obrigatorio'] == 'false') {
                    $regra[] = 'required';
                }
                if (!($c['tamanho'] == '' && $c['tamanho'] == '0')) {
                    $regra[] = 'min:1';
                    $regra[] = 'max:' . $c['tamanho'];
                }

                $validacao .= "\r'" . $c["nome"] . "' => '" . implode('|', $regra) . "',";
            }
        }
        $this->modelTemplate = str_replace(
            [
                "{{ModeloNome}}",
                "{{NameSpace}}",
                "{{NomeTabela}}",
                "{{CamposProtegidos}}",
                "{{CamposPreenchiveis}}",
                '{{Validacao}}',
            ],
            [
                $this->modelo,
                $nameSpace,
                sprintf("'%s'", $this->nomeTabela),
                sprintf("'%s'", implode("','", $this->camposProtegidos)), //adiciona aspas aos valores do array
                sprintf("'%s'", implode("','", $this->camposExibidos)),
                $validacao

            ],
            $this->getStub('Model')
        );
        $this->gravaArquivo($caminhoArquivoDestino . "/{$this->modelo}.php", $this->modelTemplate);
    }




    protected function controller()
    {
        $this->info('Gerando Controller');
        $caminhoArquivoDestino = '';
        $caminhoModel = "";
        if ($this->pacote == "App") {
            $nameSpace = 'App\Http\Controllers';
            $caminhoArquivoDestino = 'app/Http/Controllers';
            $caminhoModel = "\\App\\" . $this->modelo;
        } else {
            $nameSpace = $this->pacote . "\Controllers";
            $caminhoArquivoDestino = 'packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/Controllers";
            $caminhoModel = $this->pacote . "\\Models\\" . $this->modelo;
        }
        $camposCaixaBaixa =  sprintf("'%s'", implode("','", array_column($this->campos, 'nome')));
        $controllerTemplate = str_replace(
            [
                '{{CamposCaixaBaixa}}',
                '{{NameSpace}}',
                '{{ModeloNomeSingularCaixaBaixa}}',
                '{{ModeloNomeSingularCaixaAlta}}',
                '{{ModeloNomePluralCaixaBaixa}}',
                '{{ModeloNomePluralCaixaAlta}}',
                '{{CaminhoModeloNameSpace}}',
                '{{ModuloCaixaBaixa}}',
                '{{ModuloCaixaAlta}}',
                '{{PacoteCaixaBaixa}}',
                '{{CaminhoView}}'
            ],
            [
                $camposCaixaBaixa,
                $nameSpace,
                strtolower($this->modelo),
                $this->modelo,
                strtolower($this->modeloPlural),
                $this->modeloPlural,
                $caminhoModel,
                strtolower($this->modulo),
                $this->modulo,
                strtolower($this->modulo),
                $this->caminhoView
            ],
            $this->getStub('Controller')
        );

        $this->gravaArquivo(base_path($caminhoArquivoDestino . "/" . $this->modelo . "Controller.php"), $controllerTemplate);
        $this->adicionarRota('/' . strtolower($this->modeloPlural), $this->modelo . "Controller");
    }




    protected function adicionarRota($rota, $controller)
    {
        $this->info('Adicionando Rota');
        if ($this->pacote == "App") {
            $caminhoArquivoDestino = base_path('routes/web.php');
            $nameSpaceController = "";
        } else {
            $caminhoArquivoDestino = base_path('packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/routes.php");
            $nameSpaceController = $this->fornecedor . "\\" . $this->modulo . "\\Controllers\\";
        }
        $conteudoRota = file_get_contents($caminhoArquivoDestino);
        if (!(strpos($conteudoRota, $rota))) {
            $conteudoRota .= "\rRoute::resource('" . $rota . "', '" . $nameSpaceController . $controller . "');";
            file_put_contents($caminhoArquivoDestino, $conteudoRota);
        }
    }




    protected function migration()
    {
        $this->info('Gerando migration');
        $caminhoArquivoDestino = '';
        if ($this->pacote == "App") {
            $caminhoArquivoDestino = base_path('database/migrations');
        } else {
            $caminhoArquivoDestino = base_path('packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/migrations");
        }
        $migrationTemplate = str_replace(
            [
                '{{ModeloNomePluralCaixaAlta}}',
                '{{TabelaNome}}',
            ],
            [
                $this->modeloPlural,
                $this->nomeTabela,
            ],
            file_get_contents(base_path("packages/cgrio/crud/src/stubs/migrations/create_model_table.stub"))
        );
        $this->gravaArquivo($caminhoArquivoDestino . "/" . date('Y_m_d_His') . "_create_" . $this->nomeTabela . "_table.php", $migrationTemplate);
    }




    protected function request()
    {
        $this->info('Gerando request');
        $caminhoArquivoDestino = '';
        if ($this->pacote == "App") {
            $nameSpace = "App\Http\Requests";
            $caminhoArquivoDestino = 'app/Http/Requests';
        } else {
            $nameSpace = $this->pacote . "\Requests";
            $caminhoArquivoDestino = 'packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/Requests";
        }
        $requestTemplate = str_replace(
            [
                '{{NameSpace}}',
                '{{ModeloNomeSingularCaixaAlta}}'
            ],
            [
                $nameSpace,
                $this->modelo
            ],
            $this->getStub('Request')
        );
        $this->gravaArquivo(base_path($caminhoArquivoDestino . "/{$this->modelo}Request.php"), $requestTemplate);
    }




    protected function package()
    {
        $this->info('Gerando package');
        $this->info($this->pacote);

        $caminhoArquivoDestino = 'packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo);
        if (file_exists($caminhoArquivoDestino . "/src/")) {
            return;
        }
        //composer.json
        $composerTemplate = str_replace(
            [
                '{{VendorCaixaBaixa}}',
                '{{PackageCaixaBaixa}}',
                '{{PackageCaixaAlta}}'
            ],
            [
                strtolower($this->fornecedor),
                strtolower($this->pacote),
                $this->pacote
            ],
            file_get_contents(base_path("packages/cgrio/crud/src/stubs/packages/composer.stub"))
        );
        $this->info("Adicione a linha abaixo ou seu arquivo commposer.json no diretório raiz, seção registers");
        $this->info("\"" . $this->fornecedor . "\\\\" . $this->modulo . "\\\\\": \"packages/" . strtolower($this->fornecedor) . "/" . strtolower($this->modulo) . "/src/\"");
        if (!file_exists($caminhoArquivoDestino)) {
            mkdir($caminhoArquivoDestino, 0777, true);
        }
        file_put_contents(base_path($caminhoArquivoDestino . "/composer.json"), $composerTemplate);
        //ServiceProvider
        $serviceTemplate = str_replace(
            [
                '{{VendorCaixaAlta}}',
                '{{VendorCaixaBaixa}}',
                '{{PackageCaixaBaixa}}',
                '{{PackageCaixaAlta}}'
            ],
            [
                $this->fornecedor,
                strtolower($this->fornecedor),
                strtolower($this->modulo),
                $this->modulo
            ],
            file_get_contents(base_path("packages/cgrio/crud/src/stubs/packages/ServiceProvider.stub"))
        );
        $this->gravaArquivo(base_path($caminhoArquivoDestino . "/src/" .  $this->modulo . "ServiceProvider.php"), $serviceTemplate);
        //routes.php
        $this->gravaArquivo(base_path($caminhoArquivoDestino . "/src/routes.php"), file_get_contents(base_path("packages/cgrio/crud/src/stubs/packages/routes.stub")));
    }

    protected function view()
    {
        $this->info('Gerando view');
        $caminhoArquivoDestino = '';
        $caminhoView = "";
        if ($this->pacote == "App") {
            $caminhoArquivoDestino = 'resources/views';
            $caminhoView =  strtolower(Str::snake($this->modeloPlural));
        } else {
            $caminhoArquivoDestino = 'packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/views";
            $caminhoView = strtolower($this->modulo) . "." . strtolower(Str::snake($this->modeloPlural));
        }
        $files =  \File::files(base_path("/packages/cgrio/crud/src/stubs/views"));
        foreach ($files as $f) {
            $viewTemplate = str_replace(
                [
                    '{{ModeloNomeSingularCaixaBaixa}}',
                    '{{ModeloNomeSingularCaixaAlta}}',
                    '{{ModeloNomePluralCaixaBaixa}}',
                    '{{ModeloNomePluralCaixaBaixa}}',
                    '{{CamposPesquisa}}',
                    '{{CabecalhoTabela}}',
                    '{{CamposTabela}}',
                    '{{ChavePrimaria}}',
                    '{{CamposFormulario}}',
                    '{{PacoteCaixaBaixa}}',
                    '{{CamposView}}',
                    '{{CaminhoView}}'
                ],
                [
                    strtolower($this->modelo),
                    $this->modelo,
                    strtolower($this->modeloPlural),
                    $this->modeloPlural,
                    $this->gerarCampos('index_tabela_pesquisa'),
                    $this->gerarCampos('index_tabela_cabecalho'),
                    $this->gerarCampos('index_tabela_campos'),
                    'id',
                    $this->gerarCampos('formulario'),
                    strtolower($this->modulo),
                    $this->gerarCampos('view'),
                    $caminhoView
                ],
                file_get_contents($f->getPathName())
            );

            $this->gravaArquivo($caminhoArquivoDestino . "/" . strtolower(Str::snake($this->modeloPlural)) . "/" . Str::before($f->getBasename(), $f->getExtension()) . "php", $viewTemplate);
        }
    }

    protected function gravaArquivo($caminho, $conteudo)
    {
        if (file_exists($caminho)) {
            $confirmação = $this->ask('Arquivo ' . base_path($caminho) . ' já gerado quer gerar backup digite s para sim / n para não');
            if ($confirmação == "s") {
                copy($caminho, $caminho . date('Y_m_d_His') . ".bkp");
            }
        }

        if (!file_exists(pathinfo($caminho)['dirname'])) {
            mkdir(pathinfo($caminho)['dirname'], 0777, true);
        }
        $this->info("Gerado arquivo" . $caminho);
        file_put_contents($caminho, $conteudo);
    }



    protected function gerarCampos($tipo)
    {

        $campos = '';

        foreach ($this->campos as $campo) {
            $arquivo = '';
            $NomeExibicao =  preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $campo['nome']);

            switch ($tipo) {
                case 'index_tabela_pesquisa':
                    $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/index.pesquisa.stub";
                    break;
                case 'index_tabela_cabecalho':
                    $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/index.cabecalho-tabela.stub";
                    break;
                case 'index_tabela_campos':
                    if ($campo['nome'] == 'id' || $campo['nome'] == 'created_at' || $campo['nome'] == 'updated_at') {
                        $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/index.tabela-campo-link.stub";
                    } else {
                        $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/index.tabela-campo.stub";
                    }
                    break;
                case 'view':
                    $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/view.campo.stub";
                    break;
                case 'formulario':
                    if (!($campo['nome'] == 'id' || $campo['nome'] == 'created_at' || $campo['nome'] == 'updated_at')) {
                        if ($campo['tipo'] == 'datetime' || $campo['tipo'] == 'datetime') {
                            $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/formulario.data.stub";
                        } elseif (substr($campo['nome'], -3) == '_id') {
                            $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/formulario.select-modelo.stub";
                        } elseif ($campo['nome'] == 'situacao' || $campo['nome'] == 'tipo') {
                            $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/formulario.select.stub";
                        } else {
                            $arquivo = "/packages/cgrio/crud/src/stubs/views/esqueletos/formulario.texto.stub";
                        }
                    }
                    break;
            }

            if ($arquivo != '') {
                $campos .=  str_replace([
                    '{{ModeloNomeSingularCaixaBaixa}}',
                    '{{ModeloNomeSingularCaixaAlta}}',
                    '{{ModeloNomePluralCaixaBaixa}}',
                    '{{ModeloNomePluralCaixaAlta}}',
                    '{{NomeCampoCaixaBaixa}}',
                    '{{NomeExibicaoCampo}}',
                ], [
                    strtolower($this->modelo),
                    $this->modelo,
                    strtolower($this->modeloPlural),
                    $this->modeloPlural,
                    $campo['nome'],
                    $NomeExibicao
                ], file_get_contents(base_path($arquivo)));
            }
        }
        return $campos;
    }


    private function geracaoLote()
    {

        if ($this->pacote == "App") {
            $caminhoArquivoDestino = base_path('database/migrations');
        } else {
            $caminhoArquivoDestino = base_path('packages/' . strtolower($this->fornecedor) . '/' . strtolower($this->modulo) . "/src/migrations");
        }
        $modelos = $this->obterListaModelosDaMigracao($caminhoArquivoDestino);
        $this->pacote = Str::studly($this->fornecedor) . '\\' . $this->modulo;
        $this->fornecedor = Str::studly($this->fornecedor);
        foreach ($modelos as $modelo) {
            $this->modelo = $modelo;
            $this->nomeTabela = $this->converterMinusculoPluralSnake($this->modelo);
            $this->modeloPlural = Str::studly($this->nomeTabela);
            $this->info('Procurando tabela no Banco de Dados -> ' . $this->nomeTabela);
            if ($this->pacote == "App") {
                $this->caminhoView = strtolower(Str::snake($this->modeloPlural));
            } else {
                $this->caminhoView = strtolower($this->modulo) . "." . strtolower(Str::snake($this->modeloPlural));
            }
            if ($this->obterCamposTabelaBD()) {
                $this->view();
                $this->model();
                $this->controller();
                $this->request();
            }
        }
    }

    private function obterListaModelosDaMigracao($caminho)
    {
        $modelos = [];
        $this->info("Obtendo lista de modelos do diretório" . $caminho);
        if (file_exists($caminho)) {
            $files =  \File::files($caminho);
            foreach ($files as $f) {
                $mod = substr(
                    $f->getPathName(),
                    strpos($f->getPathName(), 'create_', false) + 7,
                    strlen($f->getPathName()) - 10 - strpos($f->getPathName(), 'create_', false) - 7
                );
                ///
                $modelos[] = $this->converterMaiusculoStudly($mod);
            }
        } else {
            $this->info("Não existe o diretório crie primeiro o pacote ...");
            exit();
        }
        return $modelos;
    }

    private function addTextoEmArquivoSeNaoExiste($texto, $arquivo, $aposTexto = null)
    {
        $conteudo_arquivo = file_get_contents($arquivo);
        $novo_conteudo = "";
        if (!(strstr($conteudo_arquivo, $texto) !== false)) {
            if (empty($aposTexto)) {
                $this->info("Não tem texto");
                $novo_conteudo = $conteudo_arquivo . "\r" . $texto;
            } else {
                $this->info("@1");
                $primeira_parte = substr(
                    $conteudo_arquivo,
                    strpos($conteudo_arquivo, $texto, false) + strlen($texto)
                );
                $this->info("@2");
                $segunda_parte = substr(
                    $conteudo_arquivo,
                    strpos($conteudo_arquivo, $texto, false) + strlen($texto)
                );
                $this->info("@3");
                $novo_conteudo = $primeira_parte . $texto . $segunda_parte;
            }

            file_put_contents($arquivo, $novo_conteudo);
        } else {
            $this->info("Texto");
            $this->info($texto);
            $this->info("já existe no arquivo");
            $this->info($arquivo);
        }
    }
}
