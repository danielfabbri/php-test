<?php 

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$this->title = 'Customers';
// $this->params['breadcrumbs'][] = $this->title;
$customerId = isset($_GET['id']) ? $_GET['id'] : null;

?>

    <?php if ($customerId !== null) {
        $customer = app\models\Customer::findOne($customerId);

        $this->params['breadcrumbs'][] = [
            'label' => 'Customers', 
            'url' => ['customer']
        ];
        $this->params['breadcrumbs'][] = $customer->nome;
        ?>
        
<div class="site-contact">
        <!-- <div class="card mb-12"> -->
            <!-- <div class="row no-gutters"> -->
                <!-- <div class="col-md-4"> -->
                    <!-- Imagem do cliente -->
                    <!-- <img src="<?= $customer->foto ?>" class="card-img" alt="Foto do cliente"> -->
                <!-- </div> -->
                <div class="col-md-12">
                    <!-- <div class="card-body"> -->
                        <!-- Dados principais do cliente -->
                        <h1 class="card-title"><?= $customer->nome ?></h1>
                        <!-- <a href="#" data-toggle="modal" data-target="#deleteCustomerModal" class="delete-customer right" data-id="<?= $customer->id ?>">
                            <i class="fa fa-trash"></i>
                        </a> -->
                        <p class="card-text">
                            CPF: <?= $customer->cpf ?><br>
                            Gênero: <?= $customer->sexo ?><br>
                            CEP: <?= $customer->cep ?><br>
                            Cidade: <?= $customer->cidade ?><br>
                            Estado: <?= $customer->estado ?><br>
                            Cidade: <?= $customer->cidade ?><br>
                        </p>
                    <!-- </div> -->
                </div>
                <div class="col-md-s12">
                <?php 
                $products = app\models\Product::find()->where(['cliente_id' => $customerId])->all();
                if (!empty($products)) {
                    echo "<h2>Products</h2>";
                    echo "<ul>";
                    foreach ($products as $product) {
                        echo "<li>{$product->nome} - {$product->preco}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Este cliente ainda não tem produtos associados.</p>";
                } ?>
                </div>
            <!-- </div> -->
        </div>

        
    <?php } else {
        
        $this->params['breadcrumbs'][] = $this->title;
        
        ?>
        
<div class="site-contact">
    


        <div class="customer-list">
            <h1 class="d-inline-block mr-2"><?= Html::encode($this->title) ?></h1>
            <button type="button" class="btn btn-primary create-button" data-toggle="modal" data-target="#customerModal">
                Create Customer
            </button>
            <?php
                $reversedCustomers = array_reverse($customers);
                $clientesPorPagina = 3;
                $totalClientes = count($reversedCustomers);
                $totalPaginas = ceil($totalClientes / $clientesPorPagina);
                $paginaAtual = isset($_GET['page']) ? $_GET['page'] : 1;
                $indiceInicial = ($paginaAtual - 1) * $clientesPorPagina;
                $clientesPagina = array_slice($reversedCustomers, $indiceInicial, $clientesPorPagina);
                $reversedCustomers = array_reverse($customers);
                foreach ($clientesPagina as $customer):
            ?>
                <div class="card mb-12">
                    <div class="row no-gutters">
                        <!-- <div class="col-md-4"> -->
                            <!-- Imagem do cliente -->
                            <!-- <img src="<?= $customer->foto ?>" class="card-img" alt="Foto do cliente"> -->
                        <!-- </div> -->
                        <div class="col-md-12">
                            <div class="card-body">
                                <!-- Dados principais do cliente -->
                                <h5 class="card-title"><a href="?id=<?= $customer->id ?>"><?= $customer->nome ?></a></h5>
                                <a href="#" data-toggle="modal" data-target="#deleteCustomerModal" class="delete-customer right" data-id="<?= $customer->id ?>">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <p class="card-text">
                                    CPF: <?= $customer->cpf ?><br>
                                    Cidade: <?= $customer->cidade ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <!-- Controles de navegação da página -->
        <div class="pagination">
            <?php if ($paginaAtual > 1): ?>
                <a href="?page=<?= ($paginaAtual - 1) ?>">🡸</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i == $paginaAtual ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($paginaAtual < $totalPaginas): ?>
                <a href="?page=<?= ($paginaAtual + 1) ?>">🡺</a>
            <?php endif; ?>
        </div>

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCustomerModalLabel">Excluir Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Tem certeza de que deseja excluir este cliente?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form>
                            <input type="hidden" id="customerIdInput" name="id" value="">
                            <button type="submit" class="btn btn-danger"  onclick="deleteCustomer()" >Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-customer').forEach(function(element) {
                    element.addEventListener('click', function() {
                        document.getElementById('customerIdInput').value = this.getAttribute('data-id');
                    });
                });
            });
            function deleteCustomer() {
                var customerId = document.getElementById('customerIdInput').value;
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Obter o token CSRF
                fetch('customer/' + customerId + '/delete', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao excluir cliente');
                    }
                })
                .catch(error => console.error('Erro:', error));
            }
        </script>
        </div>


        <!-- Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerModalLabel">Create Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php
                            $newCustomer = new app\models\Customer();
                            $form = ActiveForm::begin(['id' => 'customer-form']);
                            ?>
                            <?= $form->field($newCustomer, 'nome')->textInput(['autofocus' => true]) ?>
                            <?= $form->field($newCustomer, 'cpf')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'cep')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'logradouro')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'numero')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'cidade')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'estado')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'complemento')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($newCustomer, 'foto')->fileInput() ?>
                            <?= $form->field($newCustomer, 'sexo')->dropDownList(['M' => 'Masculino', 'F' => 'Feminino']) ?>
                            <div class="form-group">
                                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'customer-button']) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>