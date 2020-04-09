<?php

namespace App\Traits;

use Illuminate\Validation\Validator;
use Illuminate\Translation\{Translator, ArrayLoader};

trait Validatable
{
    /**
     * @var		array	$rules
     */
    protected $rules = [];

    protected $messages = [
        'required' => 'Не указано поле :attribute',
    ];

    /**
     * Валидирует переданные данные
     *
     * @access	public
     * @param	array	$data	
     * @return	void
     */
    public function validate(array $data)
    {
        $validator = new Validator(
            new Translator(new ArrayLoader('en_US', ''), 'en_US'),
            $data,
            $this->rules,
            $this->messages
        );

        if ($validator->fails()) {
            throw new \InvalidArgumentException();
        }
    }
}
