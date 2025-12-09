public function up()
{
    Schema::create('competitions', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->text('stages')->nullable(); // Tahapan lomba
        $table->string('poster_path')->nullable();
        $table->string('guidebook_path')->nullable();
        $table->string('source_link')->nullable();
        $table->timestamps();
    });
}
