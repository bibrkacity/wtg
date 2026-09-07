<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_reservations_insert');

        DB::unprepared('
            CREATE TRIGGER before_reservations_insert
            BEFORE INSERT ON reservations
            FOR EACH ROW
            BEGIN
                IF (
                    SELECT available_units
                    FROM offers
                    WHERE id = NEW.offer_id
                    LIMIT 1
                ) = 0 THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Cannot create reservation because offer has zero available units";
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_reservations_insert');
    }
};
