<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute必须选择',
    'active_url'           => '无效的:attribute',
    'after'                => ':attribute 必须晚于 :date',
    'alpha'                => ':attribute 只能是英文字母',
    'alpha_dash'           => ':attribute 仅允许包含英文字母、数字和下划线',
    'alpha_num'            => ':attribute 金玉徐包含英文字母和数字',
    'array'                => ':attribute 必须是数组',
    'before'               => ':attribute 必须早于 :date',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 和 :max 之间',
        'file'    => ':attribute 大小必须在 :min Kb 到 :max Kb 之间',
        'string'  => ':attribute 长度必须在 :min 到 :max 之间',
        'array'   => ':attribute 长度必须在 :min 到 :max 直接',
    ],
    'boolean'              => ':attribute 必须选择',
    'confirmed'            => ':attribute 不匹配',
    'date'                 => ':attribute 是无效的日期',
    'date_format'          => ':attribute 格式必须是 :format.',
    'different'            => ':attribute 与 :other 不能相同',
    'digits'               => ':attribute 只能是 :digits 个数字',
    'digits_between'       => ':attribute 必须是  :min 到 :max 个数字',
    'email'                => ':attribute 是无效的电子邮件',
    'filled'               => ':attribute 必须填写',
    'exists'               => ':attribute 包含无效的选项',
    'image'                => ':attribute 必须是图片',
    'in'                   => ':attribute 是无效的选项',
    'integer'              => ':attribute 必须是整数',
    'ip'                   => ':attribute 必须是有效的IP地址',
    'max'                  => [
        'numeric' => ':attribute 不能大于 :max.',
        'file'    => ':attribute 文件大小不能大于 :max KB.',
        'string'  => ':attribute 不能超过 :max 个字符.',
        'array'   => ':attribute 最大长度是 :max.',
    ],
    'mimes'                => ':attribute 的文件类型必须是: :values.',
    'min'                  => [
        'numeric' => ':attribute 不能小于 :min.',
        'file'    => ':attribute 文件大小不能小于 :min KB.',
        'string'  => ':attribute 不能少于 :min 个字符.',
        'array'   => ':attribute 最小长度是 :min .',
    ],
    'not_in'               => ':attribute 选项无效.',
    'numeric'              => ':attribute 必须是数字.',
    'regex'                => ':attribute 格式错误.',
    'required'             => ':attribute 必须填写',
    'required_if'          => '当 :other 是 :value 的时候 :attribute 必须填写.',
    'required_with'        => '当 :values 是当前值（选项）的时候 :attribute 必须填写.',
    'required_with_all'    => '当 :values 是当前值（选项）的时候 :attribute 必须填写.',
    'required_without'     => '当 :values 不当前值（选项）的时候 :attribute 必须填写.',
    'required_without_all' => '当 :values 没有填写的时候 :attribute 必须填写.',
    'same'                 => ':attribute 必须和 :other 一致.',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size.',
        'file'    => ':attribute 必须是 :size KB.',
        'string'  => ':attribute 必须是 :size 个字符.',
        'array'   => ':attribute 长度必须是 :size.',
    ],
    'unique'               => ':attribute 已经被使用.',
    'url'                  => ':attribute 不是有效的URL.',
    'timezone'             => ':attribute 不是有效的时区格式.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
