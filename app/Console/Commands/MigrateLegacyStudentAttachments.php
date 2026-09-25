<?php

namespace App\Console\Commands;

use App\Models\Image;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateLegacyStudentAttachments extends Command
{
    protected $signature = 'attachments:migrate-legacy';

    protected $description = 'Move student attachments from public/attachments/students into private storage';

    public function handle()
    {
        $legacyRoot = public_path('attachments/students');
        $moved = 0;
        $missing = 0;

        Image::where('imageable_type', Student::class)->whereNull('path')->each(function (Image $image) use ($legacyRoot, &$moved, &$missing) {
            $student = Student::withTrashed()->find($image->imageable_id);
            $source = $student ? $this->findLegacyFile($legacyRoot, $student, $image->filename) : null;

            if (!$source) {
                $this->warn("No legacy file for image #{$image->id} ({$image->filename})");
                $missing++;
                return;
            }

            $extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));
            $path = 'students/' . $student->id . '/' . Str::uuid() . ($extension ? '.' . $extension : '');
            Storage::disk('upload_attachments')->put($path, file_get_contents($source));

            $image->path = $path;
            $image->save();
            unlink($source);
            $moved++;
        });

        $this->info("Moved {$moved} attachment(s), {$missing} not found.");
        $this->line("Review anything left in {$legacyRoot}: it is still publicly reachable.");

        return 0;
    }

    // Old uploads were stored under the student's name in whichever locale was active at upload time.
    private function findLegacyFile($legacyRoot, Student $student, $filename)
    {
        foreach (array_unique(array_values($student->getTranslations('name'))) as $name) {
            $candidate = $legacyRoot . '/' . $name . '/' . basename($filename);
            if (is_file($candidate) && Str::startsWith(realpath($candidate), realpath($legacyRoot) . DIRECTORY_SEPARATOR)) {
                return $candidate;
            }
        }

        return null;
    }
}
