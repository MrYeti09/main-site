@extends('voyager::master')

@section('content')
    <div class="panel panel-bordered panel-primary">
        <div class="panel-heading" style="">
            <h3 class="panel-title">Documentação</h3>
            <div class="panel-actions">
                <a class="panel-collapse-icon voyager-angle-down" data-toggle="block-collapse"
                   aria-hidden="true"></a>
            </div> <!-- /.panel-actions -->
        </div> <!-- /.panel-heading -->
        <div class="panel-body">
            <h3>Page-block</h3>

            <h4>Configuração</h4>

            <pre style="border: none;padding: 0px;background: transparent;">
    <code class="php" style="padding:20px;line-height: 0.6;">$blocks['slug'] = [<br>
'name' => 'string', //Nome do bloco<br>
'color' => 'hex', //Cor do painel do bloco<br>
'description' => 'string', //Descrição<br>
'version' => 'string', //Versão<br>
'template' => 'string', //View principal do bloco<br>
'type' => 'header/footer/blog/empty', //Tipo do bloco<br>
'tabs' => [ //Array de abas<br>
    'integer' => [<br>
        'name' => 'string' //Nome da aba<br>
        ]<br>
],<br>
'group' => 'string',//Qual categoria se encaixa na seleção<br>
'fields' => [ //Array dos campos que serão usados para o bloco<br>
'string' => [<br>
    'field' => "string",//Necessário ser o mesmo nome da key<br>
    'display_name' => "string",//Nome de exibição na parte visual<br>
    'partial' => 'string',//View que será exibido<br>
    'required' => 'string' ou 'integer',//Campo obrigatório?<br>
    'width' => 'string', //Largura visual com as normas do bootstrap<br>
    'tab' => 'string' ou 'integer', //ID da aba que irá incluir o campo<br>
    'complex' => [ //Usado para criar todos os campos de texto, como tamanho e cor<br>
        'varname' => 'string', //Ex. "title" ira gerar "title_color,title_size"<br>
        'item' => 'boolean', //caso true, ira gerar, itemTitle[ID]<br>
        'display_var' => 'string' //Nome do campo que exibe visualmente exemplo para "Titulo" irá exibir "Tamanho do Titulo"<br>
        ],
    'child' => [ //Usado para criar mais campos dentro do form especifico<br>
        'field' => 'field'<br>
        ],<br>
]</code></pre>
        </div> <!-- /.panel-body -->
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/styles/arta.min.css"
          integrity="sha256-4G2VvzzvowJkpXN4h1DinKDktGpWYfuRKFDvBprTTzA=" crossorigin="anonymous"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
@endsection