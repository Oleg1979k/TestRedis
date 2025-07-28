<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use yii\web\BadRequestHttpException;
use Endroid\QrCode\Builder\Builder;
use app\models\History;
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post']
                ],

            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
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

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    private function checkUrl(string $url): bool {
        // Проверяем валидность URL по синтаксису
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false; // Неверный формат URL
        }

        // Проверяем доступность сайта (HTTP статус 200-399)
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpCode >= 200 && $httpCode < 400);
    }
    public function actionGenerate($url)
    {

        if ($this->checkUrl($url)) {
            echo "URL валидный и сайт доступен";
        } else {
            echo "URL невалидный или сайт недоступен";
        }

        $qrCode = \Endroid\QrCode\QrCode::create($url)
            ->setEncoding(new \Endroid\QrCode\Encoding\Encoding('UTF-8'))
            ->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High)
            ->setSize(250)
            ->setMargin(10);

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);

        return [
            'success' => true,
            'dataUrl' => 'data:image/png;base64,' . base64_encode($result->getString()),
        ];
    }
    public function actionAjaxForm()
    {
        $model = new \app\models\AjaxForm();
        return $this->render('ajax-form', ['model' => $model]);
    }

    public function actionAjaxFormSubmit()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new \app\models\AjaxForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if (!$this->checkUrl($model->url)) {
                $model->addError( 'url', 'URL is not valid');


                return [
                    'success' => false,
                    'message' => 'Ошибка валидации.',
                    'errors' => $model->getErrors(),
                ];

            }
            $url = $model->url;
            $qrCode = QrCode::create($model->url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
                ->setSize(250)
                ->setMargin(10);
 $model = new History();
    $model->name = $url;
    $model->ip = Yii::$app->request->userIP;
    $model->save();
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            return [
                'success' => true,
                'dataUrl' => 'data:image/png;base64,' . base64_encode($result->getString()),
            ];
        }

    }


}
