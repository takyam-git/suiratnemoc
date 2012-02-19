<?php
class Myvalidation
{

    public function _validation_is_category($val)
    {
    	Validation::active()->set_message('unique', 'The field :label must be unique, but :value has already been used');
		
        list($table, $field) = explode('.', $options);

        $result = DB::select("LOWER (\"$field\")")
            ->where($field, '=', Str::lower($val))
            ->from($table)->execute();

        return ! ($result->count() > 0);
    }

}