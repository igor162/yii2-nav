<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 24.03.17
 * Time: 12:46
 */

namespace igor162\nav;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class NavbarUser extends Widget
{

    /**
     * Данные пользователя
     */
    public $userName;

    /**
     * Задать пути к аватарке пользователя
     */
    public $userPatchImg = false;

    /**
     * Задать сообщение
     */
    public $smallShow = false;

    /**
     * Параметры тега <p/> в Header menu
     * @var false|string
     */
    public $dataHeaderName = false;

    /**
     * Параметры тега <small/> в Header menu
     * @var false|string
     */
    public $dataHeaderSmall = false;

    /**
     * Альтернативнае имя пользователя для блока toggle
     * @var false|string
     */
    public $toggleUserName = false;

    /**
     * Показать|Скрыть icon пользователя
     * @var bool true|false
     */
    public $toggleUsedImg = true;

    /**
     * Гендерная принадлежность пользователя
     */
    public $userGender = false;

    const   GR_MALE = 'Male',   // Мужской
            GR_FEMALE = 'Female'; // Женский

    /**
     * Default avatar users
     * @var array
     */
    protected $genderPatchImg =
        [
            self::GR_MALE => '/male.png',
            self::GR_FEMALE => '/female.png',
        ];

    /**
     * [
     *      [ 'label' => $name1, 'link' => $link1, 'class' => $classOptions1 ],
     *      [ 'label' => $name2, 'link' => $link2, 'class' => $classOptions2 ],
     *      [ 'label' => $name3, 'link' => $link3, 'class' => $classOptions3 ]
     * ]
     * @var array
     */
    public $panelBody = false;

    /**
     * [
     *      'labelProfile' => $buttonProfileName, 'linkProfile' => $buttonProfileLink, 'classProfile' => $classOptions1,
     *      'labelSignOut' => $buttonSignOutName, 'linkSignOut' => $buttonSignOutLink, 'classSignOut' => $classOptions2
     * ]
     * @var array
     */
    public $panelFooter = false;

    /**
     * @var string
     */
    protected $menuTemplate = <<< HTML
        <!-- Drop-Down Menu -->
        <ul class="dropdown-menu">
         {panelHeader}
         {panelBody}
         {panelFooter}
        </ul>
HTML;

    /**
     * @var string
     */
    protected $panelHeaderTemplate = <<< HTML
        <!-- Menu Header -->
        <li class="user-header">
         {img}
         <p>{dataUser}</p>
        </li>
HTML;

    /**
     * @var string
     */
    protected $panelBodyTemplate = <<< HTML
        <!-- Menu Body -->
        <li class="user-body">
         {panelBody}
        </li>
HTML;

    /**
     * @var string
     */
    protected $panelFooterTemplate = <<< HTML
        <!-- Menu Footer -->
        <li class="user-footer">
            <div class="pull-left">
                {buttonProfile}
            </div>
            <div class="pull-right">
                {buttonSignOut}
            </div>
        </li>
HTML;


    public function init()
    {
        echo Html::beginTag("ul", ["class" => "nav navbar-nav"]);
        echo Html::beginTag("li", ["class" => "dropdown user user-menu"]);

        echo $this->renderToggleButton();


        echo $this->renderMenuUser();

        parent::init();
    }

    public function run()
    {
        NavAsset::register($this->getView());

        echo Html::endTag("li");
        echo Html::endTag("ul");

        parent::run();

    }

    /**
     * Создание тела кнопки вызова меню пользователя
     * @return string
     */
    protected function renderToggleButton()
    {
        $imgClass = 'user-image';
        $line = Html::beginTag("a", ["href" => "#", "class" => "dropdown-toggle", "data-toggle" => "dropdown", "aria-expanded" => "false"]);

        // Проверка на существование альтернативной ссылки к аватарке пользователя
        if ($this->toggleUsedImg) {
            $line .= $this->renderImgUser($imgClass);
        }

        // Проверка на существование альтернативного имени пользователя
        if($this->toggleUserName){
            $userName = Html::encode($this->toggleUserName);
        }elseif($this->userName){
            $userName = Html::encode($this->userName);
        }else{
            $userName = Html::encode('Nickname');
        }

        // Вывод тэга с блоком уведомления
        if($this->smallShow) {
            $small = $this->smallShow;
            $small = is_array($small) ? $small : ['label' => $small];
            $color = !empty($color = ArrayHelper::getValue($small, 'color')) ? $color : "label-danger";
            $class = !empty($class = ArrayHelper::getValue($small, 'class')) ? $class : "label pull-right";
            $class = $class.' '.$color;
            $line .= Html::tag("small", Html::encode(ArrayHelper::getValue($small, 'label')), ['class' => $class]);
        }

        $line .= Html::tag("span", $userName , ["class" => "hidden-xs"]);
        $line .= Html::endTag("a");

        return $line;
    }

