<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = "Кар'єра в компанії Скайнікс";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container career">

    <div class="row">
        <div class="col-lg-12">
            <h1>КАР'ЄРА</h1>


        </div>
        <div class="col-lg-12 col-xs-12"><h2>Ми завжди відкриті для нових талантів</h2></div>
        <div class="col-lg-8 col-sm-8 col-xs-12 left-panel">
            <article >
                <div class="txt">
                    <h3>Запрошуємо вас обійняти посаду Продакт Менеджера.</h3>
                    <p>
                        На цій посаді вам переважно доведеться працювати над створенням різноманітних продуктів для системи
                        електронної комерції Мадженто.

                    </p>
                    <h3>Вимоги до кандидата:</h3>
                    <ul>
                        <li>Вища освіта за одним із напрямків: економіка, підприємництво, торгівля, менеджмент;</li>
                        <li>Знання англійської не нижче середнього рівня;</li>
                        <li>Знання сфери цифрової економіки та електронної комерції;</li>
                        <li>Бездоганні письмові та усні комунікаційні навички;</li>
                        <li>Креативність та аналітичне мислення;</li>
                        <li>Вміння працювати у команді;</li>
                    </ul>
                    <h3>Плюсом буде:</h3>
                    <ul>
                        <li>Розмовна англійська;</li>
                        <li>Досвід роботи на схожій посаді;</li>
                        <li>Знання інструментів JIRA, та SCRUM навички;</li>
                    </ul>
                    <h3>Посадові обов’язки:</h3>
                    <ul>
                        <li>Визначення продуктової стратегії та тактичного плану заходів;</li>
                        <li>Збір та пріоритезація вимог до продуктів, створення відповідної документації;</li>
                        <li>Розробка заходів щодо просування продуктів на ринок;</li>
                        <li>Формуваня інформаційно-аналітичної бази даних;</li>
                        <li>Створення, оновлення та підтримка контенту продуктів;</li>
                        <li>Контроль процесу розробки продукту;</li>
                        <li>Ведення перемовин з клієнтами та надання технічної підтримки;</li>

                    </ul>
                </div>
                <div class="shadow-bottom"></div>
                <button class="btn read-more">ДЕТАЛЬНІШЕ &gt; &gt;</button>
            </article>
            <article >
                <div class="txt">
                    <h3>Запрошуємо вас обійняти посаду Фронтенд Розробника.</h3>
                    <p>
                        На цій посаді вам переважно доведеться, за допомогою макетів створених у програмах Adobe Photoshop
                        або Adobe Illustrator, створювати користувацькі інтерфейси, HTML шаблони та теми для Magento,
                        Wordpress, та інших систем.

                    </p>

                    <h3>Вимоги до кандидата:</h3>
                    <ul>
                        <li>Базові знання та досвід роботи з Adobe Photoshop;</li>
                        <li>Гарні знання та досвід роботи з HTML5, CSS3;</li>
                        <li>Знання WEB та HTTP протоколу;</li>
                        <li>Знання англійської не нижче середнього рівня;</li>
                        <li>Креативність;</li>
                        <li>Вміння працювати у команді</li>
                    </ul>

                    <h3>Плюсом буде:</h3>
                    <ul>
                        <li>Знання Twitter Bootstrap;</li>
                        <li>Знання Javascript, jQuery, AngularJS;</li>
                        <li>Досвід роботи на відповідній посаді в ІТ компаніях;</li>
                        <li>Знання інструментів JIRA, та SCRUM навички;</li>
                    </ul>


                    <h3>Посадові обов’язки:</h3>
                    <ul>
                        <li>Трансформація макетів PSD до HTML документів;</li>
                        <li>Створення тем для Magento та Wordpress;</li>
                        <li>Розробка віджетів та додатків за допомогою javascript;</li>
                        <li>Інтеграція різноманітних сервісів: Google API, Facebook API, та інших;</li>

                    </ul>

                </div>
                <div class="shadow-bottom"></div>
                <button class="btn read-more">ДЕТАЛЬНІШЕ &gt; &gt;</button>
            </article>

        </div>
        <div class="col-lg-4 col-sm-4 col-xs-12 right-panel">
            <div class="offer">
                <h3>Компанія пропонує:</h3>
                <ul>
                    <li>Стабільну роботу;</li>
                    <li>Офіційне працевлаштування;</li>
                    <li>Оплачувані відпустка та лікарняні;</li>
                    <li>Заробітну плату прив’язану до курсу долара;</li>
                    <li>Та інші бонуси;</li>
                </ul>
            </div>
            <div class="need">
                <h3>Вам необхідно:</h3>
                <ul>
                    <li>Надіслати резюме;</li>
                    <li>Пройти співбесіду по скайпу або в офісі;</li>
                    <li>Виконати тестове завдання;</li>
                </ul>
            </div>
            <div>
               <p>
                   Офіс компанії знаходиться у м. Київ, район метро Академмістечко
               </p>
            </div>

            <a href="<?=\yii\helpers\Url::to(['site/contact'])?>" class="btn btn-primary apply">НАДІСЛАТИ РЕЗЮМЕ</a>
        </div>
        <div class="col-lg-12 col-xs-12 popup-box">
            <div class="popup-masks-back"></div>
            <div class="popup-career">
                <div class="close"></div>
                <div class="body"></div>
            </div>

        </div>

    </div>
</div>
<?php $this->registerJsFile('/js/career.js'); ?>