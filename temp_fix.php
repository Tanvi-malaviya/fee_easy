<?php
$students = \App\Models\Student::orderBy('id')->get();
$i = 1;
foreach($students as $s) {
    $institute = $s->institute ?? \App\Models\Institute::find($s->institute_id);
    $code = $institute ? ($institute->institute_code ?? 'INST') : 'INST';
    $prefix = date('Y') . $code;
    $s->enrollment_id = $prefix . str_pad($i, 5, '0', STR_PAD_LEFT);
    $s->save();
    $i++;
}
echo 'Updated ' . $students->count() . ' students.';
