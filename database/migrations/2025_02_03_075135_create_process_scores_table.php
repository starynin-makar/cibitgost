use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessScoresTable extends Migration
{
    public function up()
    {
        Schema::create('process_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->string('process_name');
            $table->string('technical_score')->nullable();
            $table->string('planning_score')->nullable();
            $table->string('implementation_score')->nullable();
            $table->string('control_score')->nullable();
            $table->string('improvement_score')->nullable();
            $table->string('qualitative_score')->nullable();
            $table->string('numerical_score')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('process_scores');
    }
}