    /**
     * Создание тэга с аватаркой пользователя
     * @return string
     */
    protected function renderImgUser($class)
    {

        $publishedUrl = Yii::$app->assetManager->getPublishedUrl('@igor162/nav/img'); // публичный путь к модулю

        // проверка на существования заданного пути к аватарке пользователя
        if ($this->userPatchImg) {
            $patch = $this->userPatchImg;
        } else {

            $patch = $publishedUrl . ArrayHelper::getValue($this->genderPatchImg, self::GR_MALE);

            if ($this->userGender) {
                $patch = $publishedUrl . ArrayHelper::getValue($this->genderPatchImg, $this->userGender);
            }

        }

        return Html::img(Url::to($patch), ['class' => $class, 'alt' => 'User Image']);

    }

    /**
     * Создание блока меню пользователя
     * @return string
     */
    protected function renderMenuUser()
    {
        $imgClass = 'img-circle';

        // Проверка на существование альтернативного имени пользователя
        if($this->dataHeaderName){
            $dataUser = Html::encode($this->dataHeaderName);
        }elseif($this->userName){
            $dataUser = Html::encode($this->userName);
        }else{
            $dataUser = Html::encode('Nickname');
        }

        // Вывод тэга <small /> с указанным сообщением
        if (!empty($this->dataHeaderSmall)) {
            $dataUser .= Html::tag("small", Html::encode($this->dataHeaderSmall));
        }

        // Вывод блока "panelBody"
        if (!empty($this->panelBody)) {
            $panelBody = $this->renderBody($this->panelBody);
        } else {
            $panelBody = false;
        }

        // Вывод блока "panelFooter"
        if (!empty($this->panelFooter)) {

            $labelProfile = ArrayHelper::getValue($this->panelFooter, 'labelProfile');
            $linkProfile = !empty($linkProfile = ArrayHelper::getValue($this->panelFooter, 'linkProfile')) ? $linkProfile : '#';
            $classProfile = !empty($classProfile = ArrayHelper::getValue($this->panelFooter, 'classProfile')) ? $classProfile : 'btn btn-default btn-flat';


            $labelSignOut = ArrayHelper::getValue($this->panelFooter, 'labelSignOut');
            $linkSignOut = !empty($linkSignOut = ArrayHelper::getValue($this->panelFooter, 'linkSignOut')) ? $linkSignOut : '#';
            $classSignOut = !empty($classSignOut = ArrayHelper::getValue($this->panelFooter, 'classSignOut')) ? $classSignOut : 'btn btn-default btn-flat';

            $buttonProfile = !empty($labelProfile) ? Html::a(Html::encode($labelProfile), $linkProfile, ["class" => $classProfile]) : false;
            $buttonSignOut = !empty($labelSignOut) ? Html::a(Html::encode($labelSignOut), $linkSignOut, ["class" => $classSignOut]) : false;

        } else {
            $buttonProfile = false;
            $buttonSignOut = false;
        }

        $contentHeader = strtr($this->panelHeaderTemplate,
            [
                '{img}' => $this->renderImgUser($imgClass),
                '{dataUser}' => !empty($dataUser) ? $dataUser : null,
            ]);

        $contentBody = strtr($this->panelBodyTemplate, ['{panelBody}' => $panelBody]);

        $contentFooter = empty($buttonProfile) && empty($buttonSignOut) ? null : strtr(
            $this->panelFooterTemplate,
            [
                '{buttonProfile}' => !empty($buttonProfile) ? $buttonProfile : null,
                '{buttonSignOut}' => !empty($buttonSignOut) ? $buttonSignOut : null
            ]
        );

        return strtr(
            $this->menuTemplate,
            [
                '{panelHeader}' => $contentHeader,
                '{panelBody}' => !empty($panelBody) ? $contentBody : null,
                '{panelFooter}' => !empty($contentFooter) ? $contentFooter : null,
            ]
        );

    }

    /**
     * Создание в блоке меню пользователя контейнера "Body"
     * @param $items
     * @return string
     */
    protected function renderBody($items)
    {
        $lines = [];

        $lines[] = Html::beginTag("div", ["class" => "row"]);

        foreach ($items as $i => $item) {
            if ($i > 3) break; // Ограничить до 4 элементов

            $label = ArrayHelper::getValue($item, 'label');
            $link = ArrayHelper::getValue($item, 'link');
            $class = ArrayHelper::getValue($item, 'class');

            $lines[] = Html::tag
            (
                "div",
                Html::a($label, $link),
                ["class" => $class]
            );

        }

        $lines[] = Html::endTag("div");

        return implode("\n", $lines);
    }

}