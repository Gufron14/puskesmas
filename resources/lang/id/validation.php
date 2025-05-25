<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan kesalahan default yang digunakan oleh
    | kelas validator. Beberapa aturan memiliki versi yang berbeda seperti
    | aturan ukuran. Silakan sesuaikan setiap pesan di sini.
    |
    */

    'accepted' => 'Kolom :attribute harus diterima.',
    'accepted_if' => 'Kolom :attribute harus diterima jika :other adalah :value.',
    'active_url' => 'Kolom :attribute bukan URL yang valid.',
    'after' => 'Kolom :attribute harus tanggal setelah :date.',
    'after_or_equal' => 'Kolom :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Kolom :attribute harus berupa array.',
    'before' => 'Kolom :attribute harus tanggal sebelum :date.',
    'before_or_equal' => 'Kolom :attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => 'Kolom :attribute harus antara :min dan :max.',
        'file' => 'Kolom :attribute harus antara :min dan :max kilobita.',
        'string' => 'Kolom :attribute harus antara :min dan :max karakter.',
        'array' => 'Kolom :attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean' => 'Kolom :attribute harus true atau false.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => 'Kolom :attribute bukan tanggal yang valid.',
    'date_equals' => 'Kolom :attribute harus tanggal yang sama dengan :date.',
    'date_format' => 'Kolom :attribute tidak cocok dengan format :format.',
    'declined' => 'Kolom :attribute harus ditolak.',
    'declined_if' => 'Kolom :attribute harus ditolak jika :other adalah :value.',
    'different' => 'Kolom :attribute dan :other harus berbeda.',
    'digits' => 'Kolom :attribute harus :digits digit.',
    'digits_between' => 'Kolom :attribute harus antara :min dan :max digit.',
    'dimensions' => 'Kolom :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'Kolom :attribute memiliki nilai duplikat.',
    'email' => 'Kolom :attribute harus alamat email yang valid.',
    'ends_with' => 'Kolom :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum' => 'Kolom :attribute yang dipilih tidak valid.',
    'exists' => 'Kolom :attribute yang dipilih tidak valid.',
    'file' => 'Kolom :attribute harus berupa file.',
    'filled' => 'Kolom :attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => 'Kolom :attribute harus lebih besar dari :value.',
        'file' => 'Kolom :attribute harus lebih besar dari :value kilobita.',
        'string' => 'Kolom :attribute harus lebih besar dari :value karakter.',
        'array' => 'Kolom :attribute harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => 'Kolom :attribute harus lebih besar dari atau sama dengan :value.',
        'file' => 'Kolom :attribute harus lebih besar dari atau sama dengan :value kilobita.',
        'string' => 'Kolom :attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array' => 'Kolom :attribute harus memiliki :value item atau lebih banyak.',
    ],
    'image' => 'Kolom :attribute harus berupa gambar.',
    'in' => 'Kolom :attribute yang dipilih tidak valid.',
    'in_array' => 'Kolom :attribute tidak ada di :other.',
    'integer' => 'Kolom :attribute harus berupa bilangan bulat.',
    'ip' => 'Kolom :attribute harus alamat IP yang valid.',
    'ipv4' => 'Kolom :attribute harus alamat IPv4 yang valid.',
    'ipv6' => 'Kolom :attribute harus alamat IPv6 yang valid.',
    'json' => 'Kolom :attribute harus string JSON yang valid.',
    'lt' => [
        'numeric' => 'Kolom :attribute harus kurang dari :value.',
        'file' => 'Kolom :attribute harus kurang dari :value kilobita.',
        'string' => 'Kolom :attribute harus kurang dari :value karakter.',
        'array' => 'Kolom :attribute harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'Kolom :attribute harus kurang dari atau sama dengan :value.',
        'file' => 'Kolom :attribute harus kurang dari atau sama dengan :value kilobita.',
        'string' => 'Kolom :attribute harus kurang dari atau sama dengan :value karakter.',
        'array' => 'Kolom :attribute tidak boleh memiliki lebih dari :value item.',
    ],
    'mac_address' => 'Kolom :attribute harus alamat MAC yang valid.',
    'max' => [
        'numeric' => 'Kolom :attribute tidak boleh lebih besar dari :max.',
        'file' => 'Kolom :attribute tidak boleh lebih besar dari :max kilobita.',
        'string' => 'Kolom :attribute tidak boleh lebih besar dari :max karakter.',
        'array' => 'Kolom :attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'mimes' => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'numeric' => 'Kolom :attribute harus minimal :min.',
        'file' => 'Kolom :attribute harus minimal :min kilobita.',
        'string' => 'Kolom :attribute harus minimal :min karakter.',
        'array' => 'Kolom :attribute harus memiliki minimal :min item.',
    ],
    'multiple_of' => 'Kolom :attribute harus kelipatan dari :value.',
    'not_in' => 'Kolom :attribute yang dipilih tidak valid.',
    'not_regex' => 'Format kolom :attribute tidak valid.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'password' => 'Kata sandi salah.',
    'present' => 'Kolom :attribute harus ada.',
    'prohibited' => 'Kolom :attribute tidak boleh ada.',
    'prohibited_if' => 'Kolom :attribute tidak boleh ada jika :other adalah :value.',
    'prohibited_unless' => 'Kolom :attribute tidak boleh ada kecuali jika :other berada di :values.',
    'prohibits' => 'Kolom :attribute melarang :other agar tidak hadir.',
    'regex' => 'Format kolom :attribute tidak valid.',
    'required' => 'Kolom :attribute wajib diisi.',
    'required_array_keys' => 'Kolom :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Kolom :attribute wajib diisi ketika :other adalah :value.',
    'required_unless' => 'Kolom :attribute wajib diisi kecuali :other berada di :values.',
    'required_with' => 'Kolom :attribute wajib diisi ketika :values ada.',
    'required_with_all' => 'Kolom :attribute wajib diisi ketika :values ada.',
    'required_without' => 'Kolom :attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => 'Kolom :attribute wajib diisi ketika tidak ada :values yang ada.',
    'same' => 'Kolom :attribute dan :other harus sama.',
    'size' => [
        'numeric' => 'Kolom :attribute harus :size.',
        'file' => 'Kolom :attribute harus :size kilobita.',
        'string' => 'Kolom :attribute harus :size karakter.',
        'array' => 'Kolom :attribute harus berisi :size item.',
    ],
    'starts_with' => 'Kolom :attribute harus diawali dengan salah satu dari: :values.',
    'string' => 'Kolom :attribute harus berupa string.',
    'timezone' => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Kolom :attribute sudah ada sebelumnya.',
    'uploaded' => 'Kolom :attribute gagal diunggah.',
    'url' => 'Format kolom :attribute tidak valid.',
    'uuid' => 'Kolom :attribute harus UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi khusus untuk atribut menggunakan
    | konvensi "attribute.rule" untuk memberi nama baris bahasa. Ini membuatnya mudah
    | untuk menentukan pesan bahasa khusus untuk aturan atribut tertentu.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'pesan-kustom',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar placeholder atribut kami
    | dengan sesuatu yang lebih mudah dibaca oleh pembaca seperti "Alamat Email" daripada
    | "email". Ini hanya membantu kita membuat pesan kita lebih ekspresif.
    |
    */

    'attributes' => [],

];
