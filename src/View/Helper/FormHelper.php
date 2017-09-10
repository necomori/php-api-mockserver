<?php
/**
 * Copyright (c) necomori LLC (http://necomori.asia)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  Copyright (c) necomori LLC (http://necomori.asia)
 * @since      0.1.0
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\View\View;

class FormHelper extends CakeFormHelper
{
    protected $errorClass = 'form-control form-error';

    protected $templates = [
        'button' => '{{before}}<button{{attrs}}>{{text}}</button>{{after}}',
        'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
        'checkboxFormGroup' => '{{label}}',
        'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
        'dateWidget' => '<div class="form-group">{{label}} {{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}</div>',
        'error' => '<span class="help-block">{{content}}</span>',
        'errorList' => '<ul>{{content}}</ul>',
        'errorItem' => '<li>{{text}}</li>',
        'file' => '<input type="file" name="{{name}}"{{attrs}}>',
        'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
        'formStart' => '<form{{attrs}}>',
        'formEnd' => '</form>',
        'formGroup' => '{{label}}<div class="col-sm-10">{{input}}{{error}}</div>',
        'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
        'input' => '<input type="{{type}}" name="{{name}}"{{attrs}} class="form-control" />',
        'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
        'inputContainer' => '<div class="form-group input {{type}}{{required}}">{{content}}</div>',
        'inputContainerError' => '<div class="form-group input {{type}}{{required}} has-error">{{content}}</div>',
        'label' => '<label{{attrs}} class="col-sm-2 control-label">{{text}}</label>',
        'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
        'legend' => '<legend>{{text}}</legend>',
        'multicheckboxTitle' => '<legend>{{text}}</legend>',
        'multicheckboxWrapper' => '<fieldset{{attrs}}>{{content}}</fieldset>',
        'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
        'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
        'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
        'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
        'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
        'radioWrapper' => '<div class="radio">{{label}}</div>',
        'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
        'submitContainer' => '<div class="box-footer {{required}}">{{content}}</div>',
    ];

    /**
     * FormHelper constructor.
     *
     * @param View $View
     * @param array $config
     */
    public function __construct(View $View, array $config = [])
    {
        $this->_defaultConfig['errorClass'] = $this->errorClass;
        $this->_defaultConfig['templates'] = array_merge($this->_defaultConfig['templates'], $this->templates);
        parent::__construct($View, $config);
    }

    /**
     * @param null $context
     * @param array $options
     * @return string
     */
    public function create($context = null, array $options = [])
    {
        $options += ['role' => 'form'];

        return parent::create($context, $options);
    }

    /**
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    public function textarea($fieldName, array $options = [])
    {
        $options += ['class' => 'form-control'];

        return parent::textarea($fieldName, $options);
    }

    /**
     * @param string $title
     * @param array $options
     * @return string
     */
    public function button($title, array $options = [])
    {
        $options += ['escape' => false, 'secure' => false, 'class' => 'btn btn-success'];
        $options['text'] = $title;

        return $this->widget('button', $options);
    }

    /**
     * @param null $caption
     * @param array $options
     * @return string
     */
    public function submit($caption = null, array $options = [])
    {
        $options += ['class' => 'btn btn-success'];

        return parent::submit($caption, $options);
    }

    /**
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    public function input($fieldName, array $options = [])
    {
        $_options = [];

        if (!isset($options['type'])) {
            $options['type'] = $this->_inputType($fieldName, $options);
        }

        switch ($options['type']) {
            case 'checkbox':
            case 'radio':
            case 'date':
                break;
            default:
                $_options = ['class' => 'form-control'];
                break;
        }

        $options += $_options;

        return parent::input($fieldName, $options);
    }
}
