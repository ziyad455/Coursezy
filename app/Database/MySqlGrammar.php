<?php

namespace App\Database;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as BaseMySqlGrammar;

class MySqlGrammar extends BaseMySqlGrammar
{
    /**
     * Compile the query to determine the columns.
     *
     * @param  string  $database
     * @param  string  $table
     * @return string
     */
    public function compileColumns($database, $table)
    {
        return sprintf(
            'select column_name as `name`, data_type as `type_name`, column_type as `type`, '
                .'collation_name as `collation`, is_nullable as `nullable`, '
                .'column_default as `default`, column_comment as `comment`, '
                .'extra as `extra` '
                .'from information_schema.columns where table_schema = %s and table_name = %s '
                .'order by ordinal_position asc',
            $this->quoteString($database),
            $this->quoteString($table)
        );
    }
}
