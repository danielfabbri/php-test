<?php 

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-contact">
    <div class="product-list">
        <h1 class="d-inline-block mr-2"><?= Html::encode($this->title) ?></h1>
        <button type="button" class="btn btn-primary create-button" data-toggle="modal" data-target="#productModal">
            Create Product
        </button>

        <select id="customer-filter" class="form-control">
            <option value="">Todos os clientes</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer->id ?>"><?= Html::encode($customer->nome) ?></option>
            <?php endforeach; ?>
        </select>
        
        
        <?php
            $reversedProducts = array_reverse($products); // Assuming $products is an array of Product models
            $productsPerPage = 3;
            $totalProducts = count($reversedProducts);
            $totalPages = ceil($totalProducts / $productsPerPage);
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $startIndex = ($currentPage - 1) * $productsPerPage;
            $productsPage = array_slice($reversedProducts, $startIndex, $productsPerPage);
            $reversedProducts = array_reverse($products); // Restoring original order for consistency
            $form = ActiveForm::begin();
            foreach ($productsPage as $product):
        ?>
            <div class="card mb-12">
                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product->nome ?></h5>
                            <p class="card-text">
                                Preço: <?= $product->preco ?><br>
                                Cliente: <a href="customer?id=<?= $product->cliente_id ?>"><?= ($product->cliente_id ? \app\models\Customer::findOne($product->cliente_id)->nome : '') ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;
        ActiveForm::end();
        ?>

        <!-- Pagination controls -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= ($currentPage - 1) ?>">🡸</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i == $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= ($currentPage + 1) ?>">🡺</a>
            <?php endif; ?>
        </div>

        <!-- Modal for creating a new product -->
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Create Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for creating a new product -->
                        <?php $form = ActiveForm::begin(['id' => 'product-form']); ?>
                            <?= $form->field($product, 'nome')->textInput(['autofocus' => true]) ?>
                            <?= $form->field($product, 'preco')->textInput() ?>
                            
                            <?= $form->field($product, 'cliente_id')->dropDownList(
                                \yii\helpers\ArrayHelper::map(\app\models\Customer::find()->all(), 'id', 'nome'),
                                ['prompt' => 'Selecione um cliente']
                            ) ?>
                            <?= $form->field($product, 'foto')->fileInput() ?> <!-- Se a foto é um arquivo, use fileInput() -->
                            <div class="form-group">
                                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'product-button']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>