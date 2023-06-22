<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    class CreateSurveyTemplatesTable extends Migration {
        
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::create('survey_templates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('survey_category_Id');
                $table->string('title');
                $table->text('description');
                $table->string('image')->nullable();
                $table->timestamps();
                $table->foreignUuId('survey_id')->references('id')->on('surveys')->onDelete('cascade');
                $table->foreignId('survey_category_id')->constrained('survey_categories')->onDelete('cascade');
            });
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::dropIfExists('survey_templates');
        }
    }