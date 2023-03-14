<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\grid;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * To add an ActionColumn to the gridview, add it to the [[GridView::columns|columns]] configuration as follows:
 *
 * ```php
 * 'columns' => [
 *     // ...
 *     [
 *         'class' => ActionColumn::class,
 *         // you may configure additional properties here
 *     ],
 * ]
 * ```
 *
 * For more details and usage information on ActionColumn, see the [guide article on data widgets](guide:output-data-widgets).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActionColumn extends Column
{
    /**
     * {@inheritdoc}
     */
    public $headerOptions = ['class' => 'action-column'];
    /**
     * @var string|null the ID of the controller that should handle the actions specified here.
     * If not set, it will use the currently active controller. This property is mainly used by
     * [[urlCreator]] to create URLs for different actions. The value of this property will be prefixed
     * to each action name to form the route of the action.
     */
    public $controller;
    /**
     * @var string the template used for composing each cell in the action column.
     * Tokens enclosed within curly brackets are treated as controller action IDs (also called *button names*
     * in the context of action column). They will be replaced by the corresponding button rendering callbacks
     * specified in [[buttons]]. For example, the token `{view}` will be replaced by the result of
     * the callback `buttons['view']`. If a callback cannot be found, the token will be replaced with an empty string.
     *
     * As an example, to only have the view, and update button you can add the ActionColumn to your GridView columns as follows:
     *
     * ```php
     * ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],
     * ```
     *
     * @see buttons
     */
    public $template = '{view} {update} {delete}';
    /**
     * @var array button rendering callbacks. The array keys are the button names (without curly brackets),
     * and the values are the corresponding button rendering callbacks. The callbacks should use the following
     * signature:
     *
     * ```php
     * function ($url, $model, $key) {
     *     // return the button HTML code
     * }
     * ```
     *
     * where `$url` is the URL that the column creates for the button, `$model` is the model object
     * being rendered for the current row, and `$key` is the key of the model in the data provider array.
     *
     * You can add further conditions to the button, for example only display it, when the model is
     * editable (here assuming you have a status field that indicates that):
     *
     * ```php
     * [
     *     'update' => function ($url, $model, $key) {
     *         return $model->status === 'editable' ? Html::a('Update', $url) : '';
     *     },
     * ],
     * ```
     */
    public $buttons = [];
    /**
     * @var array button icons. The array keys are the icon names and the values the corresponding html:
     * ```php
     * [
     *     'eye-open' => '<svg ...></svg>',
     *     'pencil' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-pencil'])
     * ]
     * ```
     * Defaults to FontAwesome 5 free svg icons.
     * @since 2.0.42
     * @see https://fontawesome.com
     */
    public $icons = [
        'eye-open' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
        'flag' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-flag-fill" viewBox="0 0 16 16"> <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001"/></svg>',
        'pencil' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>',
        'trash' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>'
    ];
    /** @var array visibility conditions for each button. The array keys are the button names (without curly brackets),
     * and the values are the boolean true/false or the anonymous function. When the button name is not specified in
     * this array it will be shown by default.
     * The callbacks must use the following signature:
     *
     * ```php
     * function ($model, $key, $index) {
     *     return $model->status === 'editable';
     * }
     * ```
     *
     * Or you can pass a boolean value:
     *
     * ```php
     * [
     *     'update' => \Yii::$app->user->can('update'),
     * ],
     * ```
     * @since 2.0.7
     */
    public $visibleButtons = [];
    /**
     * @var callable|null a callback that creates a button URL using the specified model information.
     * The signature of the callback should be the same as that of [[createUrl()]]
     * Since 2.0.10 it can accept additional parameter, which refers to the column instance itself:
     *
     * ```php
     * function (string $action, mixed $model, mixed $key, integer $index, ActionColumn $this) {
     *     //return string;
     * }
     * ```
     *
     * If this property is not set, button URLs will be created using [[createUrl()]].
     */
    public $urlCreator;
    /**
     * @var array html options to be applied to the [[initDefaultButton()|default button]].
     * @since 2.0.4
     */
    public $buttonOptions = [];


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('info', 'eye-open');
        $this->initDefaultButton('update', 'pencil');
        $this->initDefaultButton('status', 'flag', [
            'data-confirm' => Yii::t('yii', 'Ви впевнені, що хочете змінити статус?'),
            'data-method' => 'post',
        ]);
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }

    /**
     * Initializes the default button rendering callback for single button.
     * @param string $name Button name as it's written in template
     * @param string $iconName The part of Bootstrap glyphicon class that makes it unique
     * @param array $additionalOptions Array of additional options
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'info':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'status':
                        $title = Yii::t('yii', 'Status');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon = isset($this->icons[$iconName])
                    ? $this->icons[$iconName]
                    : Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }

    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     * @param string $action the button name (or action ID)
     * @param \yii\db\ActiveRecordInterface $model the data model
     * @param mixed $key the key associated with the data model
     * @param int $index the current row index
     * @return string the created URL
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string) $key];
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

        return Url::toRoute($params);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];

            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                    : $this->visibleButtons[$name];
            } else {
                $isVisible = true;
            }

            if ($isVisible && isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);
                return call_user_func($this->buttons[$name], $url, $model, $key);
            }

            return '';
        }, $this->template);
    }
}
