<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hosp_id', 30)->nullable();
            $table->string('uuid', 36)->comment('流水號');
            $table->string('medical_number', 30)->nullable()->comment('病歷號碼');
            $table->string('name', 50)->nullable()->comment('姓名');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('pid', 30)->nullable()->comment('身分證字號');
            $table->tinyInteger('sex')->nullable()->comment('性別');
            $table->string('tel1', 20)->nullable()->comment('住家電話1');
            $table->string('tel2', 20)->nullable()->comment('住家電話2');
            $table->string('company_tel', 20)->nullable()->comment('公司電話');
            $table->string('mobile1', 20)->nullable()->comment('行動電話1');
            $table->string('mobile2', 20)->nullable()->comment('行動電話2');
            $table->string('emergency_contact', 50)->nullable()->comment('緊急聯絡人');
            $table->tinyInteger('emergency_relationship')->nullable()->comment('緊急聯絡人關係');
            $table->string('emergency_relationship_other', 30)->nullable()->comment('緊急聯絡人關係其他');
            $table->string('emergency_tel', 20)->nullable()->comment('緊急聯絡人住家電話');
            $table->string('emergency_mobile', 20)->nullable()->comment('警急聯絡人行動電話');
            $table->char('language', 7)->nullable()->comment('語言');
            $table->string('language_other', 30)->nullable()->comment('語言其他');
            $table->char('drug_allergy', 7)->nullable()->comment('藥物過敏史');
            $table->string('drug_allergy_insulin', 30)->nullable()->comment('藥物過敏史胰島素');
            $table->string('drug_allergy_other', 30)->nullable()->comment('藥物過敏史其他');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('basis');
    }
}
