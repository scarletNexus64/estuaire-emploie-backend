<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute 'student' à l'enum `users.role` pour en faire un vrai rôle, à côté
     * de candidate/recruiter/admin. Le rôle pilote l'espace de navigation
     * (Espace Étudiant). L'accès aux contenus payants reste géré par le service
     * premium "student_mode" (cf. User::hasStudentMode()).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'candidate', 'recruiter', 'student') NOT NULL DEFAULT 'candidate'");
    }

    /**
     * Reverse the migrations.
     *
     * Bascule les éventuels comptes 'student' vers 'candidate' avant de retirer
     * la valeur de l'enum, pour éviter une erreur de troncature MySQL.
     */
    public function down(): void
    {
        DB::statement("UPDATE `users` SET `role` = 'candidate' WHERE `role` = 'student'");
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'candidate', 'recruiter') NOT NULL DEFAULT 'candidate'");
    }
};
