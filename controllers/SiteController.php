<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Customer;
use app\models\Product;
use yii\web\UploadedFile;


class SiteController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout() {
        return $this->render('about');
    }

    public function actionRegister() {
        $model = new \app\models\User();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Usuário cadastrado com sucesso.');
            return $this->goHome();
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionCustomer()
    {
        $customer = new Customer();
        if (Yii::$app->request->isPost) {
            $customer->load(Yii::$app->request->post());
            $customer->foto = UploadedFile::getInstance($customer, 'foto');
            if ($customer->validate() && $customer->save()) {
                Yii::$app->session->setFlash('success', 'Cliente cadastrado com sucesso.');
                return $this->redirect(['customer']);
            }
        }
        $customers = Customer::find()->all();
        return $this->render('/customer/index', [
            'customer' => $customer,
            'customers' => $customers,
        ]);
    }

    public function actionProduct() {
        $product = new Product();
        if (Yii::$app->request->isPost) {
            $product->load(Yii::$app->request->post());
            $product->foto = UploadedFile::getInstance($product, 'foto');
            if ($product->save()) {
                Yii::$app->session->setFlash('success', 'Produto cadastrado com sucesso.');
                return $this->redirect(['product']);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao cadastrar o produto.');
            }
        }
        $productId = Yii::$app->request->get('id');
        $product = Product::findOne($productId);
        if (!$product) {
            $product = new Product();
        }
        $products = Product::find()->all();
        return $this->render('/product/index', [
            'product' => $product,
            'products' => $products,
        ]);
    }
    
    public function actionProfile() {
        $user = Yii::$app->user->identity;
        return $this->render('profile', [
            'user' => $user,
        ]);
    }
}